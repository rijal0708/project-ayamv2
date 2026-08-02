<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id', 'date', 'amount', 'method', 'reference_no', 'notes',
    ];

    public function sale()
    {
        return $this->belongsTo(EggSale::class, 'sale_id');
    }
}
