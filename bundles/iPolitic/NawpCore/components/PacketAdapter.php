<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/1/2018
 * Time: 12:06 PM
 */
namespace App\iPolitic\NawpCore\Components;

use App\iPolitic\NawpCore\Kernel;
use Workerman\Protocols\Http;

class PacketAdapter
{
    /**
     * Folder name in root/cache
     */
    public const PACKET_ADAPTER_FOLDER = "packet_adapter";

    /**
     * Startup function, will remove all cache files before startup
     */
    public static function init(): void
    {
        // removing cache files
        $files = glob(
            join(
                DIRECTORY_SEPARATOR,
                [
                Kernel::getKernel()->cachePath, self::PACKET_ADAPTER_FOLDER , "*"]
            )
        );
        foreach ($files as $file) {
            if (!is_dir($file) && file_exists($file)) {
                @unlink($file);
            }
        }
    }

    /**
     * Will return a packet adapter cache path of the given id
     * @param string $id
     * @return string
     */
    public static function IDtoPath(string $id): string
    {
        return join(
            DIRECTORY_SEPARATOR,
            [
                dirname(__FILE__) ,
                "..",
                "..",
                "..",
                "..",
                "cache",
                self::PACKET_ADAPTER_FOLDER,
                $id . ".txt",
            ]
        );
    }

    /**
     *  Will redirect the http or the socket response
     * @param string $response
     * @param ViewLogger $viewLogger
     * @param string $url
     * @param array $args
     * @param string $requestType
     * @throws \iPolitic\Solex\RouterException
     */
    public static function redirectTo(
        string &$response,
        ViewLogger &$viewLogger,
        string $url,
        array $args = [],
        string $requestType = ViewLogger::DEFAULT_REQUEST_TYPE
    ): void {
        echo "REDIRECTING TO : " . $url . PHP_EOL;
        if (strtolower($requestType) !== "socket") {
            Http::header("Location: " . $url);
        } else {
            $_SERVER["REQUEST_URI"] = $url;
            Kernel::getKernel()->handle(
                $response,
                isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : ViewLogger::DEFAULT_REQUEST_TYPE,
                $_SERVER["REQUEST_URI"],
                null,
                $args,
                $viewLogger
            );
        }
    }

    /**
     * Will cache a packetAdapter file and return an ID
     * @param ViewLogger $viewLogger
     * @return string
     * @throws \Exception
     */
    public static function storeAndGet(ViewLogger $viewLogger): string
    {
        if ($viewLogger->requestType === "SOCKET") {
            // id should be available as post clientVar or something like that
            $hashedId = $_POST["originalClientVar"];
        // here session is not available
        } else {
            $hashedId = Session::id($viewLogger);
            $filePath = self::IDtoPath($hashedId);
            // here Session is available
            if (file_exists($filePath)) {
                unlink(self::IDtoPath($hashedId));
            }
            $fp = fopen(self::IDtoPath(Session::id($viewLogger)), "w+");
            fwrite($fp, (serialize($_SERVER)));
        }

        return $hashedId;
    }

    /**
     * Will return a packetAdapter cached file
     * @param string $id
     * @return array
     */
    public static function readFile(string $id): array
    {
        if (file_exists($filePath = self::IDtoPath($id))) {
            $fp = fopen($filePath, 'r') or die('cant open file');
            return unserialize(
                fread($fp, filesize($filePath))
            );
        } else {
            return [];
        }
    }
}
