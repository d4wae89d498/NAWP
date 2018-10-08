import * as $ from "jquery";
import {twig} from "twig";
import {NoRedirection} from "./noRedicrection";
/**
 * The client side rendering class
 */
export class ClientSideRendering {
    public static MAX_TPL_DEEP = 99;
    public noRedir: NoRedirection;
    public static noRedir;
    public constructor(noRedir: NoRedirection) {
        this.noRedir = noRedir;
        ClientSideRendering.noRedir = noRedir;
    }
    /**
     * Will reshow the twig previously hidden
     * @param {string} str
     * @return string
     */
    public static showTwigIn(str: string): string {
        return str
            .split("²==//") . join ( "}")
            .split("==²//") . join ( "{");
    }

    /**
     * Will render a template using its data-id, will append or replace using the given id.
     * @param {string} templateDataId
     * @param states
     * @param {boolean} returnAsString
     * @returns {Promise<string>}
     */
    public static async render(templateDataId: string, states: any, returnAsString: boolean = false): Promise<string> {
        window["csr"] = this;
        // if this template id is already in memory
        if (window["templates"].hasOwnProperty(templateDataId)) {
            console.log(this.TemplateNameToId(templateDataId));
            console.log(this.TemplateNameToId(templateDataId, true));
            const baseTpl: any =  window["baseTemplates"].find((e) => {
                return e.generatedID === this.TemplateNameToId(templateDataId, true);
            });
            // we re-render it using twig.js and jquery
            const template: any = twig({
                data: baseTpl.twig
            });
            console.log(baseTpl);
            let rendered = await template.render(states);
            if (returnAsString) {
                return rendered;
            }
            const selectedElement = $("[data-id=\"" + templateDataId + "\"]");
            console.log("length : ( 1 ) : " + selectedElement.length + " states : " + Object.keys(states).length + " data : " +
                baseTpl.twig.length);
            selectedElement[0].outerHTML = rendered;

        }
        // else, we try to append this template
        else {
            const nonDigit: string = this.TemplateNameToId(templateDataId, true);
            let maxId: number = 0;
            for (let tpl in window["templates"]) {
                if (window["templates"].hasOwnProperty(tpl)) {
                    if (tpl.substr(0, tpl.length - (tpl.length - nonDigit.length)) === nonDigit) {
                        maxId++;
                    }
                }
            }
            if (maxId > 0) {
                // append here
                const template = twig({
                    data: window["baseTemplates"].find((e) => {
                        return e.generatedID === this.TemplateNameToId(templateDataId, true);
                    }).twig
                });
                let rendered = await template.render(states);
                if (returnAsString) {
                    return rendered;
                }
                const selectedElement = $("[data-id=\"" + this.getMaxType(templateDataId) + "\"]");
                selectedElement.after(rendered);
                console.log("length : ( 1 ) : " + selectedElement.length);

            }
        }
        return;
    }

    /**
     * Convert id to type
     * @param {string} id
     * @returns {string}
     * @constructor
     */
    public static TemplateNameToId(id: string, idOnly: boolean = false): string {
        const generatedID: string = id.replace(/[0-9]/g, "") + "0";
        if (idOnly) {
            return generatedID;
        }
        const foundIndex = window["baseTemplates"].findIndex(function(element) {
            return element.generatedID === generatedID;
        });
        return foundIndex;
    }

    /**
     * Will generate a new type id using a template id/type
     * @param {string} type
     * @param {number} minus
     * @returns {string}
     */
    public static getMaxType(type: string): string {
        const nonDigit: string = this.TemplateNameToId(type, true);
        let maxId: number = 0;
        for (let tpl in window["templates"]) {
            if (window["templates"].hasOwnProperty(tpl)) {
                if (tpl.substr(0, tpl.length - (tpl.length - nonDigit.length)) === nonDigit) {
                    let digit: number = parseInt(tpl.replace(/\D/g, ""));
                    maxId = digit > maxId ? digit : maxId;
                }
            }
        }
        return nonDigit + maxId.toString();
    }

    /**
     * Will render recursivly the given templates states object without
     * @param {object} states
     * @param {number} deep
     * @param {object} renderedArray
     * @constructor
     */
    public static async RenderStates(states: object, deep: number = 0, renderedArray: object = []): Promise<string> {
        let generatedString = "";
        // TODO : remove non anwsered html elements
        // TODO : set window['templates'] values accordely
        if  (deep > ClientSideRendering.MAX_TPL_DEEP) {
            return "";
        } else {
            for (let tplKey in states) {
                if (states.hasOwnProperty(tplKey) && (tplKey !== "App_Views_Elements_Admin_Footer0")) {
                    if  (states[tplKey].hasOwnProperty("references")     &&
                        (Object.keys(states[tplKey]["references"])).length > 0 )  {
                        states[tplKey]["html_elements"] = [];
                        for (let referenceKey in states[tplKey]["references"]) {
                            if (typeof renderedArray[states[tplKey]["references"][referenceKey]] === "undefined") {
                                renderedArray[states[tplKey]["references"][referenceKey]] = "BONJOUR";
                                let statesArray: object = {};
                                statesArray[states[tplKey]["references"][referenceKey]] = states[states[tplKey]["references"][referenceKey]];
                                states[tplKey]["html_elements"].push(await ClientSideRendering.RenderStates (
                                    statesArray,
                                    ++deep,
                                    renderedArray
                                ));
                            }
                        }
                    }
                    let generated = "";
                    generated = await ClientSideRendering.render(tplKey, states[tplKey], true);
                    generatedString += generated;
                    console.log("rendering : " + tplKey);
                    window["templates"][tplKey] = {states: states[tplKey]};
                    if (deep === 0) {
                        console.log(generated);
                        let templateSelector = $("[data-id=\"" + tplKey + "\"]");
                        const a = $(generated);
                        if (templateSelector.html() !== a.html()) {

                          /*  const VNode = require("vtree/vnode");
                            const diff = require("vtree/diff");

                            const createElement = require("vdom/create-element");
                            const patch = require("vdom/patch");

                            const rightNode =  $(generated)[0];
                            const rootNode = $("[data-id=\"" + tplKey + "\"]")[0];
                            const patches = diff(rootNode, rightNode);
                            patch(rootNode, patches);*/
                            // todo : add PWA cache system and recursive dom comparaison before update
                        }
                        templateSelector.deepReplace(a.html());
                        ClientSideRendering.noRedir.init();
                    }
                }
            }
            return generatedString;
        }
    }
}