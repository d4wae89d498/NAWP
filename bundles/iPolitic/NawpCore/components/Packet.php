<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/6/2018
 * Time: 2:00 PM
 */
namespace App\iPolitic\NawpCore\components;

use App\iPolitic\NawpCore\exceptions\NAWPNotFoundExceptionInterface;
use App\iPolitic\NawpCore\Kernel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The packet Class
 * Class Packet
 * @package App\iPolitic\NawpCore\components
 */
class Packet implements \ArrayAccess, ContainerInterface
{
    public const DEFAULT_OBJ = [];
    /**
     * The socket adapter
     * @var PacketAdapter
     */
    private $packetAdapter;
    /**
     * The packets components
     * @var array
     */
    private $container = ["data" => [], "url" => "", "clientVar" => "", "templates" => [], "cookies" => [], "http_referer" => null, "original_client_var"];
    /**
     * @var ServerRequestInterface
     */
    public $request;
    /**
     * Packet constructor.
     * @param Kernel $kernel
     * @param ServerRequestInterface $request
     * @param array $data
     * @param bool $decryptClientVar
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Kernel &$kernel, ServerRequestInterface &$request, array $data = self::DEFAULT_OBJ, bool $decryptClientVar = false)
    {
        $this->request &= $request;
        $this->packetAdapter = new PacketAdapter($kernel->packetAdapterCache);
        $nData = [];
        if (gettype($data) === "array") {
            $nData = $data;
        }
        foreach ($this->container as $k => $v) {
            if (isset($nData[$k])) {
                $valueAddedInContainer = $nData[$k];
                // decrypt packet adapter file
                if ($k === "clientVar" && $decryptClientVar) {
                    $this->container["original_client_var"] = $valueAddedInContainer;
                    if ($kernel->packetAdapterCache->has($valueAddedInContainer)) {
                        $valueAddedInContainer = $this->packetAdapter->get($valueAddedInContainer);
                    }
                }
                // decrypt json cookies
                elseif ($k === "cookies") {
                    $valueAddedInContainer = [];
                    $cookieSplit = explode("; ", $nData[$k]);
                    for ($i = 0; $i < count($cookieSplit); $i++) {
                        $cur = explode("=", $cookieSplit[$i]);
                        $valueAddedInContainer[$cur[0]] = isset($cur[1]) ? $cur[1] : "";
                    }
                }
                $this->container[$k] = $valueAddedInContainer;
            }
        }
    }

    /**
     * Convert the object to a normal array
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this->container;
    }

    /**
     * Will set a value using a key
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Will check if a key exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Will delete a value using a key
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Will return a value using a key
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Will use the packet adaptor to make socket packets similar to http ones
     */
    public function useAdaptor(): Packet
    {
        foreach ($this->container["data"] as $key => $array) {
            if (isset($array["name"]) && isset($array["value"])) {
                $this->container["data"][$array["name"]] = $array["value"];
                unset($this->container["data"][$key]);
            }
        }
        // file name that contains needed sessions data
        unset($this->container["data"]["clientVar"]);
        $this->container["data"]["originalClientVar"] = $this->container["original_client_var"];
        $_POST = $this->container["data"];
        // setting php globals
        $_SERVER["REQUEST_URI"] = $this->container["url"];
        $_SERVER["HTTP_REFERER"] = $this->container["http_referer"];
        PacketAdapter::populateGet();
        $GLOBALS["_POST"] = $_POST;
        $GLOBALS["_GET"] = $_GET;
        $GLOBALS["_SERVER"] = $_SERVER;
        return $this;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws NAWPNotFoundExceptionInterface
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->container[$id];
        } else {
            throw new NAWPNotFoundExceptionInterface();
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->container[$id]);
    }
}
