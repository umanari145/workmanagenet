<?php

class Sendmail{

	public function sendGridMail(){
		$sendgrid = new SendGrid( EMAIL_USER_NAME, EMAIL_PASSWORD );
		$email = new SendGrid\Email();
		$email->addTo('umanari145@gmail.com')->
		setFrom('matsumoto@donow.jp')->
		setSubject('件名')->
		setText('こんにちは！');
		$sendgrid->send($email);
	}

	public function showEcho(){
		echo "success";
	}
}