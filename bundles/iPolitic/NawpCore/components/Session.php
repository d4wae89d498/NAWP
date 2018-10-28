<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 5:24 PM
 */
namespace App\iPolitic\NawpCore\Components;

use Psr\Container\ContainerInterface;

/**
 * Class Session
 * Provide php native session replacement
 * @package App\iPolitic\NawpCore\Components
 */
class Session implements ContainerInterface
{
    /**
     * @var
     */
    public $viewLogger;
    /**
     * @var array
     */
    public $data = [];
    /**
     * @var int
     */
    public $sessionExpireDate;
    /**
     * Session duration before expiration
     */
    public const sessionSecondsDuration = 45 * 60; // 45 min

    /**
     * The session file name when is stored serialmized data
     */
    public const SESSION_FILE = 'sessions.txt';

    /**
     * Session constructor.
     * @param ViewLogger $viewLogger
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct(ViewLogger $viewLogger)
    {
        $this->viewLogger = $viewLogger;
        $this->firstPopulate();
    }

    /**
     * Will populate the session data array using currently stored values if ones
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function firstPopulate() : void
    {
        if ($this->isLoggedIn()) {
            $cachedValue = $this->viewLogger->kernel->sessionCache->get($this->id());
            $this->data = unserialize($cachedValue);
        } else {
            $this->logIn();
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function id(): string
    {
        $uid = (
            isset($_GET["UID"])
            ?
            $_GET["UID"]
            :
            (
            $this->viewLogger->cookiePoolInstance->has("UID") ?
                $this->viewLogger->cookiePoolInstance->get("UID")
                :
                Utils::generateUID()
            )
        );
        $uid = empty(strval($uid)) ? Utils::generateUID() : $uid;
        return $uid;
    }

    /**
     * Will return a session value using a visitor token
     * @param string $key
     * @param string $id
     * @return string
     * @throws \Exception
     */
    public function get($key, $id = ""): string
    {
        return $this->data[$key];
    }

    /**
     * Will return a session value using a visitor token
     * @return array
     * @throws \Exception
     */
    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * Will set a session value using a key
     * @param string $key
     * @param string $value
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function set(string $key, $value): void
    {
        $value = strval($value);
        $this->data[$key] = $value;
        $this->saveChanges();
        return;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id) : bool
    {
        return isset($this->data[$id]);
    }

    /**
     * Will remove an item if exists using its key
     * @param string $key
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remove(string $key) : void
    {
        if ($this->isset($key)) {
            unset($this->data[$key]);
        }
        $this->saveChanges();
    }

    /**
     * Will destroy a session
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function destroy() : void
    {
        $this->viewLogger->kernel->sessionCache->delete($this->id());
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @return bool
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function isLoggedIn() : bool
    {
        return $this->viewLogger->kernel->sessionCache->has($this->id());
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function logIn(): void
    {
        $this->data = [];
        $this->sessionExpireDate = time() + 60 * 60 * 24 * 15;
        $this->saveChanges();
        return;
    }


    /**
     * Will save the current sessions
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function saveChanges() : void
    {
        $this->viewLogger->kernel->sessionCache->set($this->id(), serialize($this->data), time() - $this->sessionExpireDate);
    }
}
