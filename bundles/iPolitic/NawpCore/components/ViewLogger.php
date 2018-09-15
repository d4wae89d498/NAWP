<?php
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
    public $renderedTemplates = [];
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
    public function setTemplate(string $templateID, $template): void {
        if(gettype($template) === 'array') {
            $this->templatesData[$templateID] = $template;
        } else {
            foreach(self::$templatesFields as $k => $v) {
                if (is_callable(array($template, $k))) {
                    $this->templatesData[$templateID][$k] = Utils::ocb(function() use ($template, $k) {
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
    public function getTemplate(string $templateID): array {
        return $this->templatesData[$templateID];
    }

    /**
     * Will generate a new template ID for the given template instance
     * @param Template $template
     * @return string
     */
    public function generateTemplateID(View $template): string {
        $tplName = get_class($template);
        $isAvailable = false;
        $i = 0;
        $tyName = "";
        while(!$isAvailable) {
            $tyName = $tplName . $i;
            if (!isset($this->templatesData[$tyName])) {
                $isAvailable = true;
            }
            $i++;
        }
        return $tyName;
    }

    public function getTemplates(): array {
        $array = [];
        foreach($this->templatesData as $id => $template) {
            if(explode("_", $id)[0] !== "html") {
                $array[$id] = ["states" => $template["states"]];
            }
        }
        return $array;
     }

    /**
     * Will generate vanilla JS should be rendered in page footer
     * @return string
     */
    public function generateJS(): string {
        $packetAdapter = new PacketAdapter();
        return Utils::ocb(function() use (&$packetAdapter) { ?>
            window['templates'] = [];
            window['baseTemplates'] = [];
            <?php $this->renderedTemplates = [];
            foreach($this->templatesData as $id => $template) {
                $this->renderedTemplates[$id] = $template; ?>
            if (typeof window['templates'][<?=json_encode($id)?>] === 'undefined') {
                window['templates'][<?=json_encode($id)?>] = {
                    twig: (<?=json_encode(["twig" => $template["twig"]])?>)["twig"],
                    states: (<?=json_encode(["states" => $template["states"]])?>)["states"]
                };
            }
            <?php } ?>
            <?php foreach((Kernel::getKernel())->viewCollection as $k => $v) {
            /**
             * @var $v View
             */ ?>
            window['baseTemplates']['<?=$k?>'] = {
                generatedID: (<?=json_encode(["generatedID" => $v->generatedID])?>)["generatedID"],
                twig: (<?=json_encode(["twig" => $v->get('twig')]) ?>)["twig"],
                states: (<?=json_encode(["states" => $v->states])?>)["states"]
            };
            <?php } ?>
            window['clientVar'] = '<?=$packetAdapter->storeAndGet()?>';
            <?php
        });
    }


    /**
     * Will generate vanilla CSS should be rendered in page footer
     * @return string
     */
    public function generateCSS(): string {
        return Utils::ocb(function() { ?>
            <?php  foreach($this->templatesData as $template): ?>
                <?=$template["css"][0]?>
            <?php endforeach; ?>
        <?php });
    }
}