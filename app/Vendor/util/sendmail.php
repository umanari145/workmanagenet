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

		$ipAdress =( isset( $_SERVER['HTTP_X_FORWARDED_FOR']))? $_SERVER['HTTP_X_FORWARDED_FOR'] :"";

		if( $ipAdress !== ""){
			$mailMessage .=" \r\n "
					     ." IPアドレス ". $ipAdress;
		}
		return $mailMessage;
	}

	/**
	 * IPアドレス
	 */
	private function getIpAddress(){
		return $this->request->clientIp(false);
	}

}