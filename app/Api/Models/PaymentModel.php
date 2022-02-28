<?php

namespace App\Api\Models;

use App\Api\Core\Traits\UuidTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PaymentModel extends Authenticatable
{
    use UuidTrait;

    /**
     * @var string
     */
    protected $table = 'payments';

    /**
     * @var array<string>
     */
    protected $hidden = ['id'];

    /**
     * @var array<string>
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'checkout_id',
        'payment_id',
        'amount',
        'response',
        'completed_at'
    ];

    public function checkout()
    {
        return $this->belongsTo(CheckoutModel::class, 'checkout_id');
    }

    public function refund()
    {
        return $this->hasMany(RefundModel::class, 'payment_id');
    }
}
