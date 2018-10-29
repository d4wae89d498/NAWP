<?php
declare(strict_types=1);

namespace App\Datasources\ContentsCategory;

use Atlas\Mapper\Record;

/**
 * @method ContentsCategoryRow getRow()
 */
class ContentsCategoryRecord extends Record
{
    use ContentsCategoryFields;
}
