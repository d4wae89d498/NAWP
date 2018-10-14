<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/6/2018
 * Time: 2:00 PM
 */
namespace App\iPolitic\NawpCore\components;

/**
 * The packet Class
 * Class Packet
 * @package App\iPolitic\NawpCore\components
 */
class Packet implements \ArrayAccess {

    public const DEFAULT_OBJ = [];

    /**
     * The socket adapter
     * @var PacketAdapter
     */
    private $socketAdapter;
    /**
     * The packets components
     * @var array
     */
    private $container = ["data" => [], "url" => "", "clientVar" => "", "templates" => [], "cookies" => []];

    private $originalClientVar;

    /**
     * Packet constructor.
     * @param array $data
     * @param bool $decryptClientVar
     * @throws \Exception
     */
    public function __construct(array $data = self::DEFAULT_OBJ, bool $decryptClientVar = false) {
        $this->socketAdapter = new PacketAdapter();
        $nData = [];
        if (gettype($data) === "array")
        {
            $nData = $data;
        }
        foreach ($this->container as $k => $v) {
            if(isset($nData[$k])){
                $valueAddedInContainer = $nData[$k];
                // decrypt packet adapter file
                if ($k === "clientVar" && $decryptClientVar) {
                    $this->originalClientVar = $valueAddedInContainer;
                    $valueAddedInContainer = $this->socketAdapter->readFile($valueAddedInContainer);
                }
                // decrypt json cookies
                elseif ($k === "cookies") {
                    $valueAddedInContainer = [];
                    $cookieSplit = explode("; ", $nData[$k]);
                    for($i = 0; $i < count($cookieSplit); $i++) {
                        $cur = explode("=",$cookieSplit[$i]);
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
    public function toArray(): array {
        return (array) $this->container;
    }

    /**
     * Will set a value using a key
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) {
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
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    /**
     * Will delete a value using a key
     * @param mixed $offset
     */
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    /**
     * Will return a value using a key
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Will use the packet adaptor to make socket packets similar to http ones
     */
    public function useAdaptor(): Packet {
        // file name that contains needed sessions data
        $originalClientVar = $this->container["clientVar"];
        $this->container["data"]["clientVar"] = $originalClientVar;
        $this->container["data"]["originalClientVar"] = $this->originalClientVar;
        // setting php globals
        $_SERVER["REQUEST_URI"] = $this->container["url"];
        $_GET = parse_url($_SERVER["REQUEST_URI"]);
        $_POST = $this->container["data"];
        $_POST["templates"] = $this->container["templates"];
        $GLOBALS["_POST"] = $_POST;
        $GLOBALS["_GET"] = $_GET;
        return $this;
    }
}
