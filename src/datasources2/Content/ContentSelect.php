<?php
declare(strict_types=1);

namespace App\DataSources\Content;

use Atlas\Mapper\MapperSelect;

/**
 * @method ContentRecord|null fetchRecord()
 * @method ContentRecord[] fetchRecords()
 * @method ContentRecordSet fetchRecordSet()
 */
class ContentSelect extends MapperSelect
{
}
