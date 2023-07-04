<?php
namespace Incodiy\Codiy\Library\Components\Messages\Email;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

/**
 * Created on Jul 1, 2023
 * 
 * Time Created : 1:39:04 AM
 *
 * @filesource  Email.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 */
class Email extends Mailable {
	use Queueable, SerializesModels;
	
	public $mailData;
	public $subject = 'IncoDIY Information';
	
	public function __construct($mailData) {
		if (!empty($mailData['subject'])) {
			$this->subject = $mailData['subject'];
			unset($mailData['subject']);
		}
		
		$this->mailData = $mailData;
	}
	
	public function build() {
		$this->subject($this->subject)->view(diy_config('template') . '.emails.default');
		
		return $this;
	}
}