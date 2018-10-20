import * as fs from "fs";

export function isDir(path: string): boolean {
    return fs.lstatSync(path).isDirectory();
}
export function bindArr(str: string, obj: object): string {
    const objKey: string[] = Object.keys(obj);
    for (let i = 0; i < objKey.length; i++) {
        str = str.replace(objKey[i], obj[objKey[i]]);
    }
    return str;
}

export async function isStrInFile(fpath: string, needle: string, linesLimit: number = 15) {
    let wasFound: boolean = false;
    for (let i = 0; i < linesLimit; i++) {
        wasFound = wasFound || ((await getLine(fpath, i)).indexOf(needle) > -1);
    }
    return wasFound;
}

export function getLine(fileName, lineNo): Promise<string> {
    return new Promise(resolve => {
        fs.readFile(fileName, function (err, data) {
            if (err) throw err;

            // Data is a buffer that we need to convert to a string
            // Improvement: loop over the buffer and stop when the line is reached
            const lines = data.toString("utf-8").split("\n");

            if (+lineNo > lines.length) {
                resolve("");
            }

            resolve(lines[+lineNo]);
        });
    });
}

export function replaceInFile(fileName, needle, replace) {
    return fs.writeFileSync(fileName, fs.readFileSync(fileName).toString().replace(needle, replace));
}