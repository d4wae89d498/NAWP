<?php declare(strict_types=1);

include_once "bootstrap.php";

use App\Ipolitic\Nawpcore\Kernel;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use Jasny\HttpMessage\ServerRequest;
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/28/2018
 * Time: 8:30 PM
 */

final class SessionTest extends TestCase
{

    /**
     * Check that persistent session storage is working
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function testThatSessionStorageIsWorking(): void
    {
        Kernel::$PHPUNIT_MODE = true;
        $kernel = new Kernel();
        $test_uid = Utils::generateUID();
        $test_str = "patate";
        $_COOKIE["UID"] =  $test_uid;
        $request = (new ServerRequest())->withGlobalEnvironment(true);
        $viewLogger = new ViewLogger($kernel, $request);
        $viewLogger->sessionInstance->destroy();
        $viewLogger->sessionInstance->set($test_str, $test_str);
        $_COOKIE["UID"] =  $test_uid;
        $viewLogger = new ViewLogger($kernel, $request);
        $this->assertTrue($viewLogger->sessionInstance->has($test_str));
    }
}