<?php declare(strict_types=1);
namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Kernel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Workerman\Protocols\Http;

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
     * All the custom template methods, and their associated tags
     * @var mixed
     */
    public static $templatesFields = ["twig" => null];
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
    /**
     * @var array
     */
    public $cookies = [];
    /**
     * @var bool
     */
    public $areCookieEnabled = false;
    /**
     * @var bool
     */
    public $cookieEnabledLocked = false;
    /**
     * @var Kernel
     */
    public $kernel;
    /**
     * Array that contains all the templates
     * @var mixed
     */
    public $templatesData = [];
    /**
     * @var ServerRequestInterface
     */
    public $request;
    /**
     * @var Session
     */
    public $sessionInstance;
    /**
     * @var CookiePool
     */
    public $cookiePoolInstance;

    /**
     * ViewLogger constructor.
     * @param Kernel $kernel
     * @param ServerRequestInterface $request
     * @param null $array
     * @param null $packet
     * @param string $requestType
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Kernel &$kernel, ServerRequestInterface &$request, $array = null, $packet = null, string $requestType = self::DEFAULT_REQUEST_TYPE)
    {
        $this->kernel = $kernel;
        $this->cookiePoolInstance = new CookiePool($this);
        $this->sessionInstance = new Session($this);
        $this->request = $request;
        $this->requestType = $requestType;
        if ($array !== null) {
            $this->array = $array;
        }
        // if we are in a socket context
        if ($requestType === "SOCKET" && $packet !== null) {
            // and if cookies were passed
            if (isset($packet["cookies"]) && is_array($packet["cookies"])) {
                foreach ($packet["cookies"] as $k => $v) {
                    $this->cookiePoolInstance->set(new Cookie($k, $v));
                }
            }
        }
        $this->areCookieEnabled =  $this->cookiePoolInstance->areCookieEnabled();
        $this->cookieEnabledLocked = true;
        $this->cookiePoolInstance->setTestCookie();
    }

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
     * @param string $className
     * @param arra $states
     * @return View
     */
    public function resurciveHtmlElementsInstancier($className, $states, $deep = 0) : View
    {
        $output = [];
        foreach ($states as $k => $v) {
            $output[$k] = $v;
        }
        $cnt = 0;
        if (isset($states["html_elements"])) {
            foreach ($states["html_elements"] as $x => $y) {
                foreach ($y as $subClassName => $subClassStates) {
                    $tmpClassName = $subClassName;
                    $tmpStates = $subClassStates;
                    unset($output["html_elements"][$subClassName]);
                    $output["html_elements"][$cnt++] = $this->resurciveHtmlElementsInstancier($tmpClassName, $tmpStates, $deep + 1);
                }
            }
        }
        return new $className($this, $this->kernel->logger, $output);
    }

    /**
     * Will render a page using given informations
     * @param mixed ...$elements
     * @return string
     */
    public function render(... $elements) : string
    {
        $output = "<!DOCTYPE html><html lang=\"en\">";
        $cnt = 0;
        foreach ($elements as $elementData) {
            $className = array_keys($elementData)[0];
            $states = $elementData[$className];
            $output .= ($this->resurciveHtmlElementsInstancier($className, $states))->render();
            if ($cnt++ === 0) {
                $output .= "<body>";
            }
        }
        $output .= "</body></html>";
        return $output;
    }

    /**
     * Will render an element using given informations
     * @param array $elements
     * @return string
     */
    public function renderOne(array $elements) : string
    {
        //  var_dump($elements);
        $output = "";
        $className = array_keys($elements)[0];
        $states = $elements[$className];
        // var_dump($className);
        $output .= ($this->resurciveHtmlElementsInstancier($className, $states))->render();
        return $output;
    }

    /**
     *  Will redirect the http or the socket response
     * @param ResponseInterface $response
     * @param string $url
     * @param array $args
     * @throws \iPolitic\Solex\RouterException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function redirectTo(ResponseInterface &$response, string $url, array $args): void
    {
        $_SERVER["REQUEST_URI"] = $url;
        if (strtolower($this->requestType) !== "socket") {
            Http::header("Location: " . ($this->request->getServerParams()["REQUEST_URI"]));
        } else {
            $this->kernel->handle(
                $this->request,
                $response,
                $this->requestType,
                null,
                $args,
                $viewLogger
            );
        }
    }
}
