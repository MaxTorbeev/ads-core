<?php

namespace Ads\Core\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response;
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
        Response::macro('success', function ($data = [], $message = 'success', $headers = []) {
            return Response::json([
                'error'  => 0,
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
        Response::macro('error', function ($error = 400, $data = [], $message = 'error', $status = 500) {
            return Response::json([
                'error'  => $error,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }
}
