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

class PinField extends Field implements FieldInterface
{
    /**
     * @var array
     */
    public $prop = [
        "message"       => "",
        "value"         => "",
        "column"        => "",
        "placeholder"   => "",
        "icon"          => "",
        "range"         => [null, null],
        "numOnly"       => false
    ];

    public function checkValidity(): string
    {
        if ($this->prop["numOnly"] !== false) {
            if (!filter_var($this->value, FILTER_VALIDATE_INT)) {
                return  "The pin code must be a number";
            }
        }
        $intVal = strlen((string) $this->value);
        if ((($this->prop["range"][0] === null) || ($intVal >= $this->prop["range"][0])) &&
            (($this->prop["range"][0] === null) || ($intVal <= $this->prop["range"][1]))) {
            return   "";
        } else {
            return  "The password length must be in range [" .
            $this->prop["range"][0] !== null ? $this->prop["range"][0] : "-inf" . "," .
            $this->prop["range"][0] !== null ? $this->prop["range"][1] : "+inf" ."]";
        }
        return "";
    }

    public function getViews(): array
    {
        return [Number::class => $this->prop];
    }
}
