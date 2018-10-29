<?php
declare(strict_types=1);

namespace App\Datasources\Content;

use Atlas\Mapper\RecordSet;

/**
 * @method ContentRecord offsetGet($offset)
 * @method ContentRecord appendNew(array $fields = [])
 * @method ContentRecord|null getOneBy(array $whereEquals)
 * @method ContentRecordSet getAllBy(array $whereEquals)
 * @method ContentRecord|null detachOneBy(array $whereEquals)
 * @method ContentRecordSet detachAllBy(array $whereEquals)
 * @method ContentRecordSet detachAll()
 * @method ContentRecordSet detachDeleted()
 */
class ContentRecordSet extends RecordSet
{
}
