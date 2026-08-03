<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'date',
        'qty_in',
        'qty_out',
        'balance',
        'source',
        'reference_id',
    ];
}
