<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Repository\TagRepository;

/**
 * Class TagService
 */
class TagService
{
    private TagRepository $tagRepository;

    /**
     * Construct new tag service object.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }
}
