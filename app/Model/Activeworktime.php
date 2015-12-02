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
	 * 保存前にcharacter_idをuser_idと紐づけ
	 *
	 * (non-PHPdoc)
	 * @see Model::beforeSave()
	 */
	public function beforeSave($option = array()) {

		if( empty ( $this->data ["Activeworktime"] ["chatgirl_id"] ) ){
			throw new NotFoundException('character_idが存在しないデータが含まれています。');
		}


		if (! empty ( $this->data ["Activeworktime"] ["chatgirl_id"] ) && empty ( $this->data ["Activeworktime"] ["user_id"] )) {

			$chatGirlId = $this->data ["Activeworktime"] ["chatgirl_id"];

			App::import ( 'Model', 'User' );
			$UserModel = new User ();
			$userData = $UserModel->find ( 'first', array (
					'conditions' => array (
							'User.chatgirl_id' => $chatGirlId
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

	/**
	 * ユーザーごとの報酬とアカウントデータを取得
	 *
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getPointGroupingUser($query) {

		list( $aggregate_start_date,$aggregate_end_date) = $this->makeStartEndDateArr($query);

		$params = array (
				'fields' => array (
						'User.japanese_name',
						'User.transfer_account',
						'user_id',
						'SUM(point) as sum_point',
						'SUM(reward) as sum_reward'
				),
				'conditions' => array (
						'Activeworktime.is_delete' => 0,
						'Activeworktime.begin >= ' => $aggregate_start_date,
						'Activeworktime.end <= ' => $aggregate_end_date
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

		list( $aggregate_start_date,$aggregate_end_date) = $this->makeStartEndDateArr($query);

		$activeWorkDataList = $this->find ( 'all', array (
				'conditions' => array (
						'Activeworktime.is_delete' => 0,
						'Activeworktime.begin >= ' => $aggregate_start_date,
						'Activeworktime.end <= ' => $aggregate_end_date
				)
		) );
		return $activeWorkDataList;
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