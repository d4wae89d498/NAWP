<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:41 AM
 */
namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Exceptions\Exception;
use App\Ipolitic\Nawpcore\Exceptions\NoTwigFileFound;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Twig\Loader\ArrayLoader;
use Twig\Environment;

/**
 * Template class.
 */
abstract class View implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    public $logger;
    /**
     * The id of the template
     * @var string
     */
    public $generatedID = '';
    /**
     * The templateLogger reference passed in constructor for logging state updates
     * @var ViewLogger|null
     */
    public $templateLogger = null;
    /**
     * Tpl states
     * @var array|null
     */
    public $states = [];

    /**
     * Template constructor.
     * Will generate a new template id and add generated html and states to $e
     * @param \App\Ipolitic\Nawpcore\Components\ViewLogger $templateLogger
     * @param LoggerInterface $logger
     * @param array $params
     */
    public function __construct(ViewLogger &$templateLogger, LoggerInterface $logger, array $params = [])
    {
        $this->setLogger($logger);
        //we  reassign the template logger
        $this->templateLogger = $templateLogger;
        $id = $this->generatedID = $this->templateLogger->generateTemplateID($this);
        // if the given $params array is non empty, we set all $states elements using $params key and values
        if (count($params) > 0) {
            foreach ($params as $k => $v) {
                $this->states[$k] = $v;
            }
        }
        $this->states['id'] = $id;
        $this->states['references'] = [];
        if (isset($this->states['html_elements'])) {
            /**
             * @var View $view
             */
            foreach ($this->states['html_elements'] as $view) {
                array_push($this->states['references'], $view->states['id']);
            }
        }
        $this->templateLogger->setTemplate($id, $this);
    }

    /**
     * Will set the given sate variable to $value
     * @param $name
     * @param $value
     */
    public function setState(string $name, $value): void
    {
        $tpl = $this->templateLogger->getTemplate($this->generatedID);
        $tpl["states"][$name] = $value;
        $this->templateLogger->setTemplate($this->generatedID, $tpl);
        return;
    }

    /**
     * Will return the given state value using $name
     * @param $name
     * @return mixed
     */
    public function getState(string $name)
    {
        return $this->templateLogger->getTemplate($this->generatedID)["states"][$name];
    }

    /**
     * Will set a template variable
     * @param $name
     * @param $value
     */
    public function set(string $name, $value): void
    {
        $tpl = $this->templateLogger->getTemplate($this->generatedID);
        $tpl[$name] = $value;
        $this->templateLogger->setTemplate($this->generatedID, $tpl);
        return;
    }

    /**
     * Will return a template variable
     * @param $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->templateLogger->getTemplate($this->generatedID)[$name];
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $twig = $this->get("twig");
        $str = 'giventwig';
        $twig = new Environment(new ArrayLoader(array(
            $str => $twig,
        )));
        $m = "beforeRender";
        if (method_exists($this, $m)) {
            $this->$m();
        }
        $this->templateLogger->setTemplate($this->generatedID, $this);
        try {
            $html =  $twig->render($str, $this->get("states"));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        return $html;
    }

    /**
     * Magic function called when a template is rendered to string
     * i.e : in <?=new Template()?> format
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws NoTwigFileFound
     */
    public function twig() : void
    {
        $className = get_class($this);
        $explodedClassName = explode("\\", $className);
        unset($explodedClassName[0]);
        $path = join(DIRECTORY_SEPARATOR, array_merge([__DIR__, "..", "..", "..", "..", "src"], $explodedClassName));
        $path .= ".twig";
        if (file_exists($path)) {
            echo file_get_contents($path);
        } else {
            throw new NoTwigFileFound($path);
        }
    }

    /**
     *     public function twig(): void
    {
    echo file_get_contents(__DIR__ . PATH_SEPARATOR .
    (($a = explode(".",__FILE__))[count($a) - 1]));
    }
     */
}
