<?php
declare(strict_types=1);

namespace App\Datasources\Categorie;

use App\Datasources\User\User;
use Atlas\Mapper\MapperRelationships;

class CategorieRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('[author]', User::class);
        $this->manyToOne('[parent]', Categorie::class);
    }
}
