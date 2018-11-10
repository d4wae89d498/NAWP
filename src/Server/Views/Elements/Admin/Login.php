<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 1:37 PM
 */
namespace App\Server\Views\Elements\Admin;

use App\Ipolitic\Nawpcore\Components\View;
use App\Ipolitic\Nawpcore\Interfaces\TwigInterface;
use function foo\func;

class Login extends View implements TwigInterface
{
    public $states = [
        "email"     => "",
        "rand"      => 0,
        "message"   => "",
        "cookie_on" => "false",
        "fields"    => [
                "firstName"        => [null,null],
                "lastName"         => [null,null],
                "birthPlace"       => [null,null],
                "birthDay"         => [null,null],
                "pin"              => [null,null],
                "pin2"             => [null,null],
                "accessTypeRadio"  => [null,null],],
    ];
}
