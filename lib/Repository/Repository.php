<?php

namespace DevAnime\Repository;

use DevAnime\Model\Post\PostBase;

/**
 * Interface Repository
 * @oackage DevAnime\Repository
 */
interface Repository extends ImmutableRepository
{
    public function add(PostBase $Post);

    public function remove(PostBase $Post);

}