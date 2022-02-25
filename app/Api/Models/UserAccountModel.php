<?php

namespace App\Api\Models;

use App\Api\Core\Traits\UuidTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAccountModel extends Authenticatable
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

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(AccountTypeModel::class, 'account_type_id');
    }
}
