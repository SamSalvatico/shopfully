<?php

namespace App\Traits;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch (true) {
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound();
                break;
            case $this->isValidationException($e):
                $retval = $this->badRequest("I dati non sono validi", 400, $e->errors());
                break;
            case $this->isBadRequestException($e):
                $retval = $this->badRequest($e->getMessage(), 400);
                break;
            case $this->isNotFoundHttpException($e):
                $retval = $this->badRequest("Route doesn't exist", 404);
                break;
            case $this->isNotAllowedException($e):
                $retval = $this->badRequest("Method not allowed", 405);
                break;
            case $this->isAuthenticationException($e):
                $retval = $this->badRequest("Unauthenticated", 401);
                break;
            case $this->isAuthorizationException($e):
                $retval = $this->badRequest($e->getMessage(), 403);
                break;
            default:
                $retval = $this->badRequest($e->getMessage(), 500);
        }

        return $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message = 'Bad request', $statusCode = 400, $errors = null)
    {
        if (!empty($errors) && count($errors))
            return $this->jsonResponse(['message' => $message, 'errors' => $errors], $statusCode);
        else
            return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message = 'Record not found', $statusCode = 404)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = 404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is a validation error.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isValidationException(Exception $e)
    {
        return $e instanceof ValidationException;
    }

    /**
     * Determines if the given exception is a validation error.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isBadRequestException(Exception $e)
    {
        return $e instanceof BadRequestHttpException;
    }

    /**
     * Determines if the given exception is a not found http exception.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isNotFoundHttpException(Exception $e)
    {
        return $e instanceof NotFoundHttpException;
    }

    /**
     * Determines if the given exception is a not allowed http exception.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isNotAllowedException(Exception $e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    /**
     * Determines if the given exception is auth http exception.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isAuthenticationException(Exception $e)
    {
        return $e instanceof AuthenticationException;
    }

    /**
     * Determines if the given exception is authR http exception.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isAuthorizationException(Exception $e)
    {
        return $e instanceof AuthorizationException;
    }
}
