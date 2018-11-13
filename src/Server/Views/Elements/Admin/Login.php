<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 1:37 PM
 */
namespace App\Server\Views\Elements\Admin;

use App\Ipolitic\Nawpcore\Components\View;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Interfaces\TwigInterface;
use function foo\func;
use Psr\Log\LoggerInterface;

class Login extends View implements TwigInterface
{
    public $states = [
        "email"     => "",
        "rand"      => 0,
        "message"   => "",
        "cookie_on" => "false",
        "fields"    => [
                "firstName"        => ["v" => "aaaa", "m" => "NOT EMPTY"],  // 0 : value | 1 : error message
                "lastName"         => ["v" => null, "m" => null],
                "birthPlace"       => ["v" => null, "m" => null],
                "birthDay"         => ["v" => null, "m" => null],
                "pin"              => ["v" => null, "m" => null],
                "pin2"             => ["v" => null, "m" => null],
                "accessTypeRadio"  => ["v" => null, "m" => null],],
    ];
}
