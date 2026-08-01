<?php

namespace App\Models;

use Illuminate\Database\Eloquent\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggPriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
	'buyer_id', 
	'grade', 
	'unit', 
	'price',
	'effective_date',
    ];
}
