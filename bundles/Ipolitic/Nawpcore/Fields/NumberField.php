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

class NumberField extends Field implements FieldInterface
{
    public function checkValidity(): string
    {
        // TODO: Implement checkValidity() method.
        return "";
    }

    public function render(): string
    {
        // TODO: Implement render() method.
        return "";
    }
}