import { runPlugin } from "./ProfilerBarPlugin";
import { NoRedirection } from "./NoRedicrection";
import { init } from "./SbAdmin";
runPlugin();
init();
const noRedirectionInstance: NoRedirection = new NoRedirection();

