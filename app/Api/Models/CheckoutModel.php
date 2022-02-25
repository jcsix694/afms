<?php

namespace App\Api\Models;

use App\Api\Core\Traits\UuidTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CheckoutModel extends Authenticatable
{
    use UuidTrait;

    /**
     * MODEL CONSTANTS
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    /**
     * @var string
     */
    protected $table = 'checkouts';

    /**
     * @var array<string>
     */
    protected $hidden = [
        'id',
    ];

    /**
     * @var array<string>
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'completed_at',
        'refunded_at',
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'reference',
        'status',
        'refunded',
        'checkout_id',
        'response',
        'completed_at',
        'refunded_at'
    ];

    /**
     * Array of statuses
     *
     * @var array<string>
     */
    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_PARTIALLY_REFUNDED,
        self::STATUS_REFUNDED
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
