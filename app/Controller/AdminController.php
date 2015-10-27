<?php
App::uses ( 'AppController', 'Controller' );
class AdminController extends AppController {
	public $uses = array (
			'Admin',
			'User',
			'Worktime',
			'Room'
	);

	public $layout ="admin";

	public $components = array (
			'Session',
			'Cookie',
			'Auth' => array (
					'loginRedirect' => array (
							'controller' => 'admin',
							'action' => 'index'
					),
					'logoutRedirect' => array (
							'controller' => 'admin',
							'action' => 'login'
					),
					'loginAction' => array (
							'controller' => 'admin',
							'action' => 'login'
					),//テーブル名がuserでないときは↓下記のように設定します。
					'authenticate' => array(
							'Form' => array(
									'userModel' => 'Admin'
							)
					)
					)
			);


	/**
	 * スタッフユーザー一覧の取得
	 */
	public function userindex(){
		$this->set('users',$this->User->getUserData());
		$this->render('userindex');
	}

	/**
	 * スタッフユーザー追加
	 */
	public function useradd() {

		// POST の時だけ
		if ($this->request->is ( 'post' )) {
			if ($this->User->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'スタッフの登録が成功しました。' ) );
				$this->redirect ( array (
						'action' => 'index'
				) );
			} else {
				$this->Session->setFlash ( 'スタッフの登録に失敗しました。' );
			}
		}
		$this->render ( 'userregist' );
	}


	/**
	 * スタッフユーザーの情報を更新
	 *
	 * @param unknown $id
	 * @throws NotFoundException
	 */
	public function userupdate($id = null) {
		$this->User->id = $id;

		if (! $this->User->exists ()) {
			throw new NotFoundException ( __ ( 'スタッフが存在しません。' ) );
		}

		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->User->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'スタッフの編集が成功しました。' ) );
				$this->redirect ( array (
						'action' => 'index'
				) );
			} else {
				$this->Session->setFlash ( __ ( 'スタッフの情報をを編集することができませんでした。もう一度実行してください。' ) );
			}
		} else {
			$this->request->data = $this->User->read ( null, $id );
		}
		$this->render ( 'userregist' );
	}

	/**
	 * スタッフの削除
	 *
	 * @param string $id ユーザーID
	 * @throws NotFoundException
	 */
	public function userdelete( $id = null){

		$this->User->id=$id;

		if (! $this->User->exists ()) {
			throw new NotFoundException ( __ ( 'スタッフが存在しません。' ) );
		}

		if($this->User->delete()){
			$this->Session->setFlash('スタッフの削除が完了しました。');
			$this->redirect(array('action'=>'index'));
		}else{
			$this->Sessin->setFlash('スタッフの削除に失敗しました');
		}
		$this->render ( 'userindex' );
	}


	public function beforeFilter() {
		$this->Auth->allow ( 'login', 'logout','amregist' );
	}

	/**
	 * ログイン時に入る管理画面のトップ
	 */
	public function index(){

	}

	/**
	 * 部屋一覧の取得
	 */
	public function roomindex(){
		$this->set('rooms',$this->Room->getRoomData());
		$this->render('roomindex');
	}

	/**
	 * 部屋追加
	 */
	public function roomadd() {

		// POST の時だけ
		if ($this->request->is ( 'post' )) {
			if ($this->Room->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( '部屋の登録が成功しました。' ) );
				$this->redirect ( array (
						'action' => 'index'
				) );

			} else {
				$this->Session->setFlash ( '登録に失敗しました。' );
			}
		}
		$this->render ( 'roomregist' );
	}


	/**
	 * 部屋の情報を更新
	 *
	 * @param unknown $id
	 * @throws NotFoundException
	 */
	public function roomupdate($id = null) {
		$this->Room->id = $id;

		if (! $this->Room->exists ()) {
			throw new NotFoundException ( __ ( 'データが存在しません。' ) );
		}

		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->Room->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( '部屋の編集が成功しました。' ) );
				$this->redirect ( array (
						'action' => 'index'
				) );
			} else {
				$this->Session->setFlash ( __ ( '部屋の情報をを編集することができませんでした。もう一度実行してください。' ) );
			}
		} else {
			$this->request->data = $this->Room->read ( null, $id );
		}
		$this->render ( 'roomregist' );
	}

	/**
	 * 部屋の削除
	 *
	 * @param string $id 部屋ID
	 * @throws NotFoundException
	 */
	public function roomdelete( $id = null){

		$this->Room->id=$id;

		if (! $this->Room->exists ()) {
			throw new NotFoundException ( __ ( '部屋が存在しません。' ) );
		}

		if($this->Room->delete()){
			$this->Session->setFlash('部屋の削除が完了しました。');
			$this->redirect(array('action'=>'index'));
		}else{
			$this->Sessin->setFlash('部屋の削除に失敗しました');
		}
		$this->render ( 'roomindex' );
	}


	/**
	 * 管理者の登録
	 */
	public function amregist(){

		if ($this->request->is ( 'post' )) {

			if ($this->Admin->save ( $this->request->data )) {
				$this->redirect ( array (
						'action' => 'index'
				) );
			} else {
				$this->Session->setFlash ( '登録に失敗しました。' );
			}
		}
		$this->render ( 'amregist' );
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
				$this->Session->setFlash ( '管理画面ログインに失敗しました。正しいユーザー名とパスワードを入力してください。', 'default', array (), 'auth' );
			}
		}
	}

	/**
	 * ログアウト処理を行います
	 */
	public function logout() {
		$this->redirect ( $this->Auth->logout () );
	}
}