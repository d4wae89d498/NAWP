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
use App\Ipolitic\Nawpcore\Views\Email;

/**
 * Class EmailField
 * @package App\Ipolitic\Nawpcore\Fields
 */
class EmailField extends Field implements FieldInterface
{
    /**
     * @return string
     */
    public function checkValidity(): string
    {
        if (is_string($this->value)) {
            if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $this->value)) {
                return $this->prop["message"] = "";
            } else {
                return $this->prop["message"] = "Given value was not a valid email.";
            }
        } else {
            return $this->prop["message"] = "Given value was not a string.";
        }
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        return [Email::class => $this->prop];
    }
}
