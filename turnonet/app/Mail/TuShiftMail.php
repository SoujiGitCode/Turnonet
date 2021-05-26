<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TuShiftMail extends Mailable
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
     * The code instance.
     *
     * @var data
     */
    protected $code;
    /**
     * The btn instance.
     *
     * @var data
     */
    protected $btn;
    /**
     * The btn_google instance.
     *
     * @var data
     */
    protected $btn_google;
    /**
     * The btn_ical instance.
     *
     * @var data
     */
    protected $btn_ical;
    /**
     * The btn_outlook instance.
     *
     * @var data
     */
    protected $btn_outlook;
    /**
     * The  instance.
     *
     * @var data
     */
    protected $btn_yahoo;
    /**
     * The reply instance.
     *
     * @var data
     */
    protected $reply;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reply,$view_file,$title,$content,$code,$btn,$btn_google,$btn_ical,$btn_outlook,$btn_yahoo)
    {
        $this->view_file = $view_file;
        $this->title = $title;
        $this->code=$code;
        $this->btn=$btn;
        $this->content = $content;
        $this->btn_google=$btn_google;
        $this->btn_ical=$btn_ical;
        $this->btn_outlook=$btn_outlook;
        $this->btn_yahoo=$btn_yahoo;
        $this->reply=$reply;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return 
$this->replyTo($this->reply)
            ->subject($this->title)
            ->view('email.'.$this->view_file)->with([
                        'title' => $this->title,
                        'code'=>$this->code,
                        'btn'=>$this->btn,
                        'btn_google'=>$this->btn_google,
                        'btn_ical'=>$this->btn_ical,
                        'btn_yahoo'=>$this->btn_yahoo,
                        'btn_outlook'=>$this->btn_outlook,
                        'content' => $this->content,
                    ]);
    }
}
