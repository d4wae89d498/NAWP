<?php
namespace App\DataSources\Categorie;

use App\DataSources\User\UserMapper;
use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class CategorieMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        $this->manyToOne('parent', CategorieMapper::class);
        $this->manyToOne('author', UserMapper::class);

        // no related fields
    }
}
