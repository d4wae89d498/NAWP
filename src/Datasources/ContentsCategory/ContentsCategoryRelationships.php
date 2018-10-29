<?php
declare(strict_types=1);

namespace App\Datasources\ContentsCategory;

use App\Datasources\Categorie\Categorie;
use App\Datasources\Content\Content;
use Atlas\Mapper\MapperRelationships;

class ContentsCategoryRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('[content]', Content::class);
        $this->manyToOne('[categorie]', Categorie::class);
    }
}
