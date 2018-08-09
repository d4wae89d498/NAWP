<?php
declare(strict_types=1);

namespace App\DataSources\Log;

use App\DataSources\User\User;
use Atlas\Mapper\MapperRelationships;

class LogRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('author', User::class);
    }
}
