<?php
namespace App\DataSources\ContentsCategories;

use App\DataSources\Categorie\CategorieMapper;
use App\DataSources\Content\Content;
use Atlas\Orm\Mapper\AbstractMapper;

/**
 * @inheritdoc
 */
class ContentsCategoriesMapper extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function setRelated()
    {
        $this->manyToOne('categorie', CategorieMapper::class);
        $this->manyToOne('content', Content::class);
        // no related fields
    }
}
