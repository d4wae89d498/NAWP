<?php
declare(strict_types=1);

namespace App\DataSources\Content;

use App\DataSources\Categorie\Categorie;
use App\DataSources\User\User;
use Atlas\Mapper\MapperRelationships;

class ContentRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('author', User::class);
        $this->manyToOne('parent', Content::class);
    }
}
