<?php
App::uses ( 'AppModel', 'Model' );

class Activeworktime extends AppModel {
	public $name = 'Activeworktime';

	var $actsAs = array(
			'CsvImport' => array(
					'delimiter'  => ',',
			)//,
		//	'CsvExport'
	);

	public $belongsTo = array (
			'User'
	);

	/**
	 * 保存前にcharacter_idをuser_idと紐づけ
	 *
	 * (non-PHPdoc)
	 * @see Model::beforeSave()
	 */
	public function beforeSave($option = array()) {

		if( empty ( $this->data ["Activeworktime"] ["character_id"] ) ){
			throw new NotFoundException('character_idが存在しないデータが含まれています。');
		}


		if (! empty ( $this->data ["Activeworktime"] ["character_id"] ) && empty ( $this->data ["Activeworktime"] ["user_id"] )) {

			$characterId = $this->data ["Activeworktime"] ["character_id"];

			App::import ( 'Model', 'User' );
			$UserModel = new User ();
			$userData = $UserModel->find ( 'first', array (
					'conditions' => array (
							'User.character_id' => $characterId
					)
			) );

			if( empty( $userData )){
				throw new NotFoundException('存在しないユーザーが含まれています。');
			}
			$this->data["Activeworktime"]["reward"] = ceil($this->data ["Activeworktime"]["point"]*USER_REWARD_RATIO);
			$this->data["Activeworktime"]["user_id"] = $userData["User"]["id"];
		}
		return true;
	}
}