<?php
declare(strict_types=1);

namespace App\DataSources\ContentsCateogire;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method ContentsCateogireTable getTable()
 * @method ContentsCateogireRelationships getRelationships()
 * @method ContentsCateogireRecord|null fetchRecord($primaryVal, array $with = [])
 * @method ContentsCateogireRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method ContentsCateogireRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method ContentsCateogireRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method ContentsCateogireRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method ContentsCateogireRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method ContentsCateogireSelect select(array $whereEquals = [])
 * @method ContentsCateogireRecord newRecord(array $fields = [])
 * @method ContentsCateogireRecord[] newRecords(array $fieldSets)
 * @method ContentsCateogireRecordSet newRecordSet(array $records = [])
 * @method ContentsCateogireRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method ContentsCateogireRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class ContentsCateogire extends Mapper
{
}
