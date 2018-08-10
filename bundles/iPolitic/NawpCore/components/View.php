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
     * @param null $params
     */
    public function __construct(ViewLogger &$templateLogger, $params) {
        //we  reassign the template logger
        $this->templateLogger = &$templateLogger;
        $id = $this->generatedID = $this->templateLogger->generateTemplateID($this);
        $this->states = ((count($params) > 0 ) ? $params : $this->states);
        $this->templateLogger->setTemplate($id,$this);
    }

    /**
     * Will set the given sate variable to $value
     * @param $name
     * @param $value
     */
    public function setState($name, $value) {
        $tpl = $this->templateLogger->getTemplate($this->generatedID);
        $tpl["states"][$name] = $value;
        $this->templateLogger->setTemplate($this->generatedID, $tpl);
    }

    /**
     * Will return the given state value using $name
     * @param $name
     * @return mixed
     */
    public function getState($name) {
        return $this->templateLogger->getTemplate($this->generatedID)["states"][$name];
    }

    /**
     * Will set a template variable
     * @param $name
     * @param $value
     */
    public function set($name, $value) {
        $tpl = $this->templateLogger->getTemplate($this->generatedID);
        $tpl[$name] = $value;
        $this->templateLogger->setTemplate($this->generatedID, $tpl);
    }

    /**
     * Will return a template variable
     * @param $name
     * @return mixed
     */
    public function get($name) {
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
    public function __toString() {
        $twig = $this->get("twig");
        $str = 'giventwig';
        $twig = new Environment(new ArrayLoader(array(
            $str => $twig,
        )));
        $html =  $twig->render($str, $this->get("states"));
        return $html;
    }

}