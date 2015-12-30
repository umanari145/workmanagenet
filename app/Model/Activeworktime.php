<?php
App::uses ( 'AppModel', 'Model' );

class Activeworktime extends AppModel {
	public $name = 'Activeworktime';

	var $actsAs = array(
			'CsvImport' => array(
					'delimiter'  => ',',
			),
			'CsvExport'
	);

	public $belongsTo = array (
			'User'
	);

	/**
	 * データのバリデーション
	 * (形式的なチェックができないのでcakeの機能でなく、独自実装)
	 */
	public function checkRequiredData(&$data = array()) {
		$errorMessage = "";
		if (empty ( $data ["Activeworktime"] ["chatgirl_id"] )) {
			$errorMessage = 'chatgirl_idが存在していません。';
		} else {

			$chatGirlId = $data ["Activeworktime"] ["chatgirl_id"];

			App::import ( 'Model', 'User' );
			$UserModel = new User ();
			$userData = $UserModel->find ( 'first', array (
					'conditions' => array (
							'User.chatgirl_id' => $chatGirlId
					)
			) );

			if (empty ( $userData )) {
				$errorMessage = '存在しないchatgirl_idが含まれています。';
				return $errorMessage;
			}

			$res = $this->calcRewardRatio ( $data ["Activeworktime"] ["point"], $data ["Activeworktime"] ["service_name"] );
			$reward = "";
			if ($res !== false) {
				$reward = $res;
			} else {
				$errorMessage = '存在しないサービスが含まれています。';
				return $errorMessage;
			}

			$data ["Activeworktime"] ["reward"] = $reward;
			$data ["Activeworktime"] ["user_id"] = $userData ["User"] ["id"];
		}
		return $errorMessage;
	}

	/**
	 * サービス比率と報酬額の計算
	 *
	 * @param unknown $point ポイント
	 * @param unknown $service_name サービス名
	 * @return number 報酬額(存在しない場合false)
	 */
	private function calcRewardRatio( $point, $service_name ){
		App::import ( 'Model', 'Service' );
		$ServiceModel = new Service();
		$ratio = $ServiceModel->getRatioByServiceName( $service_name);
		if( empty( $ratio ) ){
			return FALSE;
		}
		//有効数字のバグをstringにキャストすることで回避
		$reward = ceil((string)($point * $ratio));
		return $reward;
	}

	/**
	 * character_id,begin,endが同一のレコードか否かを判定
	 *
	 * @param unknown $data
	 */
	public function isSameData( $data = array()){
		$params = array (
				'conditions' => array (
						'User.chatgirl_id'         => $data['Activeworktime']['chatgirl_id'],
						'Activeworktime.is_delete' => 0,
						'Activeworktime.begin'     => $data['Activeworktime']['begin'],
						'Activeworktime.end'       => $data['Activeworktime']['end']
				)
		);
		$count = $this->find ( 'count', $params );
		return $count > 0;
	}

	/**
	 * CSVアップロードに関して不正なデータのコンバート
	 *
	 * @param unknown $data CSVアップロードのデータ
	 */
	public function convertIlleagalCsvUploadData( &$data = array()){
		$data["Activeworktime"]["handlename"]=str_replace('―','-', $data["Activeworktime"]["handlename"]);
	}

	/**
	 * ユーザーごとの報酬とアカウントデータを取得
	 *
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getPointGroupingUser($targetIdArr) {

		$params = array (
				'fields' => array (
						'User.japanese_name',
						'User.transfer_account',
						'user_id',
						'SUM(point) as sum_point',
						'SUM(reward) as sum_reward'
				),
				'conditions' => array (
						'Activeworktime.id' => $targetIdArr
				),
				'group' => array (
						'Activeworktime.user_id'
				)
		)
		;

		$activeWorkDataList = $this->find ( 'all', $params );
		$this->convertForDownloadCsv($activeWorkDataList);
		return $activeWorkDataList;
	}

	/**
	 * 今月の月の稼働時間、報酬、回数などを取得する
	 *
	 * @param unknown $userId
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getMonthlyReward($userId, $targetMonthVal) {
		$targetMonthVal2=$targetMonthVal."/01";

		$startTime = date ( "Y-m-01 00:00:00", strtotime ( $targetMonthVal2 ) );
		$endTime = date ( "Y-m-t 23:59:59", strtotime ( $targetMonthVal2 ) );

		$params = array (
				'fields' => array (
						'User.japanese_name',
						'Activeworktime.user_id',
						'MAX(Activeworktime.end) as last_active_time',
						'COUNT(Activeworktime.id) as active_count',
						'SUM(UNIX_TIMESTAMP(Activeworktime.end)-UNIX_TIMESTAMP(Activeworktime.begin)) as active_time',
						'SUM(Activeworktime.point) as sum_point',
						'SUM(Activeworktime.reward) as sum_reward'
				),
				'conditions' => array (
						'Activeworktime.is_delete' => 0,
						'Activeworktime.user_id' => $userId,
						'Activeworktime.begin >= ' => $startTime,
						'Activeworktime.end <= ' => $endTime
				)
		);

		$activeWorktimeRecord = $this->find ( 'all', $params );
		return $activeWorktimeRecord;
	}

	/**
	 * 稼働履歴からサービス内容の一覧を取得する
	 */
	public function getServiceList(){

		$params = array (
				'fields' => array (
						'Activeworktime.service_name',
				),
				'conditions' => array (
						'Activeworktime.is_delete' => 0,
				),
				'group' => array(
						'Activeworktime.service_name'
				)
		);
		$serviceList0 = $this->find ( 'list', $params );
		$serviceList = array();
		if( !empty( $serviceList0)){
			$serviceList = array_values($serviceList0);
		}
		return $serviceList;
	}

