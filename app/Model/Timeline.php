<?php
class Timeline extends AppModel {

	public function getTimeline(){
		$conditions = array(
				'fields' => array(
						'id',
						'timezone'
				),
				'conditions' => array (
						'Timeline.is_delete' => 0
				)
		);
		return $this->find('list',$conditions);
	}
}