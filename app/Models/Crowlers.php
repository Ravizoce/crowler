<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PharIo\Manifest\Author;

class Crowlers extends Model
{
    use HasFactory;
    protected $table = 'crowlers';
    protected $fillable=[
        'name',
        'url',
        'user_id',
        'author',
        'start',
        'end',
        'diff',
    ];
}
