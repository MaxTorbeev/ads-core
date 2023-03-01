<?php

namespace Ads\Logger\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    /**
     * Массив полей недоступных для заполнения
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $hidden = ['request', 'response'];

    protected $casts = [
        'request' => 'array',
        'response' => 'array'
    ];

    /**
     * User relationship.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
