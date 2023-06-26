<?php

namespace Ads\Cache\ScheduledActions;

use Ads\Logger\Models\Log;
use Carbon\Carbon;

class LogsScheduleAction
{
    public function __invoke()
    {
        if ($this->type == 'clearOutdatedLogs') {
            $this->clearOutdatedLogs();
        }
    }

    /**
     * Удаление логов (старше ADS_LOGGER_SUCCESS_STORE_DAYS для успешных реквестов и старше ADS_LOGGER_ERROR_STORE_DAYS для ошибок)
     */
    private function clearOutdatedLogs()
    {
        do {
            $deleted = Log::query()
                ->where(function ($query) {
                    $query
                        ->where('created_at', '<', Carbon::now()->subDays(config('ads-logger.success_store_days'))->format('Y-m-d H:i:s'))
                        ->where(function ($query) {
                            $query->where('response_code', 200)->orWhereNull('response_code');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('created_at', '<', Carbon::now()->subDays(config('ads-logger.error_store_days'))->format('Y-m-d H:i:s'))
                        ->where('response_code', '!=', 200);
                })
                ->limit(5000)
                ->delete();
        } while ($deleted > 0);
    }
}
