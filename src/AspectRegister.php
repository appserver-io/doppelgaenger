<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger;

use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\After;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\AfterReturning;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\AfterThrowing;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\Around;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\Before;
use AppserverIo\Doppelgaenger\Entities\Annotations\Pointcut as PointcutAnnotation;
use AppserverIo\Doppelgaenger\Entities\Definitions\Advice;
use AppserverIo\Doppelgaenger\Entities\Definitions\Aspect;
use AppserverIo\Doppelgaenger\Entities\Definitions\AspectDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\AbstractTypedList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedList;
use AppserverIo\Doppelgaenger\Entities\PointcutExpression;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutFactory;
use AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut as PointcutDefinition;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutPointcut;
use Herrera\Annotations\Convert\ToArray;
use Herrera\Annotations\Tokenizer;
use Herrera\Annotations\Tokens;

/**
 * AppserverIo\Doppelgaenger\AspectRegister
 *
 * Class which knows about registered aspects to allow for checks of methods against all given pointcuts
 *
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */
class AspectRegister extends AbstractTypedList
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->itemType = '\AppserverIo\Doppelgaenger\Entities\Definitions\Aspect';
        $this->defaultOffset = 'name';
    }

    /**
     * Look up a certain advice based on a glob-like expression optionally containing aspect name and advice name
     *
     * @param string $adviceExpression Expression defining the search term
     *
     * @return array<\AppserverIo\Doppelgaenger\Entities\Definitions\Advice>
     */
    public function lookupAdvice($adviceExpression)
    {
        // if there is an aspect name within the expression we have to filter our search range and cut the expression
        $container = $this->container;
        if (strpos($adviceExpression, '->')) {

            $aspectExpression = strstr($adviceExpression, '->', true);
            $container = $this->lookupAspects($aspectExpression);
            $adviceExpression = str_replace('->', '', strstr($adviceExpression, '->'));
        }

        $matches = array();
        foreach ($container as $aspect) {

            $matches = array_merge($matches, $this->lookupEntries($aspect->getAdvices(), $adviceExpression));
        }

        return $matches;
    }

    /**
     * Will narrow down the choice of aspects to make when looking up pointcuts or advices.
     * Just pass the expression to look up any of both
     *
     * @param string $expression Expression defining the search term
     *
     * @return array<\AppserverIo\Doppelgaenger\Entities\Definitions\Aspect>
     */
    public function lookupAspects($expression)
    {
        return $this->lookupEntries($this->container, $expression);
    }

    /**
     * Look up certain entities in a container based on their qualified name and a glob-like expression
     *
     * @param array|\Traversable $container  Traversable container we will look in
     * @param string             $expression Expression defining the search term
     *
     * @return array
     */
    protected function lookupEntries($container, $expression)
    {
        // if we got the complete name of the aspect we can return it alone
        if (!strpos($expression, '*') && $this->entryExists($expression)) {

            return array($this->get($expression));
        }

        // as it seems we got something else we have to get all regex about
        $matches = array();
        foreach ($container as $entry) {

            if (fnmatch(ltrim($expression, '\\'), $entry->getQualifiedName() . '()')) {

                $matches[] = $entry;
            }
        }

        return $matches;
    }

    /**
     * Look up a certain advice based on a glob-like expression optionally containing aspect name and pointcut name
     *
     * @param string $pointcutExpression Expression defining the search term
     *
     * @return array<\AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut>
     */
    public function lookupPointcuts($pointcutExpression)
    {
        // if there is an aspect name within the expression we have to filter our search range and cut the expression
        $container = $this->container;
        if (strpos($pointcutExpression, '->')) {

            $aspectExpression = strstr($pointcutExpression, '->', true);
            $container = $this->lookupAspects($aspectExpression);
            $pointcutExpression = str_replace('->', '', strstr($pointcutExpression, '->'));
        }

        $matches = array();
        foreach ($container as $aspect) {

            $matches = array_merge($matches, $this->lookupEntries($aspect->getPointcuts(), $pointcutExpression));
        }

        return $matches;
    }

    /**
     * Will register a complete aspect to the AspectRegister.
     * This include its advices and pointcuts which can be looked up from this point on
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\AspectDefinition $aspectDefinition Structure to register as an aspect
     *
     * @return null
     */
    public function register(AspectDefinition $aspectDefinition)
    {

        // create the new aspect and fill it with things we already know
        $aspect = new Aspect();
        $aspect->setName($aspectDefinition->getName());
        $aspect->setNamespace($aspectDefinition->getNamespace());

        // prepare the tokenizer we will need for further processing
        $needles = array(
            After::ANNOTATION,
            AfterReturning::ANNOTATION,
            AfterThrowing::ANNOTATION,
            Around::ANNOTATION,
            Before::ANNOTATION
        );
        $tokenizer = new Tokenizer();
        $tokenizer->ignore(
            array(
                'param',
                'return',
                'throws'
            )
        );

        // iterate the functions and filter out the ones used as advices
        $scheduledAdviceDefinitions = array();
        foreach ($aspectDefinition->getFunctionDefinitions() as $functionDefinition) {

            $foundNeedle = false;
            foreach ($needles as $needle) {

                // create the advice
                if (strpos($functionDefinition->getDocBlock(), $needle) !== false) {

                    $foundNeedle = true;
                    $scheduledAdviceDefinitions[$needle][] = $functionDefinition;

                    break;
                }
            }

            // create the pointcut
            if (!$foundNeedle && strpos($functionDefinition->getDocBlock(), PointcutAnnotation::ANNOTATION) !== false) {

                $pointcut = new PointcutDefinition();
                $pointcut->setName($functionDefinition->getName());

                $tokens = new Tokens($tokenizer->parse($functionDefinition->getDocBlock()));

                // convert to array and run it through our advice factory
                $toArray = new ToArray();
                $annotations = $toArray->convert($tokens);

                // create the entities for the joinpoints and advices the pointcut describes
                $pointcut->setPointcutExpression(new PointcutExpression(array_pop(array_pop($annotations)->values)));
                $aspect->getPointcuts()->add($pointcut);
            }
        }
        $this->add($aspect);

        // do the pointcut lookups where we will need the pointcut factory for later use
        $pointcutFactory = new PointcutFactory();
        foreach ($scheduledAdviceDefinitions as $codeHook => $hookedAdviceDefinitions) {
            foreach ($hookedAdviceDefinitions as $scheduledAdviceDefinition) {

                // create our advice
                $advice = new Advice();
                $advice->setAspectName($aspectDefinition->getQualifiedName());
                $advice->setName($scheduledAdviceDefinition->getName());
                $advice->setCodeHook((string) $codeHook);

                $tokens = new Tokens($tokenizer->parse($scheduledAdviceDefinition->getDocBlock()));

                // convert to array and run it through our advice factory
                $toArray = new ToArray();
                $annotations = $toArray->convert($tokens);

                // create the entities for the joinpoints and advices the pointcut describes
                foreach ($annotations as $annotation) {

                    $pointcut = $pointcutFactory->getInstance(array_pop($annotation->values));
                    if ($pointcut instanceof PointcutPointcut) {

                        // get the referenced pointcuts for the split parts of the expression
                        $pointcut->setReferencedPointcuts($this->lookupPointcuts($pointcut->getExpression()));
                    }

                    $advice->getPointcuts()->add($pointcut);
                }

                $aspect->getAdvices()->add($advice);
            }
        }

        $this->set($aspect->getName(), $aspect);
    }
}
