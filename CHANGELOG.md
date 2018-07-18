# Version 1.7.1

## Bugfixes

* Fixed bug with building up the structure map and structure names that fall on the read-in buffer size

## Features

* None

# Version 1.7.0

## Bugfixes

* None

## Features

* Allowed importing assertion type namespaces with the `use` statement

# Version 1.6.0

## Bugfixes

* None

## Features

* Allowed usage of fully qualified class names of assertions with condition types 

# Version 1.5.1

## Bugfixes

* Fixed [#943](https://github.com/appserver-io/appserver/issues/943) - Inline Functions in Session Beans
* Fixed bug with parsing of different "use" keywords (namespace import, trait usage, closure scoping, ...)

## Features

* None

# Version 1.5.0

## Bugfixes

* None

## Features

* Closed [#609](https://github.com/appserver-io/appserver/issues/609) - Extend the debug abilities of produced code

# Version 1.4.8

## Bugfixes

* Fix wrong classname in InstanceAssertion error message

## Features

* None

# Version 1.4.7

## Bugfixes

* Fixed a bug with the parsing of parameter default values
* Fixed a bug with shallow cloning resulting in permanently straightened child pointcut expressions

## Features

* None

# Version 1.4.6

## Bugfixes

* Fixed file system related warning by sanitizing file pathes for OS specific use

## Features

* None

# Version 1.4.5

## Bugfixes

* Fixed [#855](https://github.com/appserver-io/appserver/issues/855) - Call to a protected method errors due to context mismatch

## Features

* None

# Version 1.4.4

## Bugfixes

* Fixed a bug related to the generation of abstract function

## Features

* None

# Version 1.4.3

## Bugfixes

* Fixed a bug with complex type hints which are imported by a `use` statement
* Fixed inverted assetion string in log message of failed raw assertions

## Features

* None

# Version 1.4.2

## Bugfixes

* Fixed [#842](https://github.com/appserver-io/appserver/issues/842) - Cannot use Traits

## Features

* None

# Version 1.4.1

## Bugfixes

* Fixed [#824](https://github.com/appserver-io/appserver/issues/824) - Several comment blocks break docBlock assignment

## Features

* None

# Version 1.4.0

## Bugfixes

* Fixed [#815](https://github.com/appserver-io/appserver/issues/815) - Local processing does not support "none" value
* Usage in multithreading context did require bootstrapping of additional classes

## Features

* The debug ability of the code has been extended

# Version 1.3.3

## Bugfixes

* Fixed [#811](https://github.com/appserver-io/appserver/issues/811) - Endless recursion on parent::<METHOD> call

## Features

* None

# Version 1.3.2

## Bugfixes

* Fixed [#805](https://github.com/appserver-io/appserver/issues/805) - Constructs like <CLASSNAME>::class break parsing

## Features

* None

# Version 1.3.1

## Bugfixes

* Fixed [#719](https://github.com/appserver-io/appserver/issues/719) - Around advice chain does break at certain size
* Fixed [#721](https://github.com/appserver-io/appserver/issues/721) - Different order of Advices in pointcut.xml depending on type

## Features

* None

# Version 1.3.0

## Bugfixes

* Multiple before advices have not been stacked correctly

## Features

* Advices can reference several pointcuts now using pointcut(<Name1>, <Name2>) annotation now

# Version 1.2.0

## Bugfixes

* Fixed typo within class name "TraitDefinition"

## Features

* Introduced assertions which allow for unwrapped error messages to not expose system internals
* Added structure and method based override for enforcement processing configuration based on annotations

# Version 1.1.0

## Bugfixes

* None

## Features

* Allows for the usage of custom assertions by specifying a type property within the annotation (see class \AppserverIo\Doppelgaenger\Tests\Data\AssertionTest\RespectValidationTestClass for example)
* Added a semantic versioning declaration of the public API

# Version 1.0.0

## Bugfixes

* None

## Features

* Switched to stable dependencies due to version 1.0.0 release

# Version 0.6.0

## Bugfixes

* Bugfix with resolving signature pointcuts using bash wildcard expressions

## Features

* Integrated usage of new [appserver-io-psr/mop](https://github.com/appserver-io-psr/mop) PSR repository
* Changed Design by Contract annotation syntax to fit basic doctrine syntax

# Version 0.5.2

## Bugfixes

* Pointcuts mentioning advice keywords in their doc block did get treated as advices themselves
* Bash wildcard using pointcuts only matched the first of several possible matches
* AfterReturning and AfterThrowing advices referencing pointcuts will now be woven into the right places
* Around advice callback chains will now take the current context into account
* Closed #455 - Pointcut based weaving mismatch for After/AfterThrowing
* Closed #456 - Problems with Around-Advice callback chain buildup

## Features

* Cache will get cleaned for every run of the PHPUnit tests

# Version 0.5.1

## Bugfixes

* Always assumed complex type elements in typed array collections

## Features

* Introduced <TYPE>[] syntax for typed array collection

# Version 0.5.0

## Bugfixes

* Some minor bugfixes
* Fixed a bug with Before advice's parameter altering

## Features

* Introduced MethodInvocationInterface
* Extended the functionality of AfterThrowing and AfterReturning advice by enhancing the MethodInvocation object
* New coding conventions
* Extended testing

# Version 0.4.2

## Bugfixes

* Structures with a namespace right after the PHP tag did not get picked up by the structure map

## Features

* None

# Version 0.4.1

## Bugfixes

* Incompatible implementation of overridden method has been fixed

## Features

* Minor cleanups

# Version 0.4.0

## Bugfixes

* Magic methods resulted in problems within multithreaded environments, therefore all entities were ported to explicit getters/setters

## Features

* None

# Version 0.3.3

## Bugfixes

* Pointcut expressions using shell regex in the class name did result in errors
* Problem with around advice chaining

## Features

* Improved performance of AppserverIo\Doppelgaenger\AutoLoader::loadClass() method
* Improved parser type safety

# Version 0.3.2

## Bugfixes

* Wrong test for configuration values within EnforcementFilter class

## Features

* None

# Version 0.3.1

## Bugfixes

* Shell regex pattern not working in pointcut expressions
* StructureMap->map did get cast wrong

## Features

* None

# Version 0.3.0

## Bugfixes

* None

## Features

* Pthreads compatibility

# Version 0.2.0

## Bugfixes

* None

## Features

* Aspect collection and lookup
* Improved advice weaving
* Advice chaining for around advices

# Version 0.1.0

## Bugfixes

* None

## Features

* Initial commit
