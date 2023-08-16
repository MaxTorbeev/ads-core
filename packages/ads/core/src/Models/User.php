<?php

namespace Ads\Core\Models;

use Ads\Core\Traits\HasPassword;
use Ads\Core\Traits\HasPermission;
use Ads\Core\Traits\HasRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRole, HasPermission, HasPassword, HasApiTokens;


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
        'parent'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'roles'
    ];

    /**
     * Parent record relationship
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenIds(?User $user = null, bool $hierarchical = true): Collection
    {
        $ids = collect();

        $userId = is_null($user)
            ? $this->id
            : $user;

        $query = User::select('id')->where('parent_id', $userId)->get();

        foreach ($query as $child) {
            $ids->push($child->id);

            if ($hierarchical) {
                $ids = $ids->merge($this->childrenIds($child, $hierarchical));
            }
        }

        return $ids;
    }
}
