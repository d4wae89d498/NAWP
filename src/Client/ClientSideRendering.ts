const $: JQueryStatic  = window["$"];
import {twig} from "twig";
import {NoRedirection} from "./NoRedicrection";
import * as morphdom from "morphdom";
/*
 * The client side rendering class
 */
export class ClientSideRendering {
    public static MAX_TPL_DEEP = 99;
    public noRedirection: NoRedirection;
    public static noRedirection: NoRedirection;
    public constructor(noRedirection: NoRedirection) {
        this.noRedirection = noRedirection;
        ClientSideRendering.noRedirection = noRedirection;
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
            const baseTpl: any =  window["baseTemplates"].find((e) => {
                return e.generatedID === this.TemplateNameToId(templateDataId, true);
            });
            // we re-render it using twig.js and jquery
            const template: any = twig({
                data:   baseTpl.twig
            });
            let rendered = await template.render(states);
            if (returnAsString) {
                return rendered;
            }
            const selectedElement = $("[data-id=\"" + templateDataId + "\"]");
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
                    data:  window["baseTemplates"].find((e) => {
                        return e.generatedID === this.TemplateNameToId(templateDataId, true);
                    }).twig
                });
                let rendered = await template.render(states);
                if (returnAsString) {
                    return rendered;
                }
                const selectedElement = $("[data-id=\"" + this.getMaxType(templateDataId) + "\"]");
                selectedElement.after(rendered);

            }
        }
        return;
    }

    /**
     * Convert id to type
     * @param {string} id
     * @param {boolean} idOnly
     * @returns {string}
     * @constructor
     */
    public static TemplateNameToId(id: string, idOnly: boolean = false): string {
        const generatedID: string = id.replace(/[0-9]/g, "") + "0";
        if (idOnly) {
            return generatedID;
        }
        return window["baseTemplates"].findIndex(function(element) {
            return element.generatedID === generatedID;
        });
    }

    /**
     * Will generate a new type id using a template id/type
     * @param {string} type
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
     * Will read current value of <meta data-url="..." content="READING HERE"> tag
     */
    public static getCurrentUrl(): string {
        const p: any = document.head.querySelector("[name~=data-url][content]");
        return p.content;
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
                            if (typeof states[tplKey]["references"] !== "undefined" && states[tplKey]["references"].hasOwnProperty(referenceKey)) {
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
                    window["templates"][tplKey] = {states: states[tplKey]};
                    if (deep === 0) {
                        const attrIdSelector = $("[data-id=\"" + tplKey + "\"]");
                        let templateSelector: any = attrIdSelector.length > 0 ?
                            attrIdSelector :
                            $("head");
                        const a = document.createElement(templateSelector.prop("nodeName"));
                        a.innerHTML = generated;
                        a.setAttribute("data-id" , tplKey);
                        if (templateSelector.prop("nodeName") === "HEAD" || (typeof templateSelector[0] !== "undefined" && templateSelector[0].innerHTML !== a.innerHTML)) {
                            // using morphdom diffing for updating only the minimal data
                            const oldUrl = this.getCurrentUrl();
                            morphdom(templateSelector[0], a);
                            if (templateSelector.prop("nodeName") === "HEAD") {
                                const newUrl = this.getCurrentUrl();
                                // if url changed we push it to update the browser URL
                                if (oldUrl !== newUrl) {
                                    window.history.pushState(newUrl, document.title, newUrl);
                                }
                            }
                        }
                        ClientSideRendering.noRedirection.init(states);
                    }
                }
            }
            return generatedString;
        }
    }
}