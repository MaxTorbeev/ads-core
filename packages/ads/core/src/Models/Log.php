<?php

namespace Ads\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Поля, значения которых будут исключены из записи в лог.
     *
     * @var array
     */
    private $fieldsExclusion = [];
}
