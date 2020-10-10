<?php

namespace Ads\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;

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
     * @var array|boolean
     */
    private $fieldsExclusion = [];

    // todo написать phpDock
    public function setRequestAttribute($data)
    {
        $this->attributes['request'] = json_encode($this->logFieldsExclusion($data, 'request'));
    }

    // todo написать phpDock
    public function setResponseAttribute($data)
    {
        $data = $this->logFieldsExclusion($data, 'response');
        $data = strlen(json_encode($data)) / 1024 < 500 ? $data : 'LONG_DATA';

        $this->attributes['response'] = json_encode($data);
    }

    /**
     * Исключение полей из лога
     *
     * @param array $data
     * @param string $type - response/request
     * @return array
     */
    private function logFieldsExclusion(array $data, string $type = 'response'): array
    {
        $this->setFieldsExclusion();

        if (is_null($this->fieldsExclusion) || !isset($this->fieldsExclusion[$type])) {
            return $data;
        } else if ($data instanceof \stdClass) {
            $data = (array)unserialize(serialize($data));
        }

        if (isset($data['data']) && $type === 'response') {
            $data['data'] = $this->eraseFieldsWithEllipsis($this->fieldsExclusion[$type], $data['data']);

            return $data;
        }

        return (array)$this->eraseFieldsWithEllipsis($this->fieldsExclusion[$type], $data);
    }

    /**
     * Затереть значения полей троеточием.
     *
     * todo свойство $fields может иметь разные типы, привести только к массиву
     * @param string|array $fields
     * @param array $data
     * @return array
     */
    private function eraseFieldsWithEllipsis($fields, array $data)
    {
        if (!is_array($fields)) return $data;

        foreach ($fields as $field) {
            if (data_get($data, $field)) {
                data_set($data, $field, '...');
            }
        }

        return $data;
    }

    // todo написать phpDock
    public function setFieldsExclusion(): void
    {
        $routeName = Route::currentRouteName();

        $this->fieldsExclusion = config('core.logging')['fields_exclusion'][$routeName]
            ?? config('logging')['fields_exclusion'][$this->URI]
            ?? null;
    }

    // todo написать phpDock
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
