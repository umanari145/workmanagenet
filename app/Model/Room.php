<?php
App::uses ( 'AppModel', 'Model' );

class Room extends AppModel {

	public $name = 'Room';
	public $primaryKey = 'id';
	public $displayField = 'room_name';


	public function getRoomList(){
		$conditions = array(
				'fields' => array(
						'id',
						'room_name'
				)
		);

		return $this->find('list',$conditions);
	}
}