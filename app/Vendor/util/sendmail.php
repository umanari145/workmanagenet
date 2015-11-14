<?php

class Sendmail{

	public function sendGridMail(){
		$sendgrid = new SendGrid(getenv('SENDGRID_USERNAME'), getenv('SENDGRID_PASSWORD'));
		$email = new SendGrid\Email();
		$email ->addTo('umanari145@gmail.coom')->
		    setFrom('matsumoto@donow.jp')->
		    setSubject('Subject test')->
		    setText('Hello World!');
		var_dump($email);
		$res=$sendgrid->send($email);
		var_dump($res);
	}

	public function showEcho(){
		echo "success";
	}
}