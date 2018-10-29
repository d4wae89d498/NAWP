<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/1/2018
 * Time: 12:06 PM
 */
namespace App\Ipolitic\Nawpcore\Components;

use Psr\SimpleCache\CacheInterface;
use Workerman\Protocols\Http;

class PacketAdapter
{
    /**
     * Folder name in root/cache
     */
    public const PACKET_ADAPTER_FOLDER = "packet_adapter";
    /**
     * @var CacheInterface
     */
    public $cache;
    /**
     * PacketAdapter constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }


    /**
     *  Will redirect the http or the socket response
     * @param string $response
     * @param ViewLogger $viewLogger
     * @param array $args
     * @param string $requestType
     * @throws \iPolitic\Solex\RouterException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function redirectTo(
        string &$response,
        ViewLogger &$viewLogger,
        array $args = [],
        string $requestType = ViewLogger::DEFAULT_REQUEST_TYPE
    ): void {
        if (strtolower($requestType) !== "socket") {
            Http::header("Location: " . ($viewLogger->request->getServerParams()["REQUEST_URI"]));
        } else {
            $viewLogger->kernel->handle(
                $response,
                $request,
                $viewLogger->requestType,
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function storeAndGet(ViewLogger $viewLogger): string
    {
        if ($viewLogger->requestType === "SOCKET") {
            $hashedId = $_POST["originalClientVar"];
        } else {
            $hashedId = $viewLogger->sessionInstance->id();
            $this->cache->set("pa".$hashedId, serialize($_SERVER));
        }
        return $hashedId;
    }

    /**
     * Will return a packetAdapter cached file
     * @param string $id
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return array
     */
    public function get(string $id): array
    {
        return unserialize($this->cache->get("pa".$id));
    }

    public static function populateGet(): void
    {
        $_GET = $GLOBALS["_GET"] = Utils::parseUrlParams($_SERVER["REQUEST_URI"]);
        return;
    }
}
