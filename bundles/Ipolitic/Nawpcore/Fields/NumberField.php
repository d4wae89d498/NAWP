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
use App\Ipolitic\Nawpcore\Views\Number;

class NumberField extends Field implements FieldInterface
{
    public function checkValidity(): string
    {
        if (!filter_var($this->value, FILTER_VALIDATE_INT)) {
            return "Given value was not a number";
        }
        return "";
    }

    public function getViews(): array
    {
        return [Number::class => $this->prop];
    }
}
