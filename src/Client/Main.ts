import { NoRedirection } from "./NoRedicrection";
import { init } from "./SbAdmin";
import {JsonViewer} from "./DebugBar/jsonViewer";
init();
const noRedirectionInstance: NoRedirection = new NoRedirection();

setTimeout(() => { JsonViewer.refresh(); }, 0 );
