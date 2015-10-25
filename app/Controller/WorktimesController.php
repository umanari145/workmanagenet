<?php


App::uses('AppController', 'Controller');

class WorktimesController extends AppController {

    public $uses = array('User','Worktime','Room');

	public function regist(){

		$userId = $this->Auth->user('id');


		if ( empty( $this->Auth->user())) {
			throw new NotFoundException ( __ ( 'データが存在しません。' ) );
		}


		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {
			if ($this->Worktime->save ( $this->request->data )) {

			} else {

			}

		}

		$workTimeData  = $this->Worktime->checkWorktimeData( $userId );
		$worktimeStatusArray = $this->Worktime->getStatusMessage($workTimeData);
		$this->set("worktimeStatusArray",$worktimeStatusArray);

		$this->set("workTimeData", $workTimeData);
		$this->set("roomList",$this->Room->getRoomList());
		$this->set("userInfo",$this->Auth->user());


		$this->render ( 'regist' );

	}

	public function index(){
		$this->set( 'users' , $this->paginate());
	}

    /**
     * ログアウト処理を行います
     */
    public function logout() {
        $this->redirect($this->Auth->logout());
    }
}