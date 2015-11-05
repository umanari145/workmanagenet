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
	public function convertSecondTohms($seconds) {
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
}
