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
use App\Ipolitic\Nawpcore\Views\Decimal;

class DecimalField extends Field implements FieldInterface
{
    public function checkValidity(): string
    {
        if (!is_numeric($this->value)) {
            return "The given value was not numeric";
        }
        return "";
    }

    public function getViews(): array
    {
        return [Decimal::class => $this->prop];
    }
}
