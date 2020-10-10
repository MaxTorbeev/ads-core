<?php

namespace Ads\Core\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    private $response;

    public function boot(ResponseFactory $responseFactory): void
    {
        $this->response = $responseFactory;

        $this->success();
        $this->error();
    }

    /**
     * Generate success macro
     */
    private function success(): void
    {
        $this->response->macro('success', function (string $message = 'success', array $data = [], $headers = []) {
            return $this->response->json([
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
        $this->response->macro('error', function ($error = 400, $message = 'error', $data = [], $status = 200) {
            if (is_array($data)) {
                if (!array_key_exists('error_id', $data)) {
                    $data['error_id'] = request()->get('request_id');
                }
            }

            return $this->response->json([
                'error' => $error,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }
}
