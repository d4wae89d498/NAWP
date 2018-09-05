## **IPOLITIC/NAWP** 
###### A simple but powerful future-proof and high performance network oriented framework. It uses combo of both modern typescript and php to deliver amazing performances. 

To install the project, simply run `npm install` or `yarn` in your terminal.
Then, you'll have to create a `configs/.env` file using the `configs/.env.dist` one as sample.

If the project is already using a database, you'll have to attach it using SQL Server Management Studio or SQL Operations Studio. Else you'll have to design your database and generate your models with Atlas.

Project database is available here (.MDF / .LDF files)  :  http://google.com 

## Minimum requirements 
- PHP >= 7.2.0 
- NodeJS  >= 10.0.0 
- Supported os : Windows || MacOS || Linux 
- Database engine : Microsoft SQL Server 2017 *
- _(optional)_ yarn

_* : (you might be able to switch to a different database engine if you succeed in converting the current mssql databases to somewhat else, logic itself is already abstracted using atlas)_

## Project commands 

use `yarn` or `npm run` following one of these following commands :

- `start` : Will start all the server workers.
- `build-dev`: Will build the client side typescript app in the public/ folder.
- `build-prod` : Will build the client side typescript app in production mode.
- `watch-client`: Will watch for changes in client-side files and rebuild needed app parts when needed.
- `watch-server` : Will watch for changes in php files and restart the server when needed.
- `watch `: Will watch for both client and server changes, and will asynchronously rebuild the needed app parts or restart the server.
- `lint` : Will analyse your client source code using tslint. Snipets

## Framework features

 - **Server-side rendering** :  _using only modern PHP 7 CLI with new exception catches management and workman._ 
 - **Client-side rendering** :  _using twig.js_ 
 - **Modern SASS & Typescript transpiling, modern app** :  _using webpack, and modern libs like sass, typescript ... For a pleasant source code. 
 We also use developer-friendly libs such as jquery but with our own `states` system for once again, performance gains for both visitor CPU and your productivity__ 
 - **Support private browsers and legacy browsers** : _When javascript _is disable_, visitor can still switch pages, perform forms and href using the legacy web features. 
   We are also using bootstrap 3 with retro-compatible css and javascript thanks to webpack._
 - **High speed even under 2G or any poor connexion** : _When javascript _is enable_, all form and href tags add redirected to the same url and so controllers but using this time the `SOCKET` request type. This call is performed by socket.io client and so support legacy browsers, and provide nice speed.
 The data provided by the php server is then very small as only data of a component will be given in order to re render twig.js components._ 
 - **User and admin friendly ORM db models** : _using atlas and Microsoft SQL SERVER 2017 (and once again with nice performances)_
 - **Friendly controllers** :  _using our own **POLITIC/SOLEX**_ router
 - **Friendly configuration** :  _using **SYMFONTY/DOTENV** component_
 - **Base skeleton** : _Enjoy a fully working CMS with all the basics features that you would expect from it._
 - **Extendable architecture** : _Enjoy a fully working CMS with all the basics features that you would expect from it._

## We are using the best packages out there
- `twig` 2.5 _For server-side rendering_
- `twig.js` 1.12.0 _For client-side rendering_
- `jquery` 3.3.1 _For dom manipulation but with our own javascript states system for perfs. gains._
- `webpack` 4.17.1 _Fast modules and source code web packing_ 
- `typescript` 3.0.1 _Enjoy the best of the javascript powers_
- `atlas` 2.x-dev _Models, Models generation and nothing else_
- `node-sass` 4.9.3 _.scss files are supported ;)_
- `socket.io-client` 1.3 _Talk to the php server with this client socektio implementation._ 
- `nodemon` 1.18.4 _Enjoy server refresh on sourcecode changes !_ 
- `iPolitic/phpsocket.io` dev-master _LIsten for inc. socket.io packets_ 
- `iPolitic/Workerman` dev-master _Our own fork of workerman_
- `iPolitic/Bike` dev-master _Our own router, forked from Bike_

## Project architecture 

- `[DIR] - bundles` : _Contains all the third-party bundles that your app is currently using, Don't reinvent the wheel ! Seriously, you can build everything with a bundle._ 
   - `[DIR] -/ [vendor_name]` 
        - `[DIR] -/ [bundle_name]` 
             - `[bundle source code root here ...]` 
- `[DIR] - configs` :  _Contains all your app configs_ 
   - `[FILE] -/ .env` : _Contains all private settings (such database information etc...)_
   - `[DIR] -/ webpack` : _Contains all the webpack config scripts_
- `[DIR] - node_modules` : _Contains JAVASCRIPT (Node.JS) packages._
- `[DIR] - public` : _Contains all the public files accessible using your public domain or ip as root._
- `[DIR] - serialized` : _contains serialized values generated by the source code itself. It should be cleared when it become really large, but users would be all disconnected._ 
- `[DIR] - src` : _Contains all your own app source code, in typescript, php or sass._
- `[DIR] - vendor` : _Contains PHP (composer) packages._
- `[FILE] - .babelrc` : _Contains babel configuration._
- `[FILE] - composer.json` : _Contains the PHP glues between all your PHP files using composer._ 
- `[FILE] - .gitignore` : _Contains git paths that should be ignored._
- `[FILE] - nodemon.json` : _Contains nodemon rules for watching sourcecode (see the `watch` command) and performing a command when a modification is done._
- `[FILE] - package.json` : _Contains the Node.JS glues between allyour JAVASCRIPT file using nodejs._
- `[FILE] - tsconfig.json` : _Contains typescript settings._
- `[FILE] - tslint.json` : _Contains tslint settings (code highlighting)._ 
