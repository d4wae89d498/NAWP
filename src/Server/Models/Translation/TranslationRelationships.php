<?php
declare(strict_types=1);

namespace App\Server\Models\Translation;

use App\Datasources\User\User;
use Atlas\Mapper\MapperRelationships;

class TranslationRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne("[author]", User::class);
    }
}
