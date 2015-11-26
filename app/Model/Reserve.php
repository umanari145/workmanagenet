<?php
App::uses ( 'AppModel', 'Model' );

class Reserve extends AppModel {

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
					"timeline" => $this->makeRegularTimeLine ()
			];
		}

		$reservedTimeline = $this->getReserveTimeline();
		$this->checkIsReservedTimeline($roomScheduleeArr, $reservedTimeline);
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
			$dateVal = date ( "m/d", strtotime ( "+" . $i . "days" ) );
			$weekArr [$dateVal] = array_fill(1,48, false);
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
			$dateVal = date ( "m/d", strtotime ( "+" . $i . "days" ) );
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
						"DATE_FORMAT(reserve_date,'%m/%d') AS reserve_date",
						"timeline_id"
				),
				'conditions' => array (
						'Reserve.reserve_date >=' => date ( "Y/m/d" )
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
				$room_id = $timeline ["Reserve"] ["room_id"];
				$timeline_id = $timeline ["Reserve"] ["timeline_id"];
				$reservedData = $timeline [0] ["reserve_date"];
				$roomScheduleeArr [$room_id] ["timeline"] [$reservedData] [$timeline_id] = true;
			}
		}
	}
}