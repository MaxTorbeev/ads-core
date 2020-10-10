<?php

namespace Ads\Core\Observers;

use Ads\Core\Models\Log;

class LogObserver
{
    public function created(Log $log)
    {
        $log->setFieldsExclusion();

        if ($log->fieldsExclusion === false) {
            return false;
        }

        $log->ip = request()->ip();
        $log->user_id = auth()->user()->id ?? null;
    }
}
