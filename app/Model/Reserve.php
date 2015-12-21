<?php
App::uses ( 'AppModel', 'Model' );
App::import('Vendor', 'util/sendmail');

class Reserve extends AppModel {

	public $belongsTo = array (
			'User',
			'Room'
	);

	/**
	 * 予約済のスケジュールの作成
	 *
	 * @param unknown $roomIdArr
	 * @param $weekPeriod 対象期間
	 * @param $isAdmin 管理者か否か
	 * @param $startPeriod 開始日
	 * @return multitype:multitype:予約済みを含んだ一週間分のタイムスケジュール
	 */
	public function createAvailabelTime($roomIdArr,$startPeriod = "" ,$isAdmin = false) {

		$roomScheduleeArr = array ();
		foreach ( $roomIdArr as $roomId => $roomName ) {

			$roomScheduleeArr [$roomId] = [
					"room_name" => $roomName,
					"timeline" => $this->makeRegularTimeLine ($startPeriod)
			];
		}
		$reservedTimeline = $this->getReserveTimeline ($isAdmin,$startPeriod);

		$reservedTimeline2 = $this->convertReserveTime($reservedTimeline, $isAdmin);
		if( $isAdmin === true ){
			$this->checkIsReservedTimelineByAdmin ( $roomScheduleeArr, $reservedTimeline2 );
		}else{
			$this->checkIsReservedTimeline ( $roomScheduleeArr, $reservedTimeline2 );
		}
		//対象機関からずれる日程を削除
		$this->fairByPeriod( $roomScheduleeArr, $startPeriod);

		return $roomScheduleeArr;
	}

	/**
	 * 出勤時メール確認
	 *
	 * @param unknown $requestData
	 *        	リクエストデータ
	 */
	public function sendReserveMail($reserveRecordId) {
		$record = $this->find ( 'first', array (
				'fields' => array (
						"Room.room_name",
						"User.japanese_name",
						"DATE_FORMAT( Reserve.start_reserve_date , '%Y/%m/%d %H:%i' ) as start_time",
						"DATE_FORMAT( Reserve.end_reserve_date, '%Y/%m/%d %H:%i' ) as end_time"
				),
				'conditions' => array (
						'Reserve.id' => $reserveRecordId
				)
		) );

		$mailMessage = $this->makeReserveMailMessage($record);
		$sendmail = new Sendmail();
		$sendmail->sendGridMail("予約連絡メール", $mailMessage );
	}

	/**
	 * 部屋予約メールの文面
	 *
	 * @param $reserveData 予約レコード
	 * @return string メールのメッセージ
	 */
	private function makeReserveMailMessage($reserveData) {

		$staffName = $reserveData ["User"] ["japanese_name"];
		$roomName = $reserveData ["Room"] ["room_name"];
		$startTime = $reserveData[0]["start_time"];
		$endTime = $reserveData[0]["end_time"];

		$mailMessage = "スタッフ名　" . $staffName . "さん\r\n ". "予約時刻　" . $startTime." ～ ". $endTime;
		$mailMessage .=" \r\n " . "予約部屋名　" . $roomName;

		return $mailMessage;
	}

	/**
	 * 対象期間を外れる予約日程を削除する
	 *
	 * @param unknown $roomScheduleeArr
	 * @param string $startPeriod
	 */
	private function fairByPeriod( &$roomScheduleeArr, $startPeriod=""){

		$startPeriod = (! empty ( $startPeriod )) ? $startPeriod : date ( "Y/m/d" );
		$lastPeriod =  date("Y/m/d " , strtotime(" +6days", strtotime($startPeriod)));

		foreach( $roomScheduleeArr as &$schedule){
			//スケジュール外の日程を削除
			foreach ( $schedule["timeline"] as $targetPeriod => $data){
				if( strtotime($targetPeriod) < strtotime( $startPeriod) ||
					strtotime($lastPeriod) < strtotime($targetPeriod) ){
					unset( $schedule["timeline"][$targetPeriod]);
				}
			}
		}
	}

	/**
	 * 期間ごとのプルダウン作成
	 *
	 * @return 週の配列
	 */
	public function makeWeekPeriodPullDown(){
		$weekArr = array ();
		for($i = 0; $i < 8; $i ++) {
			$timelineArr = array ();
			$dateVal = date ( "Y/m/d", strtotime ( "+" . $i . "weeks" ) );
			$lastDateVal = date("Y/m/d" , strtotime(" +6days", strtotime($dateVal)));
			$weekArr [$dateVal] = $dateVal . "～" . $lastDateVal;
		}
		return $weekArr;
	}

	/**
	 * 一週間分の日付と時間軸の配列を作成する
	 *
	 * @param $startPeriod 開始日
	 * @return 1週間分の日付の配列
	 */
	private function makeRegularTimeLine($startPeriod = "") {
		$startPeriod = (! empty ( $startPeriod )) ? $startPeriod : date ( "Y/m/d" );

		$weekArr = array ();
		for($i = 0; $i < 7; $i ++) {
			$timelineArr = array ();
			$dateVal = date ( "Y/m/d", strtotime ( "+" . $i . "days", strtotime ( $startPeriod ) ) );
			$weekArr [$dateVal] = array_fill ( 1, 24, false );
		}
		return $weekArr;
	}

	/**
	 * すでに予約済みのレコードを取得
	 * @param $isAdmin 管理者か否か
	 * @param $startPeriod 開始日
	 * @return 予約済の部屋データの取得
	 *
	 */
	private function getReserveTimeline($isAdmin = false,$startPeriod = "") {

		$startPeriod = (! empty ( $startPeriod )) ? $startPeriod : date ( "Y/m/d" );
		$lastPeriod =  date("Y/m/d 23:59:59" , strtotime(" +6days", strtotime($startPeriod)));


		$conditions = array (
				'fields' => array (
						"room_id",
						"user_id",
						"start_reserve_date",
						"end_reserve_date"
				),
				'conditions' => array (
						'Reserve.start_reserve_date <' => $lastPeriod,
						'Reserve.end_reserve_date >' => $startPeriod
				)
		);

		if( $isAdmin === true){
			$conditions['fields'][] ="User.japanese_name";
		}
		return $this->find ( 'all', $conditions );
	}

	/**
	 * 予約済の時間帯にチェックを入れる
	 *
	 * @param unknown $roomScheduleeArr 一週間分の部屋のスケジュール
	 * @param unknown $reservedTimeline 予約データ
	 */
	private function checkIsReservedTimeline(&$roomScheduleeArr, $reservedTimeline) {
		if (! empty ( $reservedTimeline )) {
			foreach ( $reservedTimeline as $timeline ) {
				$room_id = $timeline  ["room_id"];
				$timeline_id = $timeline  ["timeline_id"];
				$reservedData = $timeline  ["reserve_date"];
				$roomScheduleeArr [$room_id] ["timeline"] [$reservedData] [$timeline_id] = $timeline['user_id'];
			}
		}

	}

	/**
	 * 予約済の時間帯にユーザー情報を入れる(管理画面用)
	 *
	 * @param unknown $roomScheduleeArr 一週間分の部屋のスケジュール
	 * @param unknown $reservedTimeline 予約データ
	 */
	private function checkIsReservedTimelineByAdmin(&$roomScheduleeArr, $reservedTimeline) {
		if (! empty ( $reservedTimeline )) {

			foreach ( $reservedTimeline as $timeline ) {
				$room_id = $timeline  ["room_id"];
				$timeline_id = $timeline  ["timeline_id"];
				$reservedData = $timeline  ["reserve_date"];
				$roomScheduleeArr [$room_id] ["timeline"] [$reservedData] [$timeline_id] = $timeline["japanese_name"];
			}
		}

	}


	/**
	 * データベースのレコードを1時間単位の配列に区切り、処理しやすくする
	 *
	 * @param unknown $reservedTimeline 予約済みの時間軸
	 * @param $isAdmin 管理者か否か
	 * @return 変換後の予約済み配列
	 */
	private function convertReserveTime($reservedTimeline,$isAdmin = false) {
		$reservedTimeline2 = [ ];
		foreach ( $reservedTimeline as $timeline ) {
			$startDate = $timeline ["Reserve"] ["start_reserve_date"];
			$endDate = $timeline ["Reserve"] ["end_reserve_date"];

			$i = 1;
			while ( true ) {

				$addTime = date ( 'Y-m-d H:00:00', strtotime ( "+" . $i . " hour ", strtotime ( $startDate ) ) );
				$timelineId = intval ( date ( 'H', strtotime ( $addTime ) ) );
				$reserveDate = date ( 'Y/m/d', strtotime ( $addTime ) );
				$i ++;

				if ($timelineId === 0) {
					$reserveDate = date ( "Y/m/d", strtotime ( "-1 day", strtotime ( $reserveDate ) ) );
					$timelineId = 24;
				}

				$reservedTimelineChildren = [
						'user_id' => $timeline ["Reserve"] ["user_id"],
						'room_id' => $timeline ["Reserve"] ["room_id"],
						'reserve_date' => $reserveDate,
						'timeline_id' => $timelineId
				];

				if( $isAdmin === true){
					$reservedTimelineChildren["japanese_name"] = $timeline ["User"] ["japanese_name"];
				}

				$reservedTimeline2 [] = $reservedTimelineChildren;
				if ($addTime === $endDate) {
					break;
				}
			}
		}

		return $reservedTimeline2;
	}

	/**
	 * 重複するようなレコードがあるか
	 *
	 * @param unknown $data
	 * @return true(重複あり)/false(重複無し)
	 */
	public function hasDuplicateReserved($data) {
		$conditions = array (
				'conditions' => array (
						'Reserve.room_id' => $data ["room_id"],
						'Reserve.start_reserve_date <' => $data ["end_reserve_time"],
						'Reserve.end_reserve_date >' => $data ["start_reserve_time"]
				)
		);
		return  $this->find ( 'count', $conditions ) > 0;
	}

	/**
	 * 時間の配列を作成
	 * @return multitype:string
	 */
	public function getTimeline() {
		$masterTimelineArr = [ ];
		for($i = 1; $i <= 24; $i ++) {
			$startTime = ($i - 1) . ":00";
			$endTime = $i . ":00";
			$masterTimelineArr [$i] = $startTime . "-" . $endTime;
		}
		return $masterTimelineArr;
	}

	/**
	 * 予約を実行する
	 *
	 * @param unknown $data
	 */
	public function reserveRoom($data){

		$startTime= $data["User"]["start_date_pull_down_id"]." ".$data["User"]["start_hour_pull_down_id"];
		$endTime=$data["User"]["end_date_pull_down_id"]." ".$data["User"]["end_hour_pull_down_id"];

		$resistData = array(
				'user_id'=>$data['User']['user_id'],
				'room_id'=>$data['User']['room_id'],
				'start_reserve_date'=>$startTime,
				'end_reserve_date'=>$endTime
		);
		$this->save($resistData);
	}

	/**
	 * ユーザーごとに部屋の履歴を出す
	 *
	 * @param unknown $userId ユーザーID
	 */
	public function getReserveListByUser($userId){

		$reservedList =array();
		if(!empty($userId)){
			$conditions = array (
					'fields' => array (
							"Reserve.id",
							"Room.room_name",
							"Reserve.start_reserve_date",
							"Reserve.end_reserve_date"
					),
					'conditions' => array (
							'Reserve.user_id' => $userId,
							'Reserve.end_reserve_date >=' => date ( "Y/m/d" )
					),
					'order' => array(
							'Reserve.start_reserve_date' =>'asc'
					)
			);
			$reservedList = $this->find('all',$conditions);
		}
		return $reservedList;
	}
}