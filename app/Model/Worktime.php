<?php
App::uses ( 'AppModel', 'Model' );
App::import('Vendor', 'util/sendmail');

class Worktime extends AppModel {
	public $name = 'Worktime';
	public $belongsTo = array (
			'User',
			'Room'
	);

	/**
	 * 現状スタッフが業務開始か作業中かを判断する
	 *
	 * @param $id ユーザーId
	 * @return 業務開始:array{} 業務中:ユーザーのレコード
	 */
	public function checkWorktimeData($id) {
		$workTimeData = $this->find ( 'first', array (
				'conditions' => array (
						'Worktime.user_id' => $id,
						'Worktime.workstatus' => '1'
				)
		) );
		return $workTimeData;
	}

	/**
	 * 作業ステータスとメッセージを取得
	 *
	 * @param $userReport ユーザーレポート
	 * @return 作業状況の配列
	 */
	public function getStatusMessage($workTimeData = array()) {
		$worktimeStatusArray = array (
				'workstatus' => "",
				'statusMessage' => ""
		);

		if ($workTimeData === array ()) {
			// 業務開始
			$worktimeStatusArray ['workstatus'] = 1;
			$worktimeStatusArray ['statusMessage'] = "開始します。";
			$worktimeStatusArray ['javascript'] = array();
		} else {
			// 業務中→終了
			$worktimeStatusArray ['workstatus'] = 2;
			$worktimeStatusArray ['statusMessage'] = "終了します。";
			$worktimeStatusArray ['javascript'] = array ();
		}
		return $worktimeStatusArray;
	}

	public function sendWorkMail( $registData ){
		var_dump($registData);

		//$sendmail = new Sendmail();
		//$sendmail->sendGridMail($title, $mailMessage);

	}

	/**
	 * 勤務時間の履歴を取得
	 *
	 * @param unknown $existUserList
	 *        	現状のユーザー
	 * @return 勤務履歴
	 */
	public function getWorkLine($existUserList = array()) {
		$workLine = $this->find ( 'all', array (
				'order' => array (
						'Worktime.start_time DESC'
				)
		)
		// 'conditions' => array (
		// 'Worktime.user_id' => $existUserList
		// )
		 );

		foreach ( $workLine as &$work ) {
			$this->calcWorkTimeFromStartToEnd ( $work );
		}

		return $workLine;
	}

	/**
	 * 稼働時間の算出
	 *
	 * @param unknown $work
	 *        	勤務レコード
	 * @return number 稼働時間
	 */
	public function calcWorkTimeFromStartToEnd(&$work) {
		$workingtime = 0;
		if ($work ["Worktime"] ["workstatus"] === "2" && ! empty ( $work ["Worktime"] ["start_time"] ) && ! empty ( $work ["Worktime"] ["end_time"] )) {
			$workingtime = strtotime ( $work ["Worktime"] ["end_time"] ) - strtotime ( $work ["Worktime"] ["start_time"] );
		}
		$work ["Worktime"] ["working_time"] = $workingtime;
	}

	/**
	 * 作業開始から24時間経過した勤務履歴を抽出
	 *
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getForgetLogoutData() {
		$criterion_time = date ( 'Y-m-d H:i:s', strtotime ( "-1day" ) );

		$workLine = $this->find ( 'all', array (
				'order' => array (
						'Worktime.start_time DESC'
				),
				'conditions' => array (
						'Worktime.workstatus' => "1",
						'Worktime.start_time <= ' => $criterion_time,
						'Worktime.end_time ' => null
				)
		) );
		$sqlLog = $this->getDataSource()->getLog(false, false);
		debug($sqlLog , false);
		return $workLine;
	}


	/**
	 * ログアウト忘れと思われる勤務履歴を更新
	 * (更新日時はバッチを回したとき。ステータスは3にする)
	 *
	 * @param unknown $workData
	 */
	public function updateForgetWorkLine($workData) {

		foreach ( $workData as $workline ) {
			$record = array ();
			$record = array (
					"id" => $workline ["Worktime"] ["id"],
					"end_time" => date ( "Y-m-d H:i:s" ),
					"workstatus" => "3"
			);
			$this->create ();
			$this->save ( $record );
			$sqlLog = $this->getDataSource()->getLog(false, false);
			debug($sqlLog , false);
		}
	}

	/**
	 * あるユーザーの勤務履歴を取得
	 *
	 * @param unknown $userId
	 *        	ユーザーid
	 * @return 勤務履歴
	 */
	public function getWorkLineByUserId($userId = null) {
		$workLine = $this->find ( 'all', array (
				'order' => array (
						'Worktime.start_time DESC'
				),
				'conditions' => array (
						'Worktime.user_id' => $userId
				)
		) );
		return $workLine;
	}

	/**
	 * 開始時間、終了時間の記録
	 *
	 * @see Model::beforeSave()
	 */
	public function beforeSave($option = array()) {
		if (! empty ( $this->data [$this->alias] ['workstatus'] )) {
			switch ($this->data [$this->alias] ['workstatus']) {
				case "1" :
					$this->data [$this->alias] ['start_time'] = date ( 'Y-m-d H:i:s' );
					break;
				case "2" :
					$this->data [$this->alias] ['end_time'] = date ( 'Y-m-d H:i:s' );
					break;
				default :
					break;
			}
		}

		return true;
	}
}