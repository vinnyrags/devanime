<?php

namespace DevAnime\Controller;

use DevAnime\Rest\Support\RestEndpoint;
use WP_REST_Request;
use WP_REST_Server;

/**
 * class SingleRouteRestController
 * @package DevAnime\Controller
 */
abstract class SingleRouteRestEndpoint extends RestEndpoint
{
    protected $route_path = '/';
    protected $route_methods = WP_REST_Server::READABLE;
    protected $route_permission = false;

    public function registerRoutes()
    {
        $this->addRoute($this->route_path, $this->route_methods, 'execute', $this->route_permission);
    }

    abstract public function execute(WP_REST_Request $request);
}