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

class EmailField extends Field implements FieldInterface
{
    public function checkValidity(): string
    {
        if (is_string($this->value)) {
            // check if arobase
            if (strpos($this->value, "@") !== false) {
                // check if domain
                if (strpos(explode("@", $this->value)[1], ".") !== false) {
                    return "";
                } else {
                    return "Your email is not in valid format : *@*.*";
                }
            } else {
                return "Your email is not in valid format : *@*.*";
            }
        } else {
            return "Given value was not a string.";
        }
    }

    public function render(): string
    {
        // TODO: Implement render() method.
    }
}
