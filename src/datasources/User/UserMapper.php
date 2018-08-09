<?php
namespace App\DataSources\User;

use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class UserMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        // no related fields
    }
}
