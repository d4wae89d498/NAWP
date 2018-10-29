<?php
declare(strict_types=1);

namespace App\Server\Models\Translation;

use Atlas\Mapper\RecordSet;

/**
 * @method TranslationRecord offsetGet($offset)
 * @method TranslationRecord appendNew(array $fields = [])
 * @method TranslationRecord|null getOneBy(array $whereEquals)
 * @method TranslationRecordSet getAllBy(array $whereEquals)
 * @method TranslationRecord|null detachOneBy(array $whereEquals)
 * @method TranslationRecordSet detachAllBy(array $whereEquals)
 * @method TranslationRecordSet detachAll()
 * @method TranslationRecordSet detachDeleted()
 */
class TranslationRecordSet extends RecordSet
{
}
