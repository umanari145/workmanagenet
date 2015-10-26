<?php

App::uses ( 'AppModel', 'Model' );

class Admin extends AppModel {

	public $name = 'Admin';


	/**
	 * 管理者を登録をするとき
	 * にパスワードを暗号化して記録する
	 * @see Model::beforeSave()
	 */
	public function beforeSave( $option = array()) {
		if (isset ( $this->data [$this->alias] ['password'] )) {
			$this->data [$this->alias] ['password'] = AuthComponent::password ( $this->data [$this->alias] ['password'] );
		}
		return true;
	}
}