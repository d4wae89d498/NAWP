<?php
declare(strict_types=1);

namespace App\DataSources\Log;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method LogTable getTable()
 * @method LogRelationships getRelationships()
 * @method LogRecord|null fetchRecord($primaryVal, array $with = [])
 * @method LogRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method LogRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method LogRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method LogRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method LogRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method LogSelect select(array $whereEquals = [])
 * @method LogRecord newRecord(array $fields = [])
 * @method LogRecord[] newRecords(array $fieldSets)
 * @method LogRecordSet newRecordSet(array $records = [])
 * @method LogRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method LogRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Log extends Mapper
{
}
