<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $throwable
     * @return void
     */
    public function report(Throwable $throwable)
    {
        if (in_array(app()->environment(), ['production', 'staging'])) {
            try {
                $this->fejlvarp_exception_handler($throwable);
            } catch (\Exception $exception) {
                logger()->error($exception->getMessage());
            }
        }
        parent::report($throwable);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return Response
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * @param \Throwable $exception
     * @throws \JsonException
     */
    private function fejlvarp_exception_handler(\Throwable $exception) : void
    {
        // Generate unique hash from message + file + line number
        // We strip out revision-part of the file name.
        // Assuming a standard capistrano deployment path, this will prevent duplicates across deploys.
        $hash = 'Nabovaern' . $exception->getMessage() . preg_replace('~revisions/[0-9]{14}/~', '--', $exception->getFile()) . $exception->getLine();
        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query(
                    array(
                        'hash' => md5($hash),
                        'subject' => $exception->getMessage(),
                        'data' => json_encode(array(
                            'application' => 'WooInvoice-Test',
                            'error' => array(
                                'type' => get_class($exception),
                                'message' => $exception->getMessage(),
                                'code' => $exception->getCode(),
                                'file' => $exception->getFile(),
                                'line' => $exception->getLine(),
                                'trace' => $exception->getTraceAsString()
                            ),
                            'environment' => array(
                                'GET' => $_GET ?: null,
                                'POST' => $_POST ?: null,
                                'SERVER' => $_SERVER ?: null,
                                'SESSION' => $_SESSION ?? null
                            )
                        ), JSON_THROW_ON_ERROR)
                    )))
        );
        $context = stream_context_create($opts);
        $content = file_get_contents('http://fejlvarp.wooinvoice.dk', false, $context);
    }
}
