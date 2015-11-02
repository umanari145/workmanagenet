<?php
App::uses ( 'AppModel', 'Model' );
class Worktime extends AppModel {

	public $name = 'Worktime';

	public $belongsTo = 'User';

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
		} else {
			// 業務中→終了
			$worktimeStatusArray ['workstatus'] = 2;
			$worktimeStatusArray ['statusMessage'] = "終了します。";
		}
		return $worktimeStatusArray;
	}


	/**
	 * 勤務時間の履歴を取得
	 *
	 * @param unknown $existUserList 現状のユーザー
	 * @return 勤務履歴
	 */
	public function getWorkLine($existUserList = array()) {
		$workLine = $this->find ( 'all', array (
				'order' => array (
						'Worktime.start_time DESC'
				),
// 				'conditions' => array (
// 						'Worktime.user_id' => $existUserList
// 				)
		) );

		return $workLine;
	}
}