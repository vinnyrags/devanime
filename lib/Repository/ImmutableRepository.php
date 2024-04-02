<?php

namespace DevAnime\Repository;

/**
 * Interface ImmutableRepository
 * @package DevAnime\Repository
 */
interface ImmutableRepository
{
    public function findById(int $id);

    public function findOne(array $query);

    public function findAll();

    public function find(array $query);
}
