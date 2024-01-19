<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'players';

    public $timestamps = false;
   // use HasFactory;
    protected $fillable = ['name','id'];
}

