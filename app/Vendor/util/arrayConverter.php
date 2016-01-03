<?php
class ArrayConverter {

	public function convertPagerApi($dataList) {
		list ( $header, $rowData ) = $this->makeHeader ( $dataList );
		$recordForJson = $this->makeRecordForJson ( $header, $rowData );
		return $recordForJson;
	}

	/**
	 * ヘッダーの作成
	 *
	 * @param unknown $dataList
	 *        	ヘッダー配列
	 */
	private function makeHeader($dataList = array()) {
		$header = [ ];
		if (! empty ( $dataList )) {
			// ヘッダの取得
			$singleRecord = array_values ( $dataList [0] );
			$header = array_keys ( $singleRecord [0] );
			$rowData = $this->getRowData ( $dataList );
		}

		return array( $header, $rowData);
	}

	/**
	 * ローデータの作成
	 *
	 * @param unknown $dataList
	 *        	データリスト
	 * @return ヘッダー情報を抜いた実データだけの多次元配列
	 */
	private function getRowData($dataList) {

		// モデルがあるのでforeachで展開する
		$rowData = [ ];
		foreach ( $dataList as $data ) {
			$data2 = array_values ( $data );
			$data3 = array_values ( $data2 [0] );
			$rowData [] = $data3;
		}
		return $rowData;
	}

	/**
	 * ページャー用のレコード
	 *
	 * @param unknown $header
	 *        	ヘッダー用の配列
	 * @param unknown $rowData
	 *        	ヘッダー抜きの実データ
	 * @return JSONに渡す用の配列
	 */
	private function makeRecordForJson($header = array(), $rowData = array()) {

		$recordCount =( !empty( $rowData)) ? count ( $rowData ):0;
		$aaColumns = ( !empty( $header))? $header:array();

		$recordForJson = [
				"iTotalRecords" => $recordCount,
				"aaData" => $rowData,
				"iTotalDisplayRecords" => $recordCount,
				"aoColumns" => $header,
				"sEcho" => 1
		];

		return $recordForJson;
	}
}