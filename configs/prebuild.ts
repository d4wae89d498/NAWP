import * as fs from "fs";
import * as dotenv from "dotenv";
import * as path from "path";
import * as copydir from "copy-dir";

/***
 * Pre - build script
 *  this file parse env file path if exists,
 *  then copy all needed dist npm assets to the project web root
 */

const envFilePath: string = path.join(__dirname, ".env");
const pathToRoot: string = path.join( ... [__dirname, ".."]);
const pathToWebVendor: string = path.join(pathToRoot, "public", "vendors");
const isDir = (path: string): boolean => { return fs.lstatSync(path).isDirectory(); };
const bindArr = (str: string, obj: object): string => {
    const objKey: string[] = Object.keys(obj);
    for (let i = 0; i < objKey.length; i++) {
        str = str.replace(objKey[i], obj[objKey[i]]);
    } return str; };
if (fs.existsSync(path.join(__dirname, ".env"))) {
    dotenv.config({ path: envFilePath });
    const packagesList: string[] = (process.env.VENDORS_PACKAGES.split(","));
    console.log(bindArr("Starting copying dist files from %s NPM packages ... ", {"%s": packagesList.length}));
    for (let i = 0; i < packagesList.length; i++) {
        let fpath: string;
        if (isDir(fpath = path.join(pathToRoot, "node_modules", packagesList[i], "dist"))) {
            copydir.sync(fpath, path.join(pathToWebVendor, packagesList[i]));
            console.log(bindArr("#%no / %max [SUCCESS] Dist folder from path %path copied in public/vendors",
                {"%no": i + 1, "%path": fpath, "%max": packagesList.length}));
        } else {
            console.log(bindArr("#%no / %max [FAILED] No dist folder was found in path %path ",
            {"%no": i + 1, "%path": fpath, "%max": packagesList.length}));
        }
    }
} else {
    console.log("It looks like you moved the .env file. Please write its new path in copy_vendors.ts file");
}
