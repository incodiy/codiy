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
	
	private $data;
	private $subject;
	private $title;
	private $message;
	private $from;
	private $to;
	private $cc;
	private $bcc;
	
	public function from($string) {
		$this->from = $string;
	}
	
	public function to($string) {
		$this->to = $string;
	}
	
	public function cc($string) {
		$this->cc = $string;
	}
	
	public function bcc($string) {
		$this->bcc = $string;
	}
	
	public function subject($string) {
		$this->subject = $string;
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
		
		if (!empty($this->cc)) $mail->cc($this->cc);
		if (!empty($this->bcc)) $mail->bcc($this->bcc);
		
		$mail->to($this->to)->send(new Email($this->data));
		
		dd("Email is sent successfully.");
	}
}