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
    static public function respond($data = null, $message = '请求成功', $code = JsonResponse::HTTP_OK, array $header = [])
    {
        $res = $data === false ? [
            'code' => $code,
            'message' => $message
        ] : [
            'code' => $code,
            'message' => $message,
            'data' => $data ? $data : null
        ];

        return response()->json($res, $code, $header, JSON_UNESCAPED_UNICODE);
    }


    /**
     * 处理异常并返回标准化的 JSON 响应
     *
     * @static
     * @param mixed $exceptions 要处理的异常
     * @return void
     */
    static public function exception($exceptions)
    {
        $exceptions->render(function (Exception $e) {
            if ($e instanceof HttpException) $code = $e->getStatusCode();
            else if ($e instanceof AuthenticationException) $code = JsonResponse::HTTP_UNAUTHORIZED;
            else $code = $e->getCode() ? $e->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

            return self::respond(false, $e->getMessage(), $code);
        });
    }
}