	/**
	 * csvダウンロード用にデータを加工
	 *
	 * @param unknown $activeWorkDataList
	 * @return unknown
	 */
	private function convertForDownloadCsv( &$activeWorkDataList = array()){

		foreach($activeWorkDataList as &$activeWorkData ){
			$activeWorkData["Activeworktime"]{"name"} = $activeWorkData["User"]{"japanese_name"};
			$activeWorkData["Activeworktime"]{"transfer_account"} = $activeWorkData["User"]{"transfer_account"};
			$activeWorkData["Activeworktime"]{"sum_point"} = $activeWorkData[0]{"sum_point"};
			$activeWorkData["Activeworktime"]{"sum_reward"} = $activeWorkData[0]{"sum_reward"};
		}
		return $activeWorkDataList;
	}

	/**
	 * 開始期間と終了期間でレコードを出力する
	 *
	 * @param unknown $query
	 * @return Ambigous <multitype:, NULL>
	 */
	public function findActiveWorkDataByQuery($query) {


		$conditions = $this->makeConditions($query);

		$activeWorkDataList = $this->find ( 'all', array (
				'conditions' => $conditions,
				'order'=>array(
						'Activeworktime.begin DESC'
				)
		) );
		return $activeWorkDataList;
	}

	/**
	 * 検索条件の作成
	 *
	 * @param unknown $query 検索条件の作成
	 * @return 検索条件
	 */
	private function makeConditions( $query ){

		list( $aggregate_start_date,$aggregate_end_date) = $this->makeStartEndDateArr($query);

		$userId = ( isset( $query["user_id"]))? $query["user_id"]:null;

		$accountStatus = ( isset( $query["account_statues"]))? $query["account_statues"]:null;

		$conditions = array (
				'Activeworktime.is_delete' => 0,
				'Activeworktime.begin >= ' => $aggregate_start_date,
				'Activeworktime.end <= ' => $aggregate_end_date
		);

		//全員の場合(0)は検索条件がないとの一緒
		if( $userId !== NULL && $userId !== '0' ){
			$conditions['Activeworktime.user_id'] = $userId;
		}

		//全ての場合(2)は検索条件がないとの一緒
		if( $accountStatus !== NULL && $accountStatus !== '2'){
			$conditions['Activeworktime.account_statues'] = $accountStatus;
		}

		return $conditions;
	}

	/**
	 * 集計開始と集計終了日の配列を作成する
	 *
	 * @param $query 検索クエリ
	 * @param $startFormat 開始時刻フォーマット
	 * @param $endFormat 終了時刻フォーマット
	 */
	private function makeStartEndDateArr($query, $startFormat = "", $endFormat = "") {
		if (empty ( $query ["aggregate_start_date"] )) {
			$query ["aggregate_start_date"] = " 2015-11-01";
		}

		if (empty ( $query ["aggregate_end_date"] )) {
			$query ["aggregate_end_date"] = date ( 'Y-m-d' );
		}

		if (empty ( $startFormat )) {
			$startFormat = 'Y-m-d 00:00:00';
		}

		if (empty ( $endFormat )) {
			$endFormat = 'Y-m-d 23:59:59';
		}

		$aggregate_start_date = date ( $startFormat, strtotime ( $query ["aggregate_start_date"] ) );
		$aggregate_end_date = date ( $endFormat, strtotime ( $query ["aggregate_end_date"] ) );

		return array (
				$aggregate_start_date,
				$aggregate_end_date
		);
	}

	/**
	 * ファイル名の作成
	 *
	 * @param unknown $query 検索クエリ
	 * @return string ファイル名
	 */
	public function makeActiveWorkFileName($query){
		list( $aggregate_start_date,$aggregate_end_date) = $this->makeStartEndDateArr($query,'Ymd' ,'Ymd');

		$fileName="スタッフ別稼働履歴データ_"	.$aggregate_start_date ."_".$aggregate_end_date.".csv";

		return $fileName;
	}

	/**
	 * 対象の稼働履歴idに対して支払い済みにする
	 *
	 * @param unknown $activeworkLineIdArr
	 *        	支払い済み
	 */
	public function savePaymentStatus($activeworkLineIdArr = array()) {
		$conditions = array (
				'Activeworktime.id' => $activeworkLineIdArr
		);
		$updatefield = array (
				'Activeworktime.account_statues' => 1
		);
		$this->updateAll ( $updatefield, $conditions );
	}


	/**
	 * 対象月の配列の作成
	 *
	 * @return multitype:string
	 */
	public function makeTargetRewardArray() {
		$nowDateVal = date ( "Y/m" );
		$nowDateLabel = date ( "Y年m月" );
		$targetDateArr=[];
		$targetDateArr[$nowDateVal]=$nowDateLabel;

		for($i = 1; $i < 12; $i ++) {
			$dateVal = date ( "Y/m", strtotime ( "-" . $i . "month" ) );
			$dateLabel = date ( "Y年m月", strtotime ( "-" . $i . "month" ) );
			$targetDateArr [$dateVal]=$dateLabel;
		}
		return $targetDateArr;
	}
}