<?php
App::uses ( 'AppHelper', 'View/Helper' );
class CustomizeHelper extends AppHelper {

	/**
	 * 文字列の指定長さだけを出力する
	 *
	 * @param string $commentコメント
	 * @param number $limitLength 指定の長さ
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
}
