<?php

class Sendmail{

	/**
	 * メール送信プログラム(heroku用:sendgrid)
	 *
	 * @param unknown $title タイトル
	 * @param unknown $mailMessage メールのメッセージ
	 */
	public function sendGridMail($title,$mailMessage){
		$sendgrid = new SendGrid(getenv('SENDGRID_USERNAME'), getenv('SENDGRID_PASSWORD'));
		$email = new SendGrid\Email();
		$mailMessage = $this->addMailFooter($mailMessage);
		$email ->addTo(ADMIN_EMAIL_ADDRESS)->
		    setFrom(ADMIN_EMAIL_ADDRESS)->
		    setSubject($title)->
		    setText($mailMessage);
		$res=$sendgrid->send($email);
	}

	/**
	 * 付加情報の追加をする
	 *
	 * @param unknown $mailMessage
	 */
	private function addMailFooter( $mailMessage){

		$ipAdress = ( !empty( $_SERVER["REMOTE_ADDR"]))? $_SERVER["REMOTE_ADDR"]:"";

		if( $ipAdress !== ""){
			$mailMessage .=" \r\n "
					     ." IPアドレス ". $_SERVER["REMOTE_ADDR"];
		}
		return $mailMessage;
	}

}