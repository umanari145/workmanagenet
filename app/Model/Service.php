<?php
App::uses ( 'AppModel', 'Model' );

class Service extends AppModel {
	public $name = 'Service';

	public function checkMasterServiceData( $serviceList = array()){

		//マスターを見てデータが存在するかいなかをチェックし、
		//なければ追加する
		foreach( $serviceList as $service){
			if( !$this->isExistService( $service )){
				$record = array(
					'service_name' => $service,
					'ratio'       => USER_REWARD_RATIO *100
				);
				$this->create ();
				$this->save($record);
			}
		}
	}

	/**
	 * 対象サービスがマスタに存在するか否かを判定
	 *
	 * @param unknown $service サービス名
	 * @return boolean true(存在する)/false(存在しない)
	 */
	private function isExistService( $service ){
		$conditions = array(
				'conditions' => array (
						'Service.service_name' => $service
				)
		);
		return $this->find('count',$conditions) > 0;
	}

	/**
	 * 対象サービスのポイントを取得
	 *
	 * @param unknown $service サービス名
	 * @return float レシオ
	 */
	public function getRatioByServiceName( $service ){

		$params = array(
				'fields'=>array(
					'ratio'
				),
				'conditions' => array (
						'Service.service_name' => $service
				)
		);
		$serviceData = $this->find('first',$params);

		$ratio ="";
		if( !empty( $serviceData )){
			$ratio = $serviceData['Service']['ratio']/100;
		}

		return $ratio;
	}

	/**
	 * サービス一覧を取得
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getServiceList(){
		$params = array(
				'fields' =>array(
					'Service.id',
					'Service.service_name',
					'Service.ratio'
				),
				'conditions' => array (
						'Service.is_delete' => 0
				)
		);
		return $this->find('all',$params);
	}

}