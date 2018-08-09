<?php
namespace App\DataSources\Log;

use App\DataSources\User\UserMapper;
use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class LogMapper extends AbstractMapper
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
