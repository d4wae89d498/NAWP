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
use App\Ipolitic\Nawpcore\Views\Text;

class TextField extends Field implements FieldInterface
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
        "length"         => [0, 999]
    ];

    public function checkValidity(): string
    {
        if (!is_string($this->value)) {
            return "Given value was not a vlid string";
        }
    //   if (preg_match("/[~!@#\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\.\<\>\?\/]+/", $this->value)) {
           if ((($this->prop["length"][0] === null) or (strlen($this->value) >= $this->prop["length"][0])) and
               ($this->prop["length"][1] === null) or (strlen($this->value) <= $this->prop["length"][0])) {
               return "";
           } else {
               return "The string length must be between ["
                   .(string)($this->prop["length"][0]===null?0:$this->prop["length"][0]).","
                   .(string)($this->prop["length"][1]===null?"+inf":$this->prop["length"][1]) ."]";
           }
     /*  } else {
           return "The given string did not match a valid name format";
       }*/
    }

    public function getViews(): array
    {
        return [ Text::class => $this->prop];
    }
}
