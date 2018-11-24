<?php
declare(strict_types=1);

namespace App\Server\Models\User;

use App\Server\Models\Categorie\Categorie;
use App\Server\Models\Content\Content;
use Atlas\Mapper\MapperRelationships;

class UserRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne("role", Content::class, ["role_id" => "row_id"]);
    }
}
