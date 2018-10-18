<?php
declare(strict_types=1);

namespace App\Datasources\Sysdiagrams;

use Atlas\Mapper\Record;

/**
 * @method SysdiagramsRow getRow()
 */
class SysdiagramsRecord extends Record
{
    use SysdiagramsFields;
}
