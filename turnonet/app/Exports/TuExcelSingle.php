<?php

namespace App\Exports;


use App\Users;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TuExcelSingle implements FromView
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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view_file,$data)
    {
        $this->view_file = $view_file;
        $this->data = $data;

    }
    public function view(): View
    {
        return view('exports.'.$this->view_file, [
            'data' => $this->data
        ]);
    }
}
