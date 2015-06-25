# doppelgaenger

Make PHP structure definition clones which look the same but behave differently
[![Build Status](https://ci.appserver.io/buildStatus/icon?job=github_appserver-io_doppelgaenger)](https://ci.appserver.io/job/github_appserver-io_doppelgaenger)
[![Latest Stable Version](https://img.shields.io/packagist/v/appserver-io/doppelgaenger.svg?style=flat-square)](https://packagist.org/packages/appserver-io/doppelgaenger) 
 [![Total Downloads](https://img.shields.io/packagist/dt/appserver-io/doppelgaenger.svg?style=flat-square)](https://packagist.org/packages/appserver-io/doppelgaenger)
 [![License](https://img.shields.io/packagist/l/appserver-io/doppelgaenger.svg?style=flat-square)](https://packagist.org/packages/appserver-io/doppelgaenger)
 [![Build Status](https://ci.appserver.io/buildStatus/icon?job=github_appserver-io_doppelgaenger)](https://ci.appserver.io/job/github_appserver-io_doppelgaenger/)
 [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/appserver-io/doppelgaenger/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/?branch=master)
 [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/appserver-io/doppelgaenger/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/?branch=master)

# Introduction

Doppelgaenger is created for tampering with PHP structure definitions such as classes before they get loaded and known to the code which uses them.
Its main goal is to introduce additional behaviour and logic to code in a simple and controllable manner.
To do this doppelgaenger relies on annotations with which additional features can be added.

Doppelgaenger currently supports the known techniques of:

- [AOP](http://en.wikipedia.org/wiki/Aspect-oriented_programming)
- [Design by Contract](http://en.wikipedia.org/wiki/Design_by_contract)

## Issues
In order to bundle our efforts we would like to collect all issues regarding this package in [the main project repository's issue tracker](https://github.com/appserver-io/appserver/issues).
Please reference the originating repository as the first element of the issue title e.g.:
`[appserver-io/<ORIGINATING_REPO>] A issue I am having`

## Semantic versioning
This library follows semantic versioning and its public API defines as follows:

* The public API of [its related appserver.io PSR](https://github.com/appserver-io-psr/mop)
* The public interface of the `\AppserverIo\Doppelgaenger\AutoLoader` class
* The public interface of the `\AppserverIo\Doppelgaenger\Config` class
* The syntax and amount of usable annotations (NOT including common annotations such as `@param` and `@return`)
* The format of its configuration files

## External Links
* Documentation at [appserver.io](http://docs.appserver.io) (have a look at [`AOP`](http://appserver.io/get-started/documentation/aop.html) and [`Design by Contract`](http://appserver.io/get-started/documentation/design-by-contract.html) section)
