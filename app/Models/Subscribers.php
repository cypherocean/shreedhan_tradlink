<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'number', 'carrier', 'status'];

    public static function getTotalSubscribers(){
        return self::where('status','active')->count();
    }
}
