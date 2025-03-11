<?php

namespace Finalx\Laravel\Middleware;

use Closure;
use Finalx\Laravel\Common\Response as JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $res = $next($request);
        // 错误的不处理，直接往外抛，交给全局异常去处理
        if ($res->isClientError() || $res->isServerError()) return $res;

        $content = $res->getContent();

        if (version_compare(PHP_VERSION, '8.3.0', '>=')) {
            $canJson = json_validate($content);
        } else {
            json_decode($content);
            $canJson = (json_last_error() === JSON_ERROR_NONE);
        }
        return JsonResponse::respond($canJson ? json_decode($content) : $content);
    }
}
