<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/11/18
 * Time: 16:03
 */

namespace App\Ipolitic\Nawpcore\Fields;

use App\Ipolitic\Nawpcore\Components\Field;
use App\Ipolitic\Nawpcore\Interfaces\FieldInterface;

class FilesField extends Field implements FieldInterface
{
    public function checkValidity(): string
    {
        // todo : check file extension for each sub elements
        $result = json_decode($this->value);
        if (json_last_error() === JSON_ERROR_NONE or empty($this->value)) {
            return "";
        } else {
            return "Given value was not a string";
        }
    }

    public function getViews(): array
    {
        // TODO: Implement render() method.
        return [];
    }
}
