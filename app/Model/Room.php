<?php
App::uses ( 'AppModel', 'Model' );
App::import('Vendor', 'util/arrayConverter');

class Room extends AppModel {

	public $name = 'Room';
	public $primaryKey = 'id';
	public $displayField = 'room_name';

	/**
	 * 部屋番号=>部屋名のリストの取得
	 *
	 * @param $notIncludeHome 在宅を含めない
	 * @return 部屋番号=>部屋名のリスト
	 */
	public function getRoomList($notIncludeHome = false ){
		$conditions = array(
				'fields' => array(
						'id',
						'room_name'
				),
				'conditions' => array (
						'Room.is_delete' => 0
				)
		);

		if($notIncludeHome){
			$conditions['conditions']['Room.room_name <> '] ='在宅';
		}

		return $this->find('list',$conditions);
	}

	/**
	 * 部屋データの取得
	 *
	 * @param $isApi apiモード
	 * @return 部屋データの配列
	 */
	public function getRoomData($isApi = false) {
		$conditions = array (
				'fields' => array (
						'id',
						'room_name'
				),
				'conditions' => array (
						'Room.is_delete' => 0
				)
		)
		// 'offset'=>0,
		// 'limit'=>100
		;

		$rooms = $this->find ( 'all', $conditions );

		foreach ( $rooms as &$room ) {
			$room['Room'] ['check_box'] = "<input type='checkbox' class='del_box' name='room_" . $room ['Room'] ['id'] . "' >";
		}

		if ($isApi) {
			$arrayConverter = new ArrayConverter ();
			$recorForJson = $arrayConverter->convertPagerApi ( $rooms );
			return $recorForJson;
		}

		return $rooms;
	}
}