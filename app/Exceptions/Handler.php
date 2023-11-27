<?php

namespace App\Exceptions;

use App\Librerias\Helpers\LibRoute;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use League\OAuth2\Server\Exception\OAuthServerException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        FancyException::class,
        OAuthServerException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        switch (get_class($exception)) {
            case OAuthServerException::class:
                $type = $exception->getErrorType();

                switch ($type) {
                    case 'invalid_credentials':
                        $message = __('users.MessageInvalidCredentials');
                        break;
                    case 'invalid_client':
                        dd($exception);
                        $message = __('users.MessageInvalidClient');
                        break;
                    case 'unsupported_grant_type':
                        $message = __('users.MessageUnsupportedGrantType');
                        break;
                    case 'invalid_request':
                        $message = __('users.MessageInvalidRequest');
                        break;
                    default:
                        $message = $exception->getMessage();
                }

                return response()->json([
                    'error'   => is_string($type) ? $message : $type,
                    'message' => $message,
//                    'hint' => $exception->getHint(),
                ], $exception->getHttpStatusCode());
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request                 $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => __('auth.Unauthenticated')], 401);
        }
        $company = LibRoute::getCompany($request);
        $redirectTo = $company ? route('admin.companyLogin.init', [
            'company' => $company,
        ]) : route('admin.init');

        return redirect()->guest($redirectTo);
    }
}
