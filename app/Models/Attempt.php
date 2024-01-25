<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;
        protected $table = 'attempts';
    protected $fillable = ['match_id','word', 'guessed_letter'];
}
