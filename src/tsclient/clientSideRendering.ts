import * as $ from "jquery";
import Twig from "twig";

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
            // we re-render it using twig.js and jquery
            const template = Twig.twig({
                data: window["baseTemplates"][this.IdToType(templateDataId)]
            });
            let output: string = template.render(states);
            $("[data-item-id=\"" + this.getMaxType(templateDataId) + "\"]").html(output);
        }
        // else, we try to append this template
        else {
            const nonDigit: string = this.IdToType(templateDataId);
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
                const template = Twig.twig({
                    data: window["baseTemplates"][this.IdToType(templateDataId)]
                });
                let output: string = template.render(states);
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
    public static IdToType(id: string): string {
        let nonDigit: string = id.replace(/[0-9]/g, "");
        return nonDigit;
    }

    /**
     * Will generate a new type id using a template id/type
     * @param {string} type
     * @param {number} minus
     * @returns {string}
     */
    public static getMaxType(type: string): string {
        const nonDigit: string = this.IdToType(type);
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