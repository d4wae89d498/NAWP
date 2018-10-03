<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/1/2018
 * Time: 12:06 PM
 */

namespace App\iPolitic\NawpCore\Components;

use App\iPolitic\NawpCore\Kernel;

class PacketAdapter
{
    /**
     * Folder name in root/cache
     */
    public const PACKET_ADAPTER_FOLDER = "packet_adapter";

    /**
     * Startup function, will remove all cache files before startup
     */
    public static function init(): void {
        $files = glob(
            join(
                DIRECTORY_SEPARATOR, [
                Kernel::getKernel()->cachePath, self::PACKET_ADAPTER_FOLDER , "*"]
            )
        );
        foreach($files as $file){ // iterate files
            if(!is_dir($file)) {
                unlink($file); // delete file
            }
        }
    }

    /**
     * Will return a packet adapter cache path of the given id
     * @param string $id
     * @return string
     */
    public static function IDtoPath(string $id): string {
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
     * Will cache a packetAdapter file and return an ID
     * @param string $requestMethod
     * @return string
     * @throws \Exception
     */
    public static function storeAndGet(string $requestMethod = ""): string {
        var_dump("STORE AND GET : " . $requestMethod);
        $idToUse = "";
        if ($requestMethod === "SOCKET") {
            // id should be available as post clientVar or something like that
            var_dump($_POST);
            $idToUse = "pomme";
            var_dump($_SERVER);
            exit;
            // here session is not available
        } else {
            // here Session is available
            if (!Session::isset("packet_adapter_key")) {
                Session::set("packet_adapter_key", $idToUse = self::generateId());
            }
        }
        $fp = fopen(self::IDtoPath($idToUse), "w+");
        fwrite($fp, (serialize($_SERVER)));
        return $idToUse;
    }

    /**
     * Will return a packetAdapter cached file
     * @param string $id
     * @return array
     */
    public static function get(string $id): array {
        if (file_exists($filePath = self::IDtoPath($id))) {
            $fp = fopen($filePath, 'r') or die('cant open file');
            return unserialize(
                fread($fp, filesize($filePath))
            );
        } else {
            return [];
        }
    }

    /**
     * Will generate a new ID
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function  generateId(int $length = 20): string {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length).microtime(true);
    }
}