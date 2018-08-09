<?php
declare(strict_types=1);

namespace App\DataSources\Translation;

use App\DataSources\User\User;
use Atlas\Mapper\MapperRelationships;

class TranslationRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('author', User::class);
    }
}
