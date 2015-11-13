<?php
App::uses('AppShell', 'Console/Command');
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
		$forgetWorkData = $this->Worktime->getForgetLogoutData ();
		if (! empty ( $forgetWorkData )) {
			$this->Worktime->updateForgetWorkLine ( $forgetWorkData );
		}
		$this->out ( date ( "Y-m-d H:i:s" ) );
		$this->out ( "last_batch" );
	}

}