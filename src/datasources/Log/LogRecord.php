<?php
declare(strict_types=1);

namespace App\Datasources\Log;

use Atlas\Mapper\Record;

/**
 * @method LogRow getRow()
 */
class LogRecord extends Record
{
    use LogFields;
}
