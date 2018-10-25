import * as fs from "fs";
import * as path from "path";
import {bindArr, isDir, isStrInFile, replaceInFile} from "./utils";

async function proceedDir(dirPath: string, deep: number = 0): Promise<any> {
    return new Promise<any>(resolve => {
        // console.log(bindArr("%t | Scanning : %s", {"%s": dirPath, "%t" : "*".repeat(deep)}));
        fs.readdir(dirPath, async (err, files) => {
            for (let file in files) {
                let filePath: string;
                if (!isDir(filePath = path.join(dirPath, files[file]))) {
                    let parts: string[];
                    // if it is a php file
                    if ((parts = files[file].split("."))[parts.length - 1] === "php") {
                        if (!await isStrInFile(filePath, "strict_types")) {
                            replaceInFile(filePath, "<?php", "<?php declare(strict_types=1);");
                            console.log("strict_type added in file : " + filePath);
                        }
                    }
                } else {
                    proceedDir(filePath, deep + 1);
                }
            }
            resolve();
        });
    });
}

async function main() {
    const pathToRoot: string = path.join(__dirname, "..");
    const dirsToScan: string[] = [
        path.join(pathToRoot, "src"),
        path.join(pathToRoot, "bundles")
    ];

    for (let i = 0; i < dirsToScan.length; i++) {
        await proceedDir(dirsToScan[i]);
    }
    return;
}

(async() => { await main(); })();