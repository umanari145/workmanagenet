<?php
App::uses ( 'AppModel', 'Model' );

class Reserve extends AppModel {

	public $belongsTo = array (
			'User',
			'Room'
	);

	/**
	 * 予約済のスケジュールの作成
	 *
	 * @param unknown $roomIdArr
	 * @return multitype:multitype:予約済みを含んだ一週間分のタイムスケジュール
	 */
	public function createAvailabelTime($roomIdArr) {
		$roomScheduleeArr = array ();
		foreach ( $roomIdArr as $roomId => $roomName ) {

			$roomScheduleeArr [$roomId] = [
					"room_name" => $roomName,
					"timeline" => $this->makeRegularTimeLine ($roomId)
			];
		}
		$reservedTimeline = $this->getReserveTimeline ();
		$reservedTimeline2 = $this->convertReserveTime($reservedTimeline);
		$this->checkIsReservedTimeline ( $roomScheduleeArr, $reservedTimeline2 );
		return $roomScheduleeArr;
	}

	/**
	 * 一週間分の日付と時間軸の配列を作成する
	 * @return 1週間分の日付の配列
	 */
	private function makeRegularTimeLine(){
		$weekArr = array ();
		for($i = 0; $i < 7; $i ++) {
			$timelineArr = array ();
			$dateVal = date ( "Y/m/d", strtotime ( "+" . $i . "days" ) );
			$weekArr [$dateVal] = array_fill(1,24, false);
		}
		return $weekArr;

	}

	/**
	 * 1週間の配列を作成
	 * @return multitype:
	 */
	public function makeWeekArr(){
		$weekArr=array();
		for($i = 0; $i < 7; $i ++) {
			$timelineArr = array ();
			$dateVal = date ( "Y/m/d", strtotime ( "+" . $i . "days" ) );
			$weekArr[]=$dateVal;
		}
		return $weekArr;
	}

	/**
	 * すでに予約済みのレコードを取得
	 * @return 予約済の部屋データの取得
	 */
	private function getReserveTimeline() {
		$conditions = array (
				'fields' => array (
						"room_id",
						"user_id",
						"start_reserve_date",
						"end_reserve_date"
				),
				'conditions' => array (
						'Reserve.end_reserve_date >=' => date ( "Y/m/d" )
				)
		);
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
				$roomScheduleeArr [$room_id] ["timeline"] [$reservedData] [$timeline_id] = true;
			}
		}

	}

	/**
	 * データベースのレコードを1時間単位の配列に区切り、処理しやすくする
	 *
	 * @param unknown $reservedTimeline 予約済みの時間軸
	 * @return 変換後の予約済み配列
	 */
	private function convertReserveTime($reservedTimeline) {
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
						'Reserve.user_id' => $data ["user_id"],
						'Reserve.room_id' => $data ["room_id"],
						'Reserve.start_reserve_date <' => $data ["end_reserve_time"],
						'Reserve.end_reserve_date >' => $data ["start_reserve_time"]
				)
		);
		return $this->find ( 'count', $conditions ) > 0;
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
					)
			);
			$reservedList = $this->find('all',$conditions);
		}
		return $reservedList;
	}
}