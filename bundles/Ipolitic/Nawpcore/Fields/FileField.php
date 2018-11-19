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

class FileField extends Field implements FieldInterface
{
    public function checkValidity(): string
    {
        // todo : check file extension
        if (is_string($this->value)) {
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
