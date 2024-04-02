<?php

namespace DevAnime\Support;

/**
 * Class JsonManifest
 * @package DevAnime\Support
 */
class JsonManifest
{
    private array $manifest;

    public function __construct(string $manifestPath)
    {
        $this->manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
    }

    public function get(): array
    {
        return $this->manifest;
    }

    public function getPath(string $key = '', $default = null)
    {
        $collection = $this->manifest;

        if ($key === null) {
            return $collection;
        }

        $segments = explode('.', $key);
        foreach ($segments as $segment) {
            $collection = match (true) {
                isset($collection[$segment]) => $collection[$segment],
                default => $default,
            };
        }

        return $collection;
    }
}
