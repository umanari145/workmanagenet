<?php
App::uses('AppShell', 'Console/Command');
App::import('Vendor', 'util/sendmail');

class ChecklogoutShell extends AppShell {

	//モデルを読み込む
	public $uses = array (
			'Admin',
			'User',
			'Worktime',
			'Room'
	);

	/**
	 * バッチのメイン処理
	 */
	public function main() {
		$this->out ( "start_batch" );
		$this->out ( date ( "Y-m-d H:i:s" ) );
		$this->testEcho();
		$this->out ( date ( "Y-m-d H:i:s" ) );
		$this->out ( "last_batch" );
	}

	/**
	 * ログアウトのデータを強制アップデート
	 */
	public function updateforgetWorkData(){
		$forgetWorkData = $this->Worktime->getForgetLogoutData ();
		if (! empty ( $forgetWorkData )) {
			$this->Worktime->updateForgetWorkLine ( $forgetWorkData );
		}
	}

	public function testEcho(){
		echo __DIR__;
	}

}