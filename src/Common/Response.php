<?php

namespace Finalx\Laravel\Common;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Response
{

    /**
     * 返回一个标准的 JSON 响应
     *
     * @static
     * @param mixed $data 要返回的数据
     * @param string $message 响应信息，默认是 '请求成功'
     * @param int $code HTTP 状态码，默认是 200
     * @param array $header 额外的 HTTP 头
     * @return JsonResponse
     */
    static public function respond($data = null, $message = '请求成功', $code = JsonResponse::HTTP_OK, array $header = []): JsonResponse
    {
        $res = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($res, $code, $header, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @description: 仅返回状态码和信息
     * @param {*} $message
     * @param {*} $code
     * @param {array} $header
     * @return {*}
     */
    static public function notDataRespond($message = '请求成功', $code = JsonResponse::HTTP_OK, array $header = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ], $code, $header, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 处理异常并返回标准化的 JSON 响应
     *
     * @static
     * @param mixed $exceptions 要处理的异常
     * @return void
     */
    static public function exception(Exception $e): JsonResponse
    {
        if ($e instanceof HttpException) $code = $e->getStatusCode();
        else if ($e instanceof AuthenticationException) $code = JsonResponse::HTTP_UNAUTHORIZED;
        else $code = $e->getCode() ? $e->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $code = (!!$code && gettype($code)  === "integer") ? $code : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

        return self::notDataRespond($e->getMessage(), $code);
    }
}
