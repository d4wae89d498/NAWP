<?php
declare(strict_types=1);

namespace App\Server\Models\Log;

use Atlas\Mapper\MapperSelect;

/**
 * @method LogRecord|null fetchRecord()
 * @method LogRecord[] fetchRecords()
 * @method LogRecordSet fetchRecordSet()
 */
class LogSelect extends MapperSelect
{
}
