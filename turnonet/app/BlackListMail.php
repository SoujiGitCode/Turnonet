<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlackListMail extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'email_blacklist';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','email'];

}
