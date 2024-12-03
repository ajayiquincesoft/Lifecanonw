<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DbTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
   
	public $template;
    
    public function __construct($template,$sub)
    {
        $this->template = $template;
		$this->sub = $sub;
    }
    
    public function build()
    {
        return $this->from(['address' => 'ajeet.iquincesoft@gmail.com', 'name' => 'Life Canon'])->subject($this->sub)->view('emails.dynamic');
    }
}
