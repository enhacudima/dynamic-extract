<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    protected $table = 'report_new_access';
    public $primaryKey = 'id';

    protected $fillable = [
        'name', 'email','expire_at','access_link','password'
    ];

    public $timestamps=true;

    protected $hidden = [
        'password',
    ];


}
