# doppelgaenger

Make PHP structure definition clones which look the same but behave differently

[![Latest Stable Version](https://poser.pugx.org/appserver-io/doppelgaenger/v/stable.png)](https://packagist.org/packages/appserver-io/doppelgaenger) [![Total Downloads](https://poser.pugx.org/appserver-io/doppelgaenger/downloads.png)](https://packagist.org/packages/appserver-io/doppelgaenger) [![License](https://poser.pugx.org/appserver-io/doppelgaenger/license.png)](https://packagist.org/packages/appserver-io/doppelgaenger) [![Build Status](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/badges/build.png?b=master)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/?branch=master)

# Introduction

Doppelgaenger is created for tampering with PHP structure definitions such as classes before they get loaded and known to the code which uses them.
Its main goal is to introduce additional behaviour and logic to code in a simple and controllable manner.
To do this doppelgaenger relies on annotations with which additional features can be added.

Doppelgaenger currently supports the known techniques of:

- [AOP](http://en.wikipedia.org/wiki/Aspect-oriented_programming)
- [Design by Contract](http://en.wikipedia.org/wiki/Design_by_contract)

## Installation

If you want to give this project a try you can do so using composer.
Just include the following code into your composer.json` and you are good to go.

```js
{
    "require": {
        "appserver-io/doppelgaenger": "dev-master"
    }
}
```

# External Links

* Documentation at [appserver.io](http://docs.appserver.io) (have a look at `AOP` and `Design by Contract` section)
