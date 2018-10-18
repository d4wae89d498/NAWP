<?php
declare(strict_types=1);

namespace App\Datasources\User;

use Atlas\Table\TableSelect;

/**
 * @method UserRow|null fetchRow()
 * @method UserRow[] fetchRows()
 */
class UserTableSelect extends TableSelect
{
}
