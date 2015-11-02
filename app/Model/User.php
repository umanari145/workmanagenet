<?php
App::uses ( 'AppModel', 'Model' );
class User extends AppModel {
	public $name = 'User';

	/**
	 * ユーザー登録をするとき
	 * にパスワードを暗号化して記録する
	 *
	 * @see Model::beforeSave()
	 */
	public function beforeSave($option = array()) {

		//空白の場合は削除
		if( $this->data [$this->alias] ['password'] === "" ){
			unset( $this->data [$this->alias] ['password']);
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
				)
		);
		return $this->find ( 'all', $conditions );
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
						'username',
						'japanese_name',
						'email'
				),
				'conditions' => array('User.id' => $id)
		);
		return $this->find ( 'first', $conditions );
	}
}