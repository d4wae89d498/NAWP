<?php
namespace App\DataSources\Translation;

use App\DataSources\User\UserMapper;
use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class TranslationMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        $this->manyToOne('author', UserMapper::class);
        // no related fields
    }
}
