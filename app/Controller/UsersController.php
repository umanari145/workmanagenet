<?php
App::uses ( 'AppController', 'Controller' );
class UsersController extends AppController {

	public $helpers = array('Html', 'Form');

	public $uses = array (
			'User',
			'Worktime',
			'Room',
			'Activeworktime',
			'Reserve'
	);

	public $layout    = 'user';

	public $components = array (
			'Session',
			'Auth' => array (
					'loginAction' => array (
							'controller' => 'users',
							'action' => 'login'
					),
					'loginRedirect' => array (
							'controller' => 'users',
							'action' => 'regist'
					),
					'logoutRedirect' => array (
							'controller' => 'users',
							'action' => 'login'
					)
			)
	);

	public function beforeFilter() {
		AuthComponent::$sessionKey = 'Auth.users';
		$this->Auth->allow ( 'login', 'logout' );
	}


	/**
	 * ログインメソッド
	 */
	public function login() {
		if ($this->Auth->loggedIn ()) {
			$this->redirect ( $this->Auth->redirect () );
		}
		if ($this->request->is ( 'post' )) {
			if ($this->Auth->login ()) {
				$this->redirect ( $this->Auth->redirect () );
			} else {
				$this->Session->setFlash ( 'ログインに失敗しました。正しいユーザー名とパスワードを入力してください。', 'default', array (), 'auth' );
			}
		}
	}

	/**
	 * 部屋の予約を行う
	 */
	public function reserveroom() {
		$userId = $this->Auth->user ( 'id' );

		$user = $this->Auth->user ();
		if (empty ( $user )) {
			$this->Session->setFlash (__ ( 'データが存在しません。' ) );
		}

		if ($this->request->is ( 'post' )) {
			//部屋の予約
			if (! empty ( $this->request->data ['User'] ['start_date_pull_down_id'] )) {
				$this->request->data ['User'] ['user_id'] = $userId;
				$this->Reserve->reserveRoom ( $this->request->data );
				//$this->Reserve->sendReserveMail( $this->Reserve->getLastInsertID());
				$this->Session->setFlash ( __ ( '部屋の予約が成功しました。' ) );
			}
			//部屋と予約対象期間の変更
			$roomId = $this->request->data ['User'] ['room_id'];
			$startPeriod = $this->request->data ['User'] ['reserve_period'];
		} else {
			$resData = $this->Room->find ( 'first', array (
					'conditions' => array (
							'Room.is_delete' => 0,
							'Room.room_name <>'=> '在宅'
					)
			) );
			$roomId = $resData ['Room'] ['id'];
			$startPeriod = date('Y/m/d');
		}

		$roomIdArr = $this->Room->getRoomList ();
		$roomScheduleeArr = $this->Reserve->createAvailabelTime ( $roomIdArr,$startPeriod,false );

		$this->set ( "roomList", $this->Room->getRoomList (true) );
		$this->set ( "weekPullDownList", $this->Reserve->makeWeekPeriodPullDown());
		$this->set ( "roomId", $roomId );
		$this->set ( "startPeriod", $startPeriod );
		$this->set ( "weekArr", array_keys($roomScheduleeArr[$roomId]['timeline']));
		$this->set ( "masterTimelineArr", $this->Reserve->getTimeline () );
		$this->set ( "roomScheduleeArr", $roomScheduleeArr [$roomId] );
		$this->set ( "userInfo", $this->Auth->user () );
	}

	public function sayRes(){
		$this->autoRender = FALSE;
		echo $this->request->data["hoge"];
	}

	/**
	 * ある特定の時間帯が予約可能か
	 */
	public function canReserveDate() {
		$this->autoRender = FALSE;
		if ($this->Reserve->hasDuplicateReserved ( $this->request->data )) {
			echo "fail";
		} else {
			echo "success";
		}
	}

	/**
	 * ユーザーごとに予約しているリストをみる
	 */
	public function viewreservelist($reserveId = null) {
		$userId = $this->Auth->user ( 'id' );

		$user = $this->Auth->user ();
		if (empty ( $user )) {
			$this->Session->setFlash ( __ ( 'データが存在しません。' ) );
		}


		if (! empty ( $reserveId )) {
			$reserveData = $this->Reserve->find ( 'first', array (
					"conditions" => array (
							"Reserve.id" => $reserveId
					)
			) );

			if ($userId !== $reserveData ["User"] ["id"]) {
				$this->Session->setFlash ( __ ( '不正なアクセスです' ) );
			} else {
				$this->Reserve->delete ( $reserveId );
				$this->Session->setFlash ( __ ( '予約データを削除しました。' ) );
			}
		}
		$this->set ( "userInfo", $this->Auth->user () );
		$this->set ( 'reserveList', $this->Reserve->getReserveListByUser ( $userId ) );
	}

	/**
	 * ユーザーの時間の記録
	 *
	 * @throws NotFoundException
	 */
	public function regist() {
		$userId = $this->Auth->user ( 'id' );

		$user = $this->Auth->user ();
		if (empty ( $user )) {
			$this->Session->setFlash ( __ ( 'データが存在しません。' ) );
		}

		$targetMonthVal = date ( "Y/m" );
		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {

			// 対象月変更
			if (isset ( $this->request->data ["User"] ["target_month_pulldown_id"] )) {
				$targetMonthVal = $this->request->data ["User"] ["target_month_pulldown_id"];
			}

			// 稼働開始スタート
			if ($this->Worktime->save ( $this->request->data )) {

				$worktimeId = $this->Worktime->getLastInsertID ();
				if (empty ( $worktimeId )) {
					$worktimeId = $this->request->data ["Worktime"] ["id"];
				}
				$this->Worktime->sendWorkMail ( $worktimeId );
			}
		}

		$workTimeData = $this->Worktime->checkWorktimeData ( $userId );
		$worktimeStatusArray = $this->Worktime->getStatusMessage ( $workTimeData );

		$this->set ( "worktimeStatusArray", $worktimeStatusArray );
		$this->set ( "workTimeData", $workTimeData );
		// 直前まで使っていた部屋を初期設定にする
		if ($worktimeStatusArray ["workstatus"] === 1) {
			$workLine = $this->Worktime->getWorkLineByUserId ( $userId );
			if (! empty ( $workLine )) {
				$this->set ( "lastUsedRoomId", $workLine [0] ["Worktime"] ["room_id"] );
			}
		}

		$this->set ( "targetMonthVal", $targetMonthVal );
		$this->set ( "rewardMonthList", $this->Activeworktime->makeTargetRewardArray () );
		$this->set ( "montlyReward", $this->Activeworktime->getMonthlyReward ( $userId, $targetMonthVal ) );
		$this->set ( "roomList", $this->Room->getRoomList () );
		$this->set ( "userInfo", $this->Auth->user () );
		$this->render ( 'regist' );
	}




	/**
	 * ログアウト処理を行います
	 */
	public function logout() {
		$this->redirect ( $this->Auth->logout () );
	}
}