<?php
declare(strict_types=1);

namespace App\Datasources\Content;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method ContentTable getTable()
 * @method ContentRelationships getRelationships()
 * @method ContentRecord|null fetchRecord($primaryVal, array $with = [])
 * @method ContentRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method ContentRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method ContentRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method ContentRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method ContentRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method ContentSelect select(array $whereEquals = [])
 * @method ContentRecord newRecord(array $fields = [])
 * @method ContentRecord[] newRecords(array $fieldSets)
 * @method ContentRecordSet newRecordSet(array $records = [])
 * @method ContentRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method ContentRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Content extends Mapper
{
}
