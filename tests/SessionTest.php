<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/28/2018
 * Time: 8:30 PM
 */

final class SessionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Check that persistent session storage is working
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function testThatSessionStorageIsWorking(): void
    {
        \App\Ipolitic\Nawpcore\Kernel::$PHPUNIT_MODE = true;
        $kernel = new \App\Ipolitic\Nawpcore\Kernel();
        $test_uid = \App\Ipolitic\Nawpcore\Components\Utils::generateUID();
        $test_str = "patate";
        $_COOKIE["UID"] =  $test_uid;
        $request = (new \Jasny\HttpMessage\ServerRequest())->withGlobalEnvironment(true);
        $viewLogger = new \App\Ipolitic\Nawpcore\Components\ViewLogger($kernel, $request);
        $viewLogger->sessionInstance->destroy();
        $viewLogger->sessionInstance->set($test_str, $test_str);
        $_COOKIE["UID"] =  $test_uid;
        $viewLogger = new \App\Ipolitic\Nawpcore\Components\ViewLogger($kernel, $request);
        $this->assertTrue($viewLogger->sessionInstance->has($test_str));
    }
}