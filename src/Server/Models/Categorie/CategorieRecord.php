<?php
declare(strict_types=1);

namespace App\Server\Models\Categorie;

use Atlas\Mapper\Record;

/**
 * @method CategorieRow getRow()
 */
class CategorieRecord extends Record
{
    use CategorieFields;
}
