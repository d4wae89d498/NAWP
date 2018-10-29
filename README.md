
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com/MarcFaussurier/NAWP/graphs/commit-activity)
[![Build Status](https://travis-ci.com/MarcFaussurier/NAWP.svg?branch=master)](https://travis-ci.com/MarcFaussurier/NAWP)
[![Code Coverage](./tests/clover.svg)](https://github.com/MarcFaussurier/NAWP)
[![GitHub release](https://img.shields.io/github/tag/MarcFaussurier/NAWP.svg)](https://GitHub.com/MarcFaussurier/NAWP/releases/)
[![HitCount](http://hits.dwyl.io/MarcFaussurier/badges.svg)](http://hits.dwyl.io/MarcFaussurier/badges)
[![Ask Me Anything !](https://img.shields.io/badge/Ask%20me-anything-1abc9c.svg)](https://GitHub.com/MarcFaussurier)

## **IPOLITIC/NAWP** 
###### A simple but powerful future-proof and high performance network oriented framework. It uses combo of both modern typescript and php to deliver amazing performances. 

To install the project, simply run `composer install` for installing server PHP deps. Then run `npm install` or `yarn` in your terminal for installing javascript deps.

Now you have to create a `configs/.env` file using the `configs/.env.dist` one as sample so that the software is able to use your settings.

Demo project database file is available as `config/nawpcore-mssql-database.sql` . It is currently in MSSQL format but feel free to convert it using a tool like MySQL workbench.

## Minimum requirements 
- PHP >= 7.2.0 
- NodeJS  >= 10.0.0 
- Supported os : Windows || MacOS || Linux 

## Project commands 

All commands built as `npm` scripts.  
Use `yarn` or `npm run` to run one of the following commands :

- `start` : Will start all the server workers.
- `test` : Will run tests.
- `build-dev`: Will build the client side typescript app in the public/ folder.
- `build-prod` : Will build the client side typescript app in production mode.
- `watch-client`: Will watch for changes in client-side files and rebuild needed app parts when needed.
- `watch-server` : Will watch for changes in php files and restart the server when needed.
- `watch `: Will watch for both client and server changes, and will asynchronously rebuild the needed app parts or restart the server.
- `lint` : Will analyse your client source code using tslint.

## Framework features

 - **Server-side rendering** :  _using only modern PHP 7 CLI with new exception catches management, workman and twig._ 
 
 - **Client-side rendering** :  _using twig.js and morphdom DOM diffing lib for smooth updates_ 
 
 - **Modern SASS & Typescript transpiling, modern app** :  _using webpack, and modern libs like sass, typescript ... For a pleasant source code. 
 We also use developer-friendly libs such as jquery but with our own `states` system for once again, performance gains for both visitor CPU and your productivity_ 
 
 - **Support private browsers and legacy browsers** : _When javascript _is disable_, visitor can still switch pages, perform forms and href using the legacy web features. 
   We are also using bootstrap 3 with retro-compatible css and javascript thanks to webpack._
 
 - **High speed even under 2G or any poor connexion** : _When javascript _is enable_, all form and href tags add redirected to the same url and so controllers but using this time the `SOCKET` request type. This call is performed by socket.io client and so support legacy browsers, and provide nice speed.
 The data provided by the php server is then very small as only data of a component will be given in order to re render twig.js components._ 
 
 - **User and admin friendly ORM db models** : _using atlas and Microsoft SQL SERVER 2017 (and once again with nice performances)_
 
 - **Developer friendly controllers** :  _using our own **POLITIC/SOLEX**_ router
 
 - **Admin friendly configuration** :  _using a 10 line .env file ( **SYMFONTY/DOTENV** component ) and 2 webpack files_
 
 - **Base skeleton** : _Enjoy a fully working CMS with all the basics features that you would expect from it._
 
 - **Extendable architecture** : _Use bundles to share controllers and assets between your projects_

## We are using the best packages out there
- `twig` 2.5 _For server-side rendering_
- `twig.js` 1.12.0 _For client-side rendering_
- `jquery` 3.3.1 _For dom manipulation but with our own javascript states system for perfs. gains._
- `webpack` 4.17.1 _Fast modules and source code web packing_ 
- `typescript` 3.0.1 _Enjoy the best of the javascript powers_
- `atlas` 3.x-dev _Models, Models generation and nothing else_
- `node-sass` 4.9.3 _.scss files are supported ;)_
- `socket.io-client` 1.3 _Talk to the php server with this client socektio implementation._ 
- `nodemon` 1.18.4 _Enjoy server refresh on sourcecode changes !_ 
- `iPolitic/Workerman` dev-master _Our own fork of workerman (php async engine)_
- `iPolitic/phpsocket.io` dev-master _Our own fork of phpsocketio (php socket.io server)_ 
- `iPolitic/Bike` dev-master _Our own fork of Bike (php router)_
