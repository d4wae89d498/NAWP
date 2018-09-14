import * as $ from "jquery";

export class ClientSideRendering {
    /**
     * Will render a string using its data-id
     * @param {string} templateDataId
     * @param {object} states
     */
    public static render(templateDataId: string, states: object): void {
        if (typeof (window["templates"][templateDataId]) !== "undefined") {
            $("[data-item-id=\"" + templateDataId + "\"]").after("");
            // re-render window["templates"][template] with states
        } else {
            let nonDigit: string = templateDataId.replace(/[0-9]/g, "");
            let maxId: number = 0;
            const matches: Array<string> = [];
            // for each current templates WITH THE SAME TYPE as the one given
            // pushing its key to the matches array
            for (let tpl in window["templates"]) {
                if (window["templates"].hasOwnProperty(tpl)) {
                    let digit: number = parseInt(tpl.replace(/\D/g, ""));
                    maxId = digit > maxId ? digit : maxId;
                    if (tpl.substr(0, tpl.length - (tpl.length - nonDigit.length)) === nonDigit) {
                        matches.push(tpl);
                    }
                }
            }
           if (Object.keys(matches).length > 1) {
                const newId = maxId + 1;
                const newTplKey = nonDigit + newId;
                // append here
                $("[data-item-id=\"" + newTplKey + "\"]").after("");
           }
        }
        return;
    }
}