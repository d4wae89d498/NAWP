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
    ];

    /**
     * @return string
     */
    public function checkValidity(): string
    {
        if (!filter_var($this->value, FILTER_VALIDATE_INT)) {
            return "Given value was not a number";
        }
        $intVal = intval($this->value);
        if ((($this->prop["range"][0] === null) || ($intVal >= $this->prop["range"][0])) &&
            (($this->prop["range"][0] === null) || ($intVal <= $this->prop["range"][1]))) {
            return   "";
        } else {
            return  "Value must be in range [" .
            $this->prop["range"][0] !== null ? $this->prop["range"][0] : "-inf" . "," .
            $this->prop["range"][0] !== null ? $this->prop["range"][1] : "+inf" ."]";
        }
    }

    public function getViews(): array
    {
        return [Number::class => $this->prop];
    }
}
