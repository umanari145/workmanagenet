<?php
App::uses ( 'AppController', 'Controller' );
App::import ( 'Vendor', 'util/sendmail' );
class AdminController extends AppController {
	public $helpers = array (
			'Html',
			'Form',
			'Customize',
			'Number'
	);
	public $uses = array (
			'Admin',
			'User',
			'Worktime',
			'Activeworktime',
			'Room',
			'Reserve',
			'Service'
	);
	public $layout = "admin";
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
					), // テーブル名がuserでないときは↓下記のように設定します。
					'authenticate' => array (
							'Form' => array (
									'userModel' => 'Admin'
							)
					)
			)
	);

	/**
	 * スタッフユーザー一覧の取得
	 */
	public function userindex() {
		$this->set ( 'users', $this->User->getUserData () );
		$this->render ( 'userindex' );
	}

	/**
	 * スタッフユーザー追加
	 */
	public function useradd() {

		// POST の時だけ
		if ($this->request->is ( 'post' )) {
			if ($this->User->save ( $this->request->data )) {
				$this->Session->setFlash ( 'スタッフの登録が成功しました。' ,'default' , array('class' => 'success') );
				$this->redirect ( array (
						'action' => 'userindex'
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
				$this->Session->setFlash ( 'スタッフの編集が成功しました。','default' , array('class' => 'success') );
				$this->redirect ( array (
						'action' => 'userindex'
				) );
			} else {
				$this->Session->setFlash ( __ ( 'スタッフの情報をを編集することができませんでした。もう一度実行してください。' ) );
			}
		} else {
			$this->request->data = $this->User->getSingleUserData ( $id );
		}
		$this->render ( 'userregist' );
	}

	/**
	 * スタッフの削除
	 *
	 * @param string $id
	 *        	ユーザーID
	 * @throws NotFoundException
	 */
	public function userdelete($id = null) {
		$this->User->id = $id;

		if (! $this->User->exists ()) {
			throw new NotFoundException ( __ ( 'スタッフが存在しません。' ) );
		}

		$data = array (
				"id" => $id,
				"is_delete" => 1
		);

		if ($this->User->save ( $data )) {
			$this->Session->setFlash ( 'スタッフの削除が完了しました。','default' , array('class' => 'success') );
			$this->redirect ( array (
					'action' => 'userindex'
			) );
		} else {
			$this->Sessin->setFlash ( 'スタッフの削除に失敗しました' );
		}
		$this->render ( 'userindex' );
	}

	/**
	 * 勤務一覧情報を出力
	 */
	public function userworkdata() {
		$this->set ( "workLine", $this->Worktime->getWorkLine () );
	}

	/**
	 * 勤務履歴詳細データを表示
	 *
	 * @param string $id
	 *        	勤務履歴id
	 * @throws NotFoundException
	 */
	public function workdetail($id = null) {
		$this->Worktime->id = $id;
		if (! $this->Worktime->exists ()) {
			throw new NotFoundException ( __ ( 'データが存在しません。' ) );
		}
		$workDetailData = $this->Worktime->find ( 'first', array (
				"conditions" => array (
						"Worktime.id" => $id
				)
		) );
		$this->Worktime->calcWorkTimeFromStartToEnd ( $workDetailData );
		$this->set ( "workdetail", $workDetailData );
	}

	/**
	 * 稼働履歴CSVアップロード
	 */
	private function activeworkcsvupload() {
		if ($this->request->is ( 'post' )) {
			try {
				$filename = $this->request->data ["Activeworktime"] ["CsvFile"] ["tmp_name"];
				if (file_exists ( $filename )) {
					$db = $this->Activeworktime->getDataSource ();
					$db->begin ( $this->Activeworktime );
					$this->Activeworktime->importCSV ( $filename );
					if (! $this->Activeworktime->getImportErrors ()) {
						$db->commit ( $this->Activeworktime );
						$this->Session->setFlash ( 'CSV登録に成功しました。' ,'default' , array('class' => 'success') );
					} else {
						$db->rollback ( $this->Activeworktime );
						$this->Session->setFlash ( __ ( 'CSV登録に失敗しました。もう一度登録しなおしてください。' ) );
					}
				} else {
					$this->Session->setFlash ( __ ( 'ファイルが存在していません。' ) );
				}
			} catch ( Exception $e ) {
				$this->Session->setFlash ( __ ( $e->getMessage () ) );
			}
		}
	}


	/**
	 * 稼働履歴一覧
	 */
	public function useractiveworkdata() {

		//csvアップロード
		if ( !empty($this->request->data["upload"]) ) {
			$this->activeworkcsvupload();
		}

		// ダウンロード
		if (! empty ( $this->request->data["download"] )) {
			$this->autoRender=false;
			$downloadData = array();
			$downloadData = $this->Activeworktime->getPointGroupingUser ($this->request->data["Activeworktime"] );
			$bodyData = $this->Activeworktime->exportCSV ( $downloadData );
			$csvFileName=$this->Activeworktime->makeActiveWorkFileName($this->request->data["Activeworktime"]);
			// アクセスした時にダウンロードさせる為のヘッダを設定します。
			header ("Content-disposition: attachment; filename=" . $csvFileName);
			header ("Content-type: application/octet-stream; name=" . $csvFileName);
			// バッファを出力して完成です。
			print($bodyData);
			exit;
		}

		//ポストにない場合入力補完
		 if( !isset( $this->request->data["Activeworktime"]["aggregate_start_date"]) &&
		     !isset( $this->request->data["Activeworktime"]["aggregate_end_date"])){
			//初期データ
			$this->request->data["Activeworktime"]["aggregate_start_date"] = date("Y-m-01");
			$this->request->data["Activeworktime"]["aggregate_end_date"] = "";
		}

		$activeWorkData = $this->Activeworktime->findActiveWorkDataByQuery ( $this->request->data["Activeworktime"] );

		$this->set ( 'query', $this->request->data["Activeworktime"] );
		$this->set ( "activeWorkData", $activeWorkData );
	}

	/**
	 * 稼働履歴からサービス内容の一覧を取得する
	 */
	public function registservice(){
		$this->autoRender = FALSE;
		//既存の稼働履歴からサービスリストの取得
		$serviceList = $this->Activeworktime->getServiceList();
		//マスターを見てなければ登録
		$this->Service->checkMasterServiceData( $serviceList );
	}

	/**
	 * サービス内容の一覧の表示
	 */
	public function serviceindex() {
		$this->set ( 'serviceList', $this->Service->getServiceList() );
	}

	/**
	 * サービス内容の追加
	 */
	public function serviceadd() {
		// POST の時だけ
		if ($this->request->is ( 'post' )) {
			if ($this->Service->save ( $this->request->data )) {
				$this->Session->setFlash ( 'サービスの登録が成功しました。','default' , array('class' => 'success') );
				$this->redirect ( array (
						'action' => 'serviceindex'
				) );
			} else {
				$this->Session->setFlash ( 'サービスの登録に失敗しました。' );
			}
		}
		$this->render ( 'serviceindex' );
	}

	/**
	 * サービス時間の修正のajax
	 */
	public function updateservice() {
		$this->autoRender = FALSE;
		if ($this->request->is ( 'ajax' )) {
			$data = $this->request->data;
			if ($this->Service->save ( $data )) {
				echo "success";
			} else {
				echo "fail";
			}
		}
	}

	/**
	 * サービスの削除
	 *
	 * @param string $id サービスID
	 * @throws NotFoundException
	 */
	public function servicedelete($id = null) {
		$this->Service->id = $id;

		if (! $this->Service->exists ()) {
			throw new NotFoundException ( __ ( 'サービスが存在しません。' ) );
		}

		$data = array (
				"id" => $id,
				"is_delete" => 1
		);

		if ($this->Service->save ( $data )) {
			$this->Session->setFlash ( 'サービスの削除が完了しました。' ,'default' , array('class' => 'success'));
			$this->redirect ( array (
					'action' => 'serviceindex'
			) );
		} else {
			$this->Sessin->setFlash ( 'サービスの削除に失敗しました' );
		}
		$this->render ( 'serviceindex' );
	}

	/**
	 * 勤務時間の修正のajax
	 */
	public function updateworkdata() {
		$this->autoRender = FALSE;
		if ($this->request->is ( 'ajax' )) {
			$data = $this->request->data;
			if ($this->Worktime->save ( $data )) {
				echo "success";
			} else {
				echo "fail";
			}
		}
	}
	public function beforeFilter() {
		AuthComponent::$sessionKey = 'Auth.admins';
		$this->Auth->allow ( 'login', 'logout');
	}

	/**
	 * ログイン時に入る管理画面のトップ
	 */
	public function index() {
	}

	/**
	 * 部屋一覧の取得
	 */
	public function roomindex() {
		$this->set ( 'rooms', $this->Room->getRoomData () );
		$this->render ( 'roomindex' );
	}

	/**
	 * 部屋追加
	 */
	public function roomadd() {

		// POST の時だけ
		if ($this->request->is ( 'post' )) {
			if ($this->Room->save ( $this->request->data )) {
				$this->Session->setFlash ( '部屋の登録が成功しました。','default' , array('class' => 'success') );
				$this->redirect ( array (
						'action' => 'roomindex'
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
				$this->Session->setFlash ( '部屋の編集が成功しました。','default' , array('class' => 'success') );
				$this->redirect ( array (
						'action' => 'roomindex'
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
	 * @param string $id
	 *        	部屋ID
	 * @throws NotFoundException
	 */
	public function roomdelete($id = null) {
		$this->Room->id = $id;

		if (! $this->Room->exists ()) {
			throw new NotFoundException ( __ ( '部屋が存在しません。' ) );
		}

		$data = array (
				"id" => $id,
				"is_delete" => 1
		);

		if ($this->Room->save ( $data )) {
			$this->Session->setFlash ( '部屋の削除が完了しました。','default' , array('class' => 'success') );
			$this->redirect ( array (
					'action' => 'roomindex'
			) );
		} else {
			$this->Sessin->setFlash ( '部屋の削除に失敗しました' );
		}
		$this->render ( 'roomindex' );
	}

	/**
	 * 部屋の予約を行う
	 */
	public function reserveroom() {
		if ($this->request->is ( 'post' )) {
			if (! empty ( $this->request->data ['User'] ['start_date_pull_down_id'] )) {
				$this->Reserve->reserveRoom ( $this->request->data );
				$this->Session->setFlash ( __ ( '部屋の予約が成功しました。' ) );
			}
			$roomId = $this->request->data ['User'] ['room_id'];
			$startPeriod = $this->request->data ['User'] ['reserve_period'];
		} else {
			$resData = $this->Room->find ( 'first', array (
					'conditions' => array (
							'Room.is_delete' => 0,
							'Room.room_name <>' => '在宅'
					)
			) );
			$roomId = $resData ['Room'] ['id'];
			$startPeriod = date('Y/m/d');
		}

		$roomIdArr = $this->Room->getRoomList ();
		$roomScheduleeArr = $this->Reserve->createAvailabelTime ( $roomIdArr, $startPeriod, true );
		$this->set ( "weekPullDownList", $this->Reserve->makeWeekPeriodPullDown());
		$this->set ( "roomList", $this->Room->getRoomList ( true ) );
		$this->set ( "roomId", $roomId );
		$this->set ( "startPeriod", $startPeriod );
		$this->set ( "weekArr", array_keys($roomScheduleeArr[$roomId]['timeline']));
		$this->set ( "masterTimelineArr", $this->Reserve->getTimeline () );
		$this->set ( "roomScheduleeArr", $roomScheduleeArr [$roomId] );
	}


	/**
	 * ログインメソッド
	 */
	public function login() {
		if ($this->Auth->loggedIn ()) {
			$this->redirect ( $this->Auth->redirect () );
		}

		if ($this->request->is ( 'post' )) {
			if ($this->Auth->login () ) {
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