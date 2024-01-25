<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangmanWord extends Model
{
    use HasFactory;
    protected $table = 'hangman_words';
    protected $fillable = ['word','hint'];
}
