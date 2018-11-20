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
    /**
     * @var array
     */
    public $prop = [
        "message"       => "",
        "value"         => "",
        "column"        => "",
        "placeholder"   => "",
        "icon"          => "",
        "range"         => [null, null]
    ];

    /**
     * @return string
     */
    public function checkValidity(): string
    {
        if (!is_numeric($this->value)) {
            return  "Given value was not a valid decimal.";
        }
        $floatVal = floatval($this->value);
        // test if in range
        if ((($this->prop["range"][0] === null) || ($floatVal >= $this->prop["range"][0])) &&
            (($this->prop["range"][0] === null) || ($floatVal <= $this->prop["range"][1]))) {
            return   "";
        } else {
           return  "Value must be in range [" .
           $this->prop["range"][0] !== null ? $this->prop["range"][0] : "-inf" . "," .
           $this->prop["range"][0] !== null ? $this->prop["range"][1] : "+inf" ."]";
        }
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        return [Decimal::class => $this->prop];
    }
}
