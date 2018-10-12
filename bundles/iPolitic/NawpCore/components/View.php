<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 7/21/2018
 * Time: 1:41 AM
 */
namespace App\iPolitic\NawpCore\Components;

use Twig\Loader\ArrayLoader;
use Twig\Environment;

/**
 * Template class.
 */
abstract class View
{
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
     * @param \App\iPolitic\NawpCore\Components\ViewLogger $templateLogger
     * @param array $params
     */
    public function __construct(ViewLogger &$templateLogger, array $params = []) {
        //we  reassign the template logger
        $this->templateLogger = &$templateLogger;
        $id = $this->generatedID = $this->templateLogger->generateTemplateID($this);
        // if the given $params array is non empty, we set all $states elements using $params key and values
        if (count($params) > 0 ) {
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
            foreach($this->states['html_elements'] as $view) {
                array_push($this->states['references'], $view->states['id']);
            }
        }
        $this->templateLogger->setTemplate($id,$this);
    }

    /**
     * Will set the given sate variable to $value
     * @param $name
     * @param $value
     */
    public function setState(string $name, $value): void {
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
    public function getState(string $name) {
        return $this->templateLogger->getTemplate($this->generatedID)["states"][$name];
    }

    /**
     * Will set a template variable
     * @param $name
     * @param $value
     */
    public function set(string $name, $value): void {
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
    public function get(string $name) {
        return $this->templateLogger->getTemplate($this->generatedID)[$name];
    }

    /**
     * Magic function called when a template is rendered to string
     * i.e : in <?=new Template()?> format
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __toString(): string {
        $twig = $this->get("twig");
        $str = 'giventwig';
        $twig = new Environment(new ArrayLoader(array(
            $str => $twig,
        )));
        $m = "beforeRender";
        if(method_exists ($this , $m)) {
            $this->$m();
        }
        $this->templateLogger->setTemplate($this->generatedID, $this);
        $html =  $twig->render($str, $this->get("states"));
        return $html;
    }

}