<?php

class Sendmail{

	public function sendGridMail(){
		$sendgrid = new SendGrid( EMAIL_USER_NAME, EMAIL_PASSWORD );
		$emaill = new SendGridEmail();
		$email ->addTo('umanari145@gmail.coom')->
		    setFrom('matsumoto@donow.jp')->
		    setSubject('Subject test')->
		    setText('Hello World!');
		$sendgrid->send($email);
	}

	public function showEcho(){
		echo "success";
	}
}