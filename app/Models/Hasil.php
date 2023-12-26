<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;

    protected $fillable = [
        'klub_a', 'klub_b','score_a', 'score_b','nilai_a', 'nilai_b'
    ];
}
