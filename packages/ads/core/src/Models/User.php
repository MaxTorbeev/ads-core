<?php

namespace Ads\Core\Models;

use Ads\Core\Traits\HasHierarchy;
use Ads\Core\Traits\HasPassword;
use Ads\Core\Traits\HasPermission;
use Ads\Core\Traits\HasRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRole, HasPermission, HasPassword, HasHierarchy, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'login',
        'email',
        'password',
        'parent_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'parent',
        'default_roles'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'roles',
        'permissions',
        'is_active'
    ];

    /**
     * Override is_active model attribute.
     *
     * @description Если в собственной модели и у родителей есть is_active - false,
     * то пользователь не считается активным
     *
     * @return Attribute
     */
    public function isActive(): Attribute
    {
        $is_active = !User::whereIn('id', $this->parentIds(true))->where('is_active', 0)->exists();

        return Attribute::make(
            get: fn() => $is_active
        );
    }

    /**
     * Может ли пользователю применить parent_id.
     *
     * @param $id
     * @return bool
     */
    public function canAttemptParentId($id): bool
    {
        return !self::where('id', $id)->whereNot('id', $this->id)->exists();
    }
}
