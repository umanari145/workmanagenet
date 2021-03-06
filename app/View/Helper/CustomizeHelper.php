<?php
App::uses ( 'AppHelper', 'View/Helper' );
class CustomizeHelper extends AppHelper {

	/**
	 * 文字列の指定長さだけを出力する
	 *
	 * @param string $commentコメント
	 * @param number $limitLength
	 *        	指定の長さ
	 * @return string 表示文字列
	 */
	public function viewComment($comment = "", $limitLength = 0) {
		$commentLength = mb_strlen ( $comment );

		if ($limitLength < $commentLength) {
			return mb_substr ( $comment, 0, $limitLength ) . "..";
		} else {
			return $comment;
		}
	}

	/**
	 * 秒数を時分秒に変換
	 *
	 * @param $seconds 秒
	 * @return 時分秒
	 */
	public function convertSecondTohms( $seconds) {
		$hours = floor ( $seconds / 3600 );
		$minutes = floor ( ($seconds- $hours*3600) / 60);
		$lastSeconds = $seconds - ($hours * 60 * 60 + $minutes * 60);
		if ($seconds >= 3600) {
			$hms = sprintf ( "%d時間%d分%d秒", $hours, $minutes, $lastSeconds );
		} elseif ($seconds >= 60) {
			$hms = sprintf ( "%d分%d秒", $minutes, $lastSeconds );
		} else {
			$hms = $lastSeconds . "秒";
		}
		return $hms;
	}

	/**
	 * ユーザーの勤務状態を表示
	 *
	 * @param unknown $workstate 勤務状態
	 * @param 稼働時間
	 * @return string 表示文字
	 */
	public function viewState($workstate,$seconds){
		$viewState = "";
		switch ($workstate) {
			case "1" :
				$viewState = "作業中";
				break;
			case "2" :
				$viewState = $this->convertSecondTohms($seconds);
				break;

			case "3" :
				$viewState = "異常終了";
				break;
		}
		return $viewState;
	}

	/**
	 * 曜日の表示
	 *
	 * @param unknown $viewDate yyyy/MM/dd型式の日付
	 * @return unknown MM/dd 曜日(色つき)
	 */
	public function showDataWithWeek($viewDate) {
		$viewDate2 = date ( 'm/d ', strtotime ( $viewDate ) );
		$weekInt = date ( "N", strtotime ( $viewDate ) );
		$viewWeek = "";
		$cssColor = "";
		switch ($weekInt) {
			case '1' :
				$viewWeek = "月";
				break;
			case '2' :
				$viewWeek = "火";
				break;
			case '3' :
				$viewWeek = "水";
				break;
			case '4' :
				$viewWeek = "木";
				break;
			case '5' :
				$viewWeek = "金";
				break;
			case '6' :
				$viewWeek = "土";
				$cssColor = "#0404B4";
				break;
			case '7' :
				$viewWeek = "日";
				$cssColor = "#FF0040";
				break;
			default :
				break;
		}

		if (! empty ( $cssColor )) {
			$viewHtml = "<span style='color:" . $cssColor . "';>" . $viewDate2 . $viewWeek . "</span>";
		} else {
			$viewHtml = "<span>" . $viewDate2 . $viewWeek . "</span>";
		}

		return $viewHtml;
	}
}
