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
    private $container = ["data" => [], "url" => "", "clientVar" => ""];

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
                if ($k === "clientVar" && $decryptClientVar) {
                    $this->container[$k] = $this->socketAdapter->getDecryptedDServer($nData[$k]);
                } else {
                    $this->container[$k] = $nData[$k];
                }
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
        foreach ($this->container["clientVar"] as $k => $v) {
            if ($k === "REQUEST_URI") {
                $v = $this->container["url"];
            }
            if ($k === "data") {
                $v = $this->container['data'];
            }
            $_SERVER[$k] = $v;
            $GLOBALS["_SERVER"][$k] = $v;
        }
        return $this;
    }
}
