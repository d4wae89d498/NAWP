<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/29/2018
 * Time: 7:35 PM
 */

namespace App\Tests;

use App\Ipolitic\Nawpcore\Components\Controller;
use App\Ipolitic\Nawpcore\Components\SQL;
use App\Ipolitic\Nawpcore\Components\View;
use App\Ipolitic\Nawpcore\Kernel;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareInterface;

class KernelTest extends TestCase
{
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function testThatAllProjectFilesAreIncluded() : void
    {
        new Kernel();
        $includedFiles = get_included_files();
        $gotFiles = [];
        $analyseFolder = function (string $directory, int $deep = 0) use (&$analyseFolder, &$gotFiles) {
            if ($deep > Kernel::MAX_INC_DEEP) {
                return;
            }
            if (is_dir($directory)) {
                $scan = scandir($directory);
                unset($scan[0], $scan[1]); //unset . and ..
                if (!file_exists($directory . DIRECTORY_SEPARATOR . ".noInclude")) {
                    foreach ($scan as $file) {
                        if (is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
                            $analyseFolder($directory . DIRECTORY_SEPARATOR . $file, $deep + 1);
                        } else {
                            if (strpos($file, '.php') !== false) {
                                $gotFiles[] = ($directory . DIRECTORY_SEPARATOR . $file);
                            }
                        }
                    }
                }
            }
        };
        $prefix = join(DIRECTORY_SEPARATOR, [__DIR__, ".."]);
        foreach (Kernel::FRAMEWORK_FOLDERS as $v) {
            $analyseFolder($prefix . DIRECTORY_SEPARATOR . $v);
        }
        $areAllInArray = true;
        foreach ($gotFiles as $v) {
            $areAllInArray = $areAllInArray && in_array(realpath($v), $includedFiles);
            if (!$areAllInArray) {
                break;
            }
        }
        $this->assertTrue($areAllInArray);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testThatKernelRawTwigIsCorrect() : void
    {
        $kernel = new Kernel();
        $this->assertGreaterThan(0, count($kernel->rawTwig));
        $noTwigChars = true;
        foreach($kernel->rawTwig as $k => $v) {
            $c1 = stristr($v, "}");
            $c2 = stristr($v, "{");
            $noTwigChars = $noTwigChars && ($c1 === $c2 && $c1 === false);
            if(!$noTwigChars) {
                break;
            }
        }
        $this->assertTrue($noTwigChars);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testThatEnvWasPopulated(): void
    {
        new Kernel();
        $this->assertGreaterThan(0, strlen(getenv("SQL_DSN")));
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testThatKernelCollectionsAreFilled(): void
    {
        $kernel = new Kernel();
        //checking controllers
        $this->assertGreaterThan(0, count($kernel->controllerCollection));
        $areAllControllers = true;
        foreach($kernel->controllerCollection as $v) {
            $areAllControllers = $areAllControllers && ($v instanceof Controller);
            if (!$areAllControllers) {
                break;
            }
        }
        $this->assertTrue($areAllControllers);
        // checking views
        $this->assertGreaterThan(0, count($kernel->viewCollection));
        $areAllViews = true;
        foreach($kernel->viewCollection as $v) {
            $areAllViews = $areAllViews && ($v instanceof View);
            if (!$areAllViews) {
                break;
            }
        }
        $this->assertTrue($areAllViews);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testLogger() : void
    {
        $kernel = new Kernel();
        $this->assertInstanceOf(LoggerAwareInterface::class, $kernel);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testAtlas(): void
    {
        $kernel = new Kernel();
        $this->assertInstanceOf(SQL::class, $kernel->atlas);
    }
}