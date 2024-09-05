<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'course_id',
        'transaction_id', // ID from the payment gateway
        'payment_method', // e.g., 'credit_card', 'paypal'
        'amount',
        'status', // e.g., 'completed', 'pending', 'refunded'
        'purchase_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
