<?php
namespace App\DataSources\Content;

use App\DataSources\User\UserMapper;
use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class ContentMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        // no related fields
        $this->manyToOne('parent', ContentMapper::class);
        $this->manyToOne('author', UserMapper::class);
    }
}
