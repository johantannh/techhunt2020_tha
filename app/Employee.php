<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'login', 'name', 'salary'
    ];
    
    protected $hidden = ["created_at", "updated_at"];
    
    protected $casts = [
        "salary" => "float"
    ];
}
