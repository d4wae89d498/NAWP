<?php
declare(strict_types=1);

namespace App\Server\Models\Translation;

use Atlas\Mapper\Record;

/**
 * @method TranslationRow getRow()
 */
class TranslationRecord extends Record
{
    use TranslationFields;
}
