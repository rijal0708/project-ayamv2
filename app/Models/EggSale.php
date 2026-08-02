<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no', 'date', 'buer_id', 'payment_method', 'due_date', 'subtotal', 'discount', 
        'grand_total', 'paid_amount', 'payment_status', 'status', 'notes', 'created_by',
    ];

    public function items()
    {
        return $this->hasMany(EggSaleItem::class, 'sale_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}
