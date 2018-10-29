<?php
declare(strict_types=1);

namespace App\Datasources\Log;

use App\Datasources\User\User;
use Atlas\Mapper\MapperRelationships;

class LogRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->manyToOne('[author]', User::class);
    }
}
