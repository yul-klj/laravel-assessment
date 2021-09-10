<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// @codingStandardsIgnoreStart
class Handler extends ExceptionHandler
{
    // @codingStandardsIgnoreEnd
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Exception $e, $request) {
            return $this->handleException($request, $e);
        });
    }

    /**
     * Rendering and response towards the error catched
     *
     * @return response
     */
    public function handleException($request, Exception $exception)
    {
        $content_type = 'application/json';

        switch (true) {
            case $exception instanceof NotFoundHttpException:
                $content = json_encode([
                    'code' => Controller::CODE_NOT_FOUND,
                    'http_code' => 404,
                    'content' => [
                        'error' => 'The specified URL cannot be found.'
                    ]
                ]);
                $response = response($content, 404);
                break;
            default:
                $content = json_encode([
                    'code' => Controller::CODE_INTERNAL_ERROR,
                    'http_code' => 500,
                    'content' => [
                        'error' => 'Internal server error, kindly seek advice from system admin.'
                    ]
                ]);
                $response = response($content, 500);
        }

        $response->header('Content-Type', $content_type);
        return $response;
    }
}
