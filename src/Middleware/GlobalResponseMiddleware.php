<?php

namespace Finalx\Laravel\Middleware;

use Closure;
use Finalx\Laravel\Common\Response as FinalxResponse;
use Illuminate\Http\JsonResponse as JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GlobalResponseMiddleware
{
    private array $excludes = ['/'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse)  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        $res = $next($request);
        // 错误的不处理，直接往外抛，交给全局异常去处理
        if ($res->isClientError() || $res->isServerError()) return $res;
        if (in_array($request?->route()?->uri, $this->excludes)) return $res;

        return FinalxResponse::respond($res->original);
    }
}
