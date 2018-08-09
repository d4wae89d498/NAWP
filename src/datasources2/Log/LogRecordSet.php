<?php
declare(strict_types=1);

namespace App\DataSources\Log;

use Atlas\Mapper\RecordSet;

/**
 * @method LogRecord offsetGet($offset)
 * @method LogRecord appendNew(array $fields = [])
 * @method LogRecord|null getOneBy(array $whereEquals)
 * @method LogRecordSet getAllBy(array $whereEquals)
 * @method LogRecord|null detachOneBy(array $whereEquals)
 * @method LogRecordSet detachAllBy(array $whereEquals)
 * @method LogRecordSet detachAll()
 * @method LogRecordSet detachDeleted()
 */
class LogRecordSet extends RecordSet
{
}
