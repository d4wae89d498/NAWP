<?php
declare(strict_types=1);

namespace App\Datasources\Log;

use Atlas\Table\TableSelect;

/**
 * @method LogRow|null fetchRow()
 * @method LogRow[] fetchRows()
 */
class LogTableSelect extends TableSelect
{
}
