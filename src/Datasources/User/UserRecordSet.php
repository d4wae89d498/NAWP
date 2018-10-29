<?php
declare(strict_types=1);

namespace App\Datasources\User;

use Atlas\Mapper\RecordSet;

/**
 * @method UserRecord offsetGet($offset)
 * @method UserRecord appendNew(array $fields = [])
 * @method UserRecord|null getOneBy(array $whereEquals)
 * @method UserRecordSet getAllBy(array $whereEquals)
 * @method UserRecord|null detachOneBy(array $whereEquals)
 * @method UserRecordSet detachAllBy(array $whereEquals)
 * @method UserRecordSet detachAll()
 * @method UserRecordSet detachDeleted()
 */
class UserRecordSet extends RecordSet
{
}
