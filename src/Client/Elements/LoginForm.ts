const $ = window["$"];
import {IElement} from "./../NoRedicrection";

export default class LoginForm implements IElement {
    /**
     * form states
     */
    public currentTab: number = 0;
    public lastPage: string = "";
    /**
     * Called at each page change
     */
    public refreshTick(): void {
        this.currentTab = 0;
        // if we are on login / register page
        if ((window.location.href.indexOf("admin/login") > -1) && (this.lastPage !== window.location.href)) {
            $("[name=\"accessTypeRadio\"]").on("click",
                (e: Event) => {
                    let shouldToggle: boolean = false;
                    let eClassName: string;
                    switch (eClassName = $(e.target).attr("class")) {
                        case "loginRadio":
                            if (this.currentTab > 0) {
                                this.currentTab = 0;
                                shouldToggle = true;
                            }
                            break;
                        case "registerRadio":
                            if (this.currentTab < 1) {
                                this.currentTab = 1;
                                shouldToggle = true;
                            }
                            break;
                        default:
                            break;
                    }
                    if (shouldToggle) {
                        $("#registrationSection").slideToggle();
                    }
                }
            );
        }
        this.lastPage = window.location.href;
    }
}