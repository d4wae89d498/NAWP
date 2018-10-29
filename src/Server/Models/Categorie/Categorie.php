<?php
declare(strict_types=1);

namespace App\Datasources\Categorie;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method CategorieTable getTable()
 * @method CategorieRelationships getRelationships()
 * @method CategorieRecord|null fetchRecord($primaryVal, array $with = [])
 * @method CategorieRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method CategorieRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method CategorieRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method CategorieRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method CategorieRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method CategorieSelect select(array $whereEquals = [])
 * @method CategorieRecord newRecord(array $fields = [])
 * @method CategorieRecord[] newRecords(array $fieldSets)
 * @method CategorieRecordSet newRecordSet(array $records = [])
 * @method CategorieRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method CategorieRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Categorie extends Mapper
{
}
