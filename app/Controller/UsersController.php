<?php


App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $uses = array('User');
    public $components=array('Session',
    		'Auth' => array(
        'loginRedirect' => array('controller' => 'worktimes', 'action' => 'regist'),
        'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
    )
);
    public function beforeFilter(){
       $this->Auth->allow('login','logout');
    }


	public function create(){

	}

	public function index(){
		$this->set( 'users' , $this->paginate());
	}

	/**
	 * ログインメソッド
	 *
	 */
    public function login() {
        if( $this->Auth->loggedIn()) {
            $this->redirect($this->Auth->redirect());
        }
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {

                $this->redirect($this->Auth->redirect());
            }else{
                $this->Session->setFlash('ログインに失敗しました。正しいユーザー名とパスワードを入力してください。', 'default', array(), 'auth');
            }
        }
    }

	/**
	 * ユーザーの情報を表示する
	 *
	 * @param unknown $id
	 * @throws NotFoundException
	 */
	public function update($id) {
		$this->User->id = $id;

		if (! $this->User->exists ()) {
			throw new NotFoundException ( __ ( 'データが存在しません。' ) );
		}

		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->User->save ( $this->request->data )) {
				$this->Session->setFlash ( __ ( 'スタッフの編集が成功しました。' ) );
				$this->redirect ( array (
						'action' => 'index'
				) );
			} else {
				$this->Session->setFlash ( __ ( '商品を保存することができませんでした。もう一度実行してください。' ) );
			}
		} else {
			$this->request->data = $this->User->read ( null, $id );
		}

		$this->render ( 'regist' );
	}


    /**
     * ユーザー追加
     */
    public function add(){

        // POST の時だけ
        if ($this->request->is('post')) {
            if ($this->User->save($this->request->data)) {
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash('登録に失敗しました。');
            }
        }
		$this->render('regist');
    }

    /**
     * ログアウト処理を行います
     */
    public function logout() {
        $this->redirect($this->Auth->logout());
    }
}