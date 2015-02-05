# Version 0.5.2

## Bugfixes

* Pointcuts mentioning advice keywords in their doc block did get treated as advices themselves
* Bash wildcard using pointcuts only matched the first of several possible matches
* AfterReturning and AfterThrowing advices referencing pointcuts will now be woven into the right places
* Closes #455 - Pointcut based weaving mismatch for After/AfterThrowing

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
* New coding coneventions
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