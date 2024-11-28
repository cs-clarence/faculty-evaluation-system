<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    public function __construct()
    {
        $this->proxies = preg_split('/,/', Config::get('app.trusted_proxy_ips', ''));
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PORT |
    Request::HEADER_X_FORWARDED_PROTO |
    Request::HEADER_X_FORWARDED_AWS_ELB;
}
