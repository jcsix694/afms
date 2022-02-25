<?php

namespace App\Api\Models;

use App\Api\Core\Traits\UuidTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAccountsModel extends Authenticatable
{
    use UuidTrait;

    /**
     * @var string
     */
    protected $table = 'user_accounts';

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
        'user_id',
        'account_type_id'
    ];
}
