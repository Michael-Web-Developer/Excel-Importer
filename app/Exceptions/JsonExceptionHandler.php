<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Auth\AuthenticationException;

class JsonExceptionHandler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthenticationException || $e instanceof UnauthorizedHttpException) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return parent::render($request, $e);
    }

    protected function invalid($request, ValidationException $exception)
    {
        return $this->invalidJson(
            $request,
            $exception
        );
    }
}
