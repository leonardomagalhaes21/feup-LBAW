<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['question', 'answer'];

    protected $table = 'frequently_asked_questions';
}
