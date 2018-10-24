import { NoRedirection } from "./tsclient/noRedicrection";
import { init } from "./tsclient/sbAdmin";
init();
console.log("ab123456");
const noRedirectionInstance: NoRedirection = new NoRedirection();
noRedirectionInstance.applyForm();
console.log("noRedirection applied!");
console.log("aaba");