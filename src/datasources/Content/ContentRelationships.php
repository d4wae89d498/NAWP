<?php
declare(strict_types=1);

namespace App\Datasources\Content;

use App\Datasources\Categorie\Categorie;
use App\Datasources\User\User;
use Atlas\Mapper\MapperRelationships;

class ContentRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('[author]', User::class);
        $this->manyToOne('[parent]', Categorie::class);
    }
}
