<?php
App::uses ( 'AppController', 'Controller' );
class UsersController extends AppController {

	public $helpers = array('Html', 'Form');

	public $uses = array (
			'User',
			'Worktime',
			'Room'
	);

	public $layout    = 'mobile';

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
	 * ユーザーの時間の記録
	 *
	 * @throws NotFoundException
	 */
	public function regist() {
		$userId = $this->Auth->user ( 'id' );

		$user = $this->Auth->user ();
		if (empty ( $user )) {
			throw new NotFoundException ( __ ( 'データが存在しません。' ) );
		}

		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->Worktime->save ( $this->request->data )) {
			} else {
			}
		}

		$workTimeData = $this->Worktime->checkWorktimeData ( $userId );
		$worktimeStatusArray = $this->Worktime->getStatusMessage ( $workTimeData );
		$this->set ( "worktimeStatusArray", $worktimeStatusArray );

		$this->set ( "workTimeData", $workTimeData );
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