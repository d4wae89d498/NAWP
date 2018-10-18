<?php
declare(strict_types=1);

namespace App\Datasources\Sysdiagrams;

use Atlas\Mapper\RecordSet;

/**
 * @method SysdiagramsRecord offsetGet($offset)
 * @method SysdiagramsRecord appendNew(array $fields = [])
 * @method SysdiagramsRecord|null getOneBy(array $whereEquals)
 * @method SysdiagramsRecordSet getAllBy(array $whereEquals)
 * @method SysdiagramsRecord|null detachOneBy(array $whereEquals)
 * @method SysdiagramsRecordSet detachAllBy(array $whereEquals)
 * @method SysdiagramsRecordSet detachAll()
 * @method SysdiagramsRecordSet detachDeleted()
 */
class SysdiagramsRecordSet extends RecordSet
{
}
