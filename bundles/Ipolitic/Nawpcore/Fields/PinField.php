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
        "length"         => [null, null],
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
        if ((($this->prop["length"][0] === null) || ($intVal >= $this->prop["length"][0])) &&
            (($this->prop["length"][0] === null) || ($intVal <= $this->prop["length"][1]))) {
            return   "";
        } else {
            return  "The password length must be in length [" .
            $this->prop["length"][0] !== null ? $this->prop["length"][0] : "-inf" . "," .
            $this->prop["length"][0] !== null ? $this->prop["length"][1] : "+inf" ."]";
        }
    }

    public function getViews(): array
    {
        return [Number::class => $this->prop];
    }
}
