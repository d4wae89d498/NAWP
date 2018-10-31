<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/13/2018
 * Time: 12:22 PM
 */
namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Exceptions\NotFoundExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class Cookie
 * @package App\Ipolitic\Nawpcore\Components
 */
class Cookie implements ContainerInterface
{
    public const COOKIE8_SID_KEY = "SID";
    public const DEFAULT_COOKIE_DURATION = 30 * 60; // in seconds
    public const DEFAULT_TEST_COOKIE_STR = "TEST_COOKIE"; // test cookie name for checking if cookies are enabled or not

    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $value;
    /**
     * @var int
     */
    public $duration;

    /**
     * Cookie constructor.
     * @param string $name
     * @param string $value
     * @param int $duration
     */
    public function __construct(string $name, string $value, int $duration = self::DEFAULT_COOKIE_DURATION)
    {
        $this->name = $name;
        $this->value = $value;
        $this->duration = $duration;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->$key);
    }

    /**
     * @param string $key
     * @return mixed|void
     * @throws NotFoundExceptionInterface
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new NotFoundExceptionInterface();
        }
    }
}
