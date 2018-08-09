<?php
declare(strict_types=1);

namespace App\DataSources\Content;

use Atlas\Mapper\Record;

/**
 * @method ContentRow getRow()
 */
class ContentRecord extends Record
{
    use ContentFields;
}
