<?php
declare(strict_types=1);

namespace App\Datasources\Content;

use Atlas\Table\TableSelect;

/**
 * @method ContentRow|null fetchRow()
 * @method ContentRow[] fetchRows()
 */
class ContentTableSelect extends TableSelect
{
}
