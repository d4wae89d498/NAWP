<?php
declare(strict_types=1);

namespace App\DataSources\ContentsCateogire;

use Atlas\Mapper\Record;

/**
 * @method ContentsCateogireRow getRow()
 */
class ContentsCateogireRecord extends Record
{
    use ContentsCateogireFields;
}
