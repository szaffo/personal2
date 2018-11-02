<?php
	
	/**
	 * This is a simple library for sending emails with Postmark created by Matthew Loberg (http://mloberg.com)
	 */
	
	class Postmark{
	
		private $api_key;
		private $data = array();
		
		function __construct($apikey,$from,$reply=""){
			$this->api_key = $apikey;
			$this->data["From"] = $from;
			$this->data["ReplyTo"] = $reply;
		}
		
		function send(){
			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json",
				"X-Postmark-Server-Token: {$this->api_key}"
			);
			$data = $this->data;
			$ch = curl_init('http://api.postmarkapp.com/email');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$return = curl_exec($ch);
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			// do some checking to make sure it sent
			if($http_code !== 200){
				return false;
			}else{
				return true;
			}
		}
		
		function to($to){
			$this->data["To"] = $to;
			return $this;
		}
		
		function subject($subject){
			$this->data["subject"] = $subject;
			return $this;
		}
		
		function html_message($body){
			$this->data["HtmlBody"] = "{$body}";
			return $this;
		}
		
		function plain_message($msg){
			$this->data["TextBody"] = $msg;
			return $this;
		}
		
		function tag($tag){
			$this->data["Tag"] = $tag;
			return $this;
		}
	
	}

	$name = $_POST['name'];
	$email = $_POST['email'];
	$text = $_POST['text'];

	$postmark = new Postmark("76931b34-6fc7-4dce-9bbd-bdc06474d1ed","Weblapról<szabomartin@tob.hu>","$name<$email>");

	if ($postmark->to('martin77szabo@gmail.com')->subject($name)->plain_message($text)->send()) {
		header("Location: sent.html#c");
	} else {
		header("Location: error.html#c");
	}

?>