# doppelgaenger

Make PHP structure definition clones which look the same but behave differently

[![Latest Stable Version](https://poser.pugx.org/appserver-io/doppelgaenger/v/stable.png)](https://packagist.org/packages/appserver-io/doppelgaenger) [![Total Downloads](https://poser.pugx.org/appserver-io/doppelgaenger/downloads.png)](https://packagist.org/packages/appserver-io/doppelgaenger) [![Dependencies Status](https://depending.in/appserver-io/doppelgaenger.png)](http://depending.in/appserver-io/doppelgaenger) [![License](https://poser.pugx.org/appserver-io/doppelgaenger/license.png)](https://packagist.org/packages/appserver-io/doppelgaenger) [![Build Status](https://travis-ci.org/appserver-io/doppelgaenger.png)](https://travis-ci.org/appserver-io/doppelgaenger) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/appserver-io/doppelgaenger/?branch=master)

# Introduction

Doppelgaenger is created for tampering with PHP structure definitions such as classes before they get loaded and known to the code which uses them.
Its main goal is to introduce additional behaviour and logic to code in a simple and controlable manner.
To do this doppelgaenger relies on annotations with which additional features can be added.

Doppelgaenger currently supports the known techniques of:

- [AOP](http://en.wikipedia.org/wiki/Aspect-oriented_programming)
- [Design by Contract](http://en.wikipedia.org/wiki/Design_by_contract) (by using [php-by-contract](https://github.com/techdivision/php-by-contract))

# Roadmap

- Allow for annotation shorthands and annotation mapping
