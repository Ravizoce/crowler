<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Function_;

class Url extends Model
{
    // protected $table='urls';
    protected $fillable=[
        'urls',
        'status',
        'crowlers_id'
    ];
    use HasFactory;
    public function Crowlers (){
        return $this->belongsTo(Crowlers::class,'crowlers_id' );
    }
}
