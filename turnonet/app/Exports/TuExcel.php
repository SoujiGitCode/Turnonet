<?php

namespace App\Exports;


use App\Users;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TuExcel implements FromView
{
    
    /**
     * The view instance.
     *
     * @var data
     */
    protected $view_file;
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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view_file,$data,$inputs_add)
    {
        $this->view_file = $view_file;
        $this->data = $data;
        $this->inputs_add=$inputs_add;
    }
    public function view(): View
    {
        return view('exports.'.$this->view_file, [
            'data' => $this->data,
            'inputs_add'=>$this->inputs_add
        ]);
    }
}
