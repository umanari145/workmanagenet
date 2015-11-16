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
		$email ->addTo(ADMIN_EMAIL_ADDRESS)->
		    setFrom(ADMIN_EMAIL_ADDRESS)->
		    setSubject($title)->
		    setText($mailMessage);
		$res=$sendgrid->send($email);
	}

}