<?php

namespace DevAnime\Model\Term;

/**
 * Class TermGeneric
 * @package DevAnime\Model\Term
 */
class TermGeneric extends TermBase
{
    public function __construct($term, ?string $taxonomy = null)
    {
        if ($taxonomy) {
            $term = get_term($term, $taxonomy);
        }
        parent::__construct($term);
    }
}
