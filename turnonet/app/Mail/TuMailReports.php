<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TuMailReports extends Mailable
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
     * The data instance.
     *
     * @var data
     */
    protected $data;
    /**
     * The inputs_add instance.
     *
     * @var inputs_add
     */
    protected $inputs_add;
    /**
     * The rep_type instance.
     *
     * @var rep_type
     */
    protected $rep_type;
     /**
     * The btn_email instance.
     *
     * @var btn_email
     */
    protected $btn_email;
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
    public function __construct($reply,$view_file,$title,$content,$data,$inputs_add,$rep_type,$btn_email)
    {
        $this->view_file = $view_file;
        $this->title = $title;
        $this->content = $content;
        $this->data = $data;
        $this->inputs_add=$inputs_add;
        $this->rep_type=$rep_type;
        $this->btn_email=$btn_email;
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
            'data' => $this->data,
            'content' => $this->content,
            'inputs_add'=>$this->inputs_add,
            'rep_type'=>$this->rep_type,
            'btn_email'=>$this->btn_email,
        ]);
    }
}
