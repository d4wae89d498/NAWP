<?php
declare(strict_types=1);

namespace App\DataSources\ContentsCateogire;

use App\DataSources\Categorie\Categorie;
use App\DataSources\Content\Content;
use Atlas\Mapper\MapperRelationships;

class ContentsCateogireRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('content', Content::class);
        $this->manyToOne('categorie', Categorie::class);
    }
}
