<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TuMailBtn extends Mailable
{
    use Queueable, SerializesModels;

    
    /**
     * The view instance.
     *
     * @var data
     */
    protected $view_file;
    /**
     * The title instance.
     *
     * @var data
     */
    protected $title;
    /**
     * The content instance.
     *
     * @var data
     */
    protected $content;
    /**
     * The btn instance.
     *
     * @var data
     */
    protected $bnt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view_file,$title,$content,$btn)
    {
        $this->view_file = $view_file;
        $this->title = $title;
        $this->content = $content;
        $this->btn = $btn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return 

        $this->subject($this->title)
            ->view('email.'.$this->view_file)->with([
                        'title' => $this->title,
                        'content' => $this->content,
                        'btn' => $this->btn,
                    ]);
    }
}
