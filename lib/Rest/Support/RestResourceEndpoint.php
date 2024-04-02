<?php

namespace DevAnime\Rest\Support;

use WP_REST_Request;

/**
 * class RestResourceEndpoint
 * @package DevAnime\Rest\Support
 */
abstract class RestResourceEndpoint extends RestEndpoint
{
    protected $resource_path = '/';

    public function registerRoutes() {
        $this->addRoutes($this->resource_path, [
            $this->addReadAction('list')
        ]);
        $this->addRoutes($this->resource_path . '/(?P<id>\d+)', [
            $this->addCreateAction('create'),
            $this->addReadAction('read'),
            $this->addEditAction('update'),
            $this->addDeleteAction('delete')
        ]);
    }

    abstract public function list(WP_REST_Request $request);

    abstract public function create(WP_REST_Request $request);

    abstract public function read(WP_REST_Request $request);

    abstract public function update(WP_REST_Request $request);

    abstract public function delete(WP_REST_Request $request);
}