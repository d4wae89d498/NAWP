<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/29/2018
 * Time: 7:35 PM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Kernel;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function checkThatAllProjectFilesAreIncluded() : void
    {
        new Kernel();
        $isSuccess = true;
        $declaredFiles = get_included_files();
        $prefix = join(DIRECTORY_SEPARATOR,Kernel::ROOT_PATH) . DIRECTORY_SEPARATOR;
        foreach (Kernel::FRAMEWORK_FOLDERS as $v) {
            $dirPath = $prefix . join(DIRECTORY_SEPARATOR, $v);
            echo $dirPath . PHP_EOL;
            /*$rii = new \RecursiveIteratorIterator
            (
                new \RecursiveDirectoryIterator($dirPath)
            );
            foreach ($rii as $file) {
                if ($file->isDir()){
                    continue;
                }
                var_dump($file);
                if(!in_array($file->getPathname(), $declaredFiles)) {
                    $isSuccess = false;
                    break;
                }
            } if (!$isSuccess) {break;}*/
        }
        $this->assertTrue($isSuccess);
    }

    public function testThatKernelRawTwigIsCorrect() : void
    {

    }

    public function testThatEnvWasPopulated(): void
    {

    }

    public function testThatKernelCollectionsAreFilled(): void
    {

    }

    public function testLogger() : void
    {

    }

    public function testAtlas(): void
    {

    }
}