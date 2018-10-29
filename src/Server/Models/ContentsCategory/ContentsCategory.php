<?php
declare(strict_types=1);

namespace App\Server\Models\ContentsCategory;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method ContentsCategoryTable getTable()
 * @method ContentsCategoryRelationships getRelationships()
 * @method ContentsCategoryRecord|null fetchRecord($primaryVal, array $with = [])
 * @method ContentsCategoryRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method ContentsCategoryRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method ContentsCategoryRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method ContentsCategoryRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method ContentsCategoryRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method ContentsCategorySelect select(array $whereEquals = [])
 * @method ContentsCategoryRecord newRecord(array $fields = [])
 * @method ContentsCategoryRecord[] newRecords(array $fieldSets)
 * @method ContentsCategoryRecordSet newRecordSet(array $records = [])
 * @method ContentsCategoryRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method ContentsCategoryRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class ContentsCategory extends Mapper
{
}
