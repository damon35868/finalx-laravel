<?php

namespace Finalx\Laravel\Common;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Response
{

    static public function respond($data = null, $message = '请求成功', $code = JsonResponse::HTTP_OK, array $header = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data ? $data : null
        ], $code, $header, JSON_UNESCAPED_UNICODE);
    }

    static public function exception($exceptions)
    {
        $exceptions->render(function (Exception $e) {
            if ($e instanceof HttpException) $code = $e->getStatusCode();
            else if ($e instanceof AuthenticationException) $code = JsonResponse::HTTP_UNAUTHORIZED;
            else $code = $e->getCode() ? $e->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

            return self::respond(null, $e->getMessage(), $code);
        });
    }
}
