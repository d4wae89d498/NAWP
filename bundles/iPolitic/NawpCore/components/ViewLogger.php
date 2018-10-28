<?php declare(strict_types=1);
namespace App\iPolitic\NawpCore\Components;

use App\iPolitic\NawpCore\Kernel;

/**
 * ViewLogger will store all the data given to the template class
 *
 * Its goal is to generate a javascript code will all the data passed to the template,
 * So that the client can access server templates.
 *
 * @version 1.0
 * @author fauss
 */
class ViewLogger
{
    public const HTML_STATES_PREFIX = "html_elements";
    public const DEFAULT_REQUEST_TYPE = "GET";
    /**
     * @var array|null
     */
    public $array = [];
    /**
     * @var array
     */
    public $renderedTemplates = [];
    /**
     * @var string
     */
    public $requestType = "";

    public $cookies = [];

    /**
     * @var bool
     */
    public $areCookieEnabled = false;
    public $cookieEnabledLocked = false;
    public $kernel;
    public $sessionInstance = null;
    /**
     * ViewLogger constructor.
     * @param Kernel $kernel
     * @param null $array
     * @param Packet|null $packet
     * @param string $requestType
     */
    public function __construct(Kernel &$kernel, $array = null, $packet = null, string $requestType = self::DEFAULT_REQUEST_TYPE)
    {
        $this->kernel = $kernel;
        $this->requestType = $requestType;
        if ($array !== null) {
            $this->array = $array;
        }
        // if we are in a socket context
        if ($requestType === "SOCKET" && $packet !== null) {
            // and if cookies were passed
            if (isset($packet["cookies"]) && is_array($packet["cookies"])) {
                foreach ($packet["cookies"] as $k => $v) {
                    Cookie::set($this, new Cookie($k, $v));
                }
            }
        }
        $this->areCookieEnabled = Cookie::areCookieEnabled($this);
        $this->cookieEnabledLocked = true;
        Cookie::setTestCookie($this);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        // if we are retrieving the cookie array
        if ($name === "cookies") {
            // we check iuf $_COOKIES are array
            if (isset($_COOKIE) && is_array($_COOKIE) && is_array($this->cookies)) {
                // if yes we set it in $this->cookies array
                foreach ($_COOKIE as $k => $v) {
                    $this->cookies[$k] = $v;
                }
            }
        }
        return $this->$name;
    }

    /**
     * Array that contains all the templates
     * @var mixed
     */
    public $templatesData = [];
    /**
     * All the custom template methods, and their associated tags
     * @var mixed
     */
    public static $templatesFields = ["twig" => null];

    /**
     * Will assign a template, using a template instance, or a template data array
     * @param string $templateID
     * @param mixed $template
     */
    public function setTemplate(string $templateID, $template): void
    {
        if (gettype($template) === 'array') {
            $this->templatesData[$templateID] = $template;
        } else {
            foreach (self::$templatesFields as $k => $v) {
                if (is_callable([$template, $k])) {
                    $this->templatesData[$templateID][$k] = Utils::ocb(function () use ($template, $k) {
                        $template->$k();
                    });
                    if ($v !== null) {
                        $this->templatesData[$templateID][$k] = Utils::strInTag($this->templatesData[$templateID][$k], $v);
                    }
                }
            }
            $this->templatesData[$templateID]["states"] = $template->states;
        }
    }

    /**
     * Will return a template data array by using tempalte ID
     * @param string $templateID
     * @return mixed
     */
    public function getTemplate(string $templateID): array
    {
        return $this->templatesData[$templateID];
    }

    /**
     * Will generate a new template ID for the given template instance
     * @param View $view
     * @return string
     */
    public function generateTemplateID(View $view): string
    {
        $tplName = get_class($view);
        $isAvailable = false;
        $i = 0;
        $tyName = "";
        while (!$isAvailable) {
            $tyName = $tplName . $i;
            if (!isset($this->templatesData[$tyName])) {
                $isAvailable = true;
            }
            $i++;
        }
        return str_replace("\\", "_", $tyName);
    }

    /**
     * Will return all templates
     * @return array
     */
    public function getStates(): array
    {
        // will remove all html_ like states and replace
        $array = [];
        foreach ($this->templatesData as $id => $template) {
            $array[$id] = [];
            if (isset($template['states']) && is_array($template['states'])) {
                foreach ($template['states'] as $k => $v) {
                    if ($k !== self::HTML_STATES_PREFIX) {
                        $array[$id][$k] = $v;
                        //echo " template " . (string) $k . " added for " . $id . PHP_EOL;
                    }
                }
            }
        }
        return $array;
    }

    /**
     * Will generate vanilla JS should be rendered in page footer
     * @return string
     */
    public function generateJS(): string
    {
        $packetAdapter = new PacketAdapter($this->kernel->packetAdapterCache);
        $output = Utils::ocb(function () use (&$packetAdapter) {
            ?>
            window['templates'] = [];
            window['baseTemplates'] = [];
            <?php $this->renderedTemplates = [];
            foreach ($this->getStates() as $id => $states):
                $this->renderedTemplates[$id] = $states; ?>
                if (typeof window['templates'][<?=json_encode($id) ?>] === 'undefined') {
                    window['templates'][<?=json_encode($id) ?>] = {
                        states: (<?=json_encode(["states" => $states]) ?>)["states"]
                    };
                }
            <?php endforeach; ?>
            <?php foreach ($this->kernel->viewCollection as $k => $v):
                /**
                 * @var $v View
                 */
                if (isset($v->states) && isset($this->array[$k])): ?>
                    window['baseTemplates']['<?=$k?>'] = {
                        generatedID: (<?=json_encode(["generatedID" => $v->generatedID])?>)["generatedID"],
                        twig: (<?=json_encode(["twig" => $this->array[$k]]) ?>)["twig"],
                        states: (<?=json_encode(["states" => $v->states])?>)["states"]
                    };
                <?php endif;
            endforeach; ?>
            window['clientVar'] = '<?=$packetAdapter->storeAndGet($this)?>';
            <?php
        });
        return $output;
    }

    /**
     * Will generate vanilla CSS should be rendered in page footer
     * @return string
     */
    public function generateCSS(): string
    {
        return Utils::ocb(function () {
            ?>
            <?php  foreach ($this->templatesData as $template): ?>
                <?=$template["css"][0]?>
            <?php endforeach; ?>
        <?php
        });
    }

    /**
     * @return \App\iPolitic\NawpCore\Components\Session
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getSession(): Session
    {
        if ($this->sessionInstance === null) {
            $this->sessionInstance =  new Session($this);
        }
        return $this->sessionInstance;
    }
}
