<?php

namespace Ads\Core\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->success();
        $this->error();
    }

    /**
     * Generate success macro
     */
    private function success(): void
    {
        response()->macro('success', function (string $message = 'success', array|string $data = [], $headers = []) {
            return response()->json([
                'error' => 0,
                'message' => $message,
                'data' => $data,
            ], 200, $headers);
        });
    }

    /**
     * Generate error macro
     */
    private function error(): void
    {
        response()->macro('error', function ($error = 400, $message = 'error', $data = [], $status = 200) {
            if (is_array($data)) {
                if (!array_key_exists('error_id', $data)) {
                    $data['error_id'] = request()->get('request_id');
                }
            }

            return response()->json([
                'error' => $error,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }
}
