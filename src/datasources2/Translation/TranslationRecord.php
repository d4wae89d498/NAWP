<?php
declare(strict_types=1);

namespace App\DataSources\Translation;

use Atlas\Mapper\Record;

/**
 * @method TranslationRow getRow()
 */
class TranslationRecord extends Record
{
    use TranslationFields;
}
