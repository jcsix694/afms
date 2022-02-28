<?php

namespace App\Api\Models;

use App\Api\Core\Traits\UuidTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RefundModel extends Authenticatable
{
    use UuidTrait;

    /**
     * @var string
     */
    protected $table = 'refunds';

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
        'refund_id',
        'payment_id',
        'amount',
        'response',
        'completed_at'
    ];

    public function payment()
    {
        return $this->hasOne(PaymentModel::class, 'payment_id');
    }
}
