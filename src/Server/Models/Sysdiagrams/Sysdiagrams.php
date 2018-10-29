<?php
declare(strict_types=1);

namespace App\Server\Models\Sysdiagrams;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method SysdiagramsTable getTable()
 * @method SysdiagramsRelationships getRelationships()
 * @method SysdiagramsRecord|null fetchRecord($primaryVal, array $with = [])
 * @method SysdiagramsRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method SysdiagramsRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method SysdiagramsRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method SysdiagramsRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method SysdiagramsRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method SysdiagramsSelect select(array $whereEquals = [])
 * @method SysdiagramsRecord newRecord(array $fields = [])
 * @method SysdiagramsRecord[] newRecords(array $fieldSets)
 * @method SysdiagramsRecordSet newRecordSet(array $records = [])
 * @method SysdiagramsRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method SysdiagramsRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Sysdiagrams extends Mapper
{
}
