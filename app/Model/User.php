<?php
App::uses ( 'AppModel', 'Model' );
class User extends AppModel {
	public $name = 'User';
	public $hasMany = array (
			'Worktime' => array (
					'order' => 'Worktime.start_time DESC'
			)
	);

	/**
	 * ユーザー登録をするとき
	 * にパスワードを暗号化して記録する
	 *
	 * @see Model::beforeSave()
	 */
	public function beforeSave($option = array()) {

		// 空白の場合は削除
		if ($this->data [$this->alias] ['password'] === "") {
			unset ( $this->data [$this->alias] ['password'] );
		}

		if (isset ( $this->data [$this->alias] ['password'] )) {
			$this->data [$this->alias] ['password'] = AuthComponent::password ( $this->data [$this->alias] ['password'] );
		}

		return true;
	}

	/**
	 * ユーザー一覧を取得する
	 *
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getUserData() {
		$conditions = array (
				'fields' => array (
						'id',
						'username',
						'japanese_name'
				),
				'conditions' => array (
						'User.is_delete' => 0
				)
		);

		$users = $this->find ( 'all', $conditions );
		// 稼働情報をセット
		$this->setWorkInfo ( $users );
		return $users;
	}

	/**
	 * 稼働情報をセットする
	 *
	 * @param unknown $users ユーザーリスト
	 * @return unknown
	 */
	private function setWorkInfo( &$users) {
		foreach ( $users as &$user ) {
			$last_login_time = "";
			$work_count = 0;
			$work_sum_time = 0;

			if ($user ["Worktime"] !== array ()) {
				$last_login_time = $this->getLastLoginTime ( $user );
				$work_count = $this->countWorkCountByPresentMonth( $user );
				$work_sum_time = $this->calcWorkSumTimeByPresentMonth( $user );
			}
			// 最終ログインタイムの記録
			$user ["User"] ["last_login_time"] = $last_login_time;
			// 当月稼働回数の計算
			$user ["User"] ["work_count"] = $work_count;
			// 当月稼働時間の計算
			$user ["User"] ["work_sum_time"] = $work_sum_time;

			App::import ( 'Model', 'Activeworktime' );
			$ActiveWorkModel = new Activeworktime();
			$activeworkData = $ActiveWorkModel->getMonthlyReward($user['User']['id'], date('Y/m'));

			$user['User']['sum_reward'] = $activeworkData[0][0]['sum_reward'];
		}
	}

	/**
	 * 最終ログインタイムを記録する
	 *
	 * @param unknown $users
	 *        	ユーザーデータ
	 * @return unknown 最終ログイン日時
	 */
	private function getLastLoginTime($user) {
		$lastLoginTime = "";
		if ($user ["Worktime"] [0] ["workstatus"] === "1") {
			$lastLoginTime = $user ["Worktime"] [0] ["start_time"];
		} else if ($user ["Worktime"] [0] ["workstatus"] === "2") {
			$lastLoginTime = $user ["Worktime"] [0] ["end_time"];
		}
		return $lastLoginTime;
	}

	/**
	 * 稼働回数の計算
	 *
	 * @param unknown $user
	 *        	ユーザー
	 * @return 稼働回数
	 */
	private function countWorkCountByPresentMonth($user) {
		$target_month = date ( 'Y-m', strtotime ( "now" ) );
		$count = 0;
		foreach ( $user ["Worktime"] as $workData ) {

			if (empty ( $workData )) {
				continue;
			}

			if (date ( 'Y-m', strtotime ( $workData ["start_time"] ) ) === $target_month) {
				$count ++;
			}
		}
		return $count;
	}

	/**
	 * 稼働時間の算出
	 *
	 * @param unknown $user
	 *        	ユーザー
	 * @return 稼働時間
	 */
	private function calcWorkSumTimeByPresentMonth($user) {
		$target_month = date ( 'Y-m', strtotime ( "now" ) );

		$sumTime = 0;

		foreach ( $user ["Worktime"] as $workData ) {

			if (empty ( $workData ) || $workData ["workstatus"] !== "2") {
				continue;
			}

			$start_time = $workData ["start_time"];
			$end_time = $workData ["end_time"];

			if (date ( "Y-m", strtotime ( $start_time ) ) === $target_month) {
				$sumTime += (strtotime ( $end_time ) - strtotime ( $start_time ));
			}
		}
		return $sumTime;
	}

	/**
	 * 特定ユーザーを取得する
	 *
	 * @param unknown $id
	 */
	public function getSingleUserData($id) {
		$conditions = array (
				'fields' => array (
						'id',
						'chatgirl_id',
						'username',
						'japanese_name',
						'email',
						'transfer_account'
				),
				'conditions' => array (
						'User.id' => $id
				)
		);
		return $this->find ( 'first', $conditions );
	}

	/**
	 * ユーザー一覧(プルダウン用のメソッド)
	 *
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getUserList() {
		$userList=[];
		$conditions = array (
				'fields' => array (
						'id',
						'japanese_name'
				),
				'conditions' => array (
						'User.is_delete' => 0
				)
		);
		$userList = $this->find ( 'list', $conditions );
		$userList[0] = "全員";
		ksort( $userList);
		return $userList;
	}
}