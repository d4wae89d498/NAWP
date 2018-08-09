<?php
declare(strict_types=1);

namespace App\DataSources\User;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method UserTable getTable()
 * @method UserRelationships getRelationships()
 * @method UserRecord|null fetchRecord($primaryVal, array $with = [])
 * @method UserRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method UserRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method UserRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method UserRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method UserRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method UserSelect select(array $whereEquals = [])
 * @method UserRecord newRecord(array $fields = [])
 * @method UserRecord[] newRecords(array $fieldSets)
 * @method UserRecordSet newRecordSet(array $records = [])
 * @method UserRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method UserRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class User extends Mapper
{
}
