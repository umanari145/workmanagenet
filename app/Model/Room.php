<?php
App::uses ( 'AppModel', 'Model' );

class Room extends AppModel {

	public $name = 'Room';
	public $primaryKey = 'id';
	public $displayField = 'room_name';

	/**
	 * 部屋番号=>部屋名のリストの取得
	 *
	 * @return 部屋番号=>部屋名のリスト
	 */
	public function getRoomList(){
		$conditions = array(
				'fields' => array(
						'id',
						'room_name'
				),
				'conditions' => array (
						'Room.is_delete' => 0
				)
		);
		return $this->find('list',$conditions);
	}

	/**
	 * 部屋データの取得
	 * @return 部屋データの配列
	 */
	public function getRoomData(){
		$conditions = array(
				'fields' => array(
						'id',
						'room_name'
				),
				'conditions' => array (
						'Room.is_delete' => 0
				)
		);
		return $this->find('all',$conditions);
	}
}