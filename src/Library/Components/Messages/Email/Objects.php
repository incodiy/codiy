<?php
namespace Incodiy\Codiy\Library\Components\Messages\Email;

use Illuminate\Support\Facades\Mail;

/**
 * Created on Jul 1, 2023
 * 
 * Time Created : 9:27:38 PM
 *
 * @filesource  Objects.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 */
class Objects {
	
	private $mail;
	private $data;
	private $title;
	private $message;
	private $from;
	private $to;
	private $cc;
	private $bcc;
	
	public function from($address) {
		$this->from = $address;
	}
	
	public function to($address) {
		$this->to = $address;
	}
	
	public function cc($address) {
		if (!is_array($address)) $address = [$address];
		
		$this->cc = $address;
	}
	
	public function bcc($address) {
		if (!is_array($address)) $address = [$address];
		
		$this->bcc = $address;
	}
	
	public function subject($string) {
		$this->data['subject'] = $string;
	}
	
	public function title($string) {
		$this->title              = $string;
		$this->data[__FUNCTION__] = $string;
	}
	
	public function message($string) {
		$this->message      = $string;
		$this->data['body'] = $string;
	}
	
	public function send($mailData = []) {
		if (!empty($mailData)) $this->data = $mailData;
		
		$mail = new Mail();
		$this->mail = $mail::to($this->to);
		
		
		if (!empty($this->cc)) $this->mail->cc($this->cc);
		if (!empty($this->bcc)) $this->mail->bcc($this->bcc);
		dump($this);
		$this->mail->send(new Email($this->data));
		 
		dd("Email is sent successfully.", $mail);
	}
}