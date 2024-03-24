<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'admin';
    protected $primaryKey = 'id';

    protected $fillable = ['username','password','email',
    'display_name','avatar','skin','depart_id','is_default','lastlogin','code_reset','menu_order',
    'status','phone','created_at','updated_at'
    ];
    public function department()
    {
        // return $this->hasOne(Department::class,'id','depart_id');
        return $this->belongsTo(Department::class,'depart_id','id');
    }

}
