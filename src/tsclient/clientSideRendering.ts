import * as $ from "jquery";
import {twig, Template} from "twig";
/**
 * The client side rendering class
 */
export class ClientSideRendering {

    /**
     * Will render a template using its data-id, will append or replace using the given id.
     * @param {string} templateDataId
     * @param {object} states
     */
    public static render(templateDataId: string, states: object): void {
        // if this template id is already in memory
        if (typeof (window["templates"][templateDataId]) !== "undefined") {
            console.log(this.TemplateNameToId(templateDataId));
            console.log(this.TemplateNameToId(templateDataId, true));
            // we re-render it using twig.js and jquery
            const template: Template = twig({
                data: window["baseTemplates"][this.TemplateNameToId(templateDataId)]["twig"]
            });
            $("[data-item-id=\"" + this.getMaxType(templateDataId) + "\"]").html(template.render(states));
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
                    data: window["baseTemplates"][this.TemplateNameToId(templateDataId)]
                });
                let output: any = template.render(states);
                $("[data-item-id=\"" + this.getMaxType(templateDataId) + "\"]").after(output);
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
}