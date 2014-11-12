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
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities;

use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\AdviceList;
use AppserverIo\Doppelgaenger\Entities\Lists\JoinpointList;
use AppserverIo\Doppelgaenger\Interfaces\Pointcut;
use TechDivision\PBC\Entities\Definitions\AbstractDefinition;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutFactory;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut
 *
 * Definition of a pointcut as a combination of a joinpoint and advices
 *
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @see https://www.eclipse.org/aspectj/doc/next/progguide/quick.html
 * @see https://www.eclipse.org/aspectj/doc/next/progguide/semantics-pointcuts.html
 */
class PointcutExpression extends AbstractLockableEntity
{

    /**
     *
     */
    const TOKEN_AND = '&&';

    /**
     *
     */
    const TOKEN_OR = '||';

    /**
     * Tree of expressions which form the complete expression of this pointcut
     *
     * @var array $expressionTree
     */
    protected $expressionTree;

    /**
     * Joinpoints at which the enclosed advices have to be weaved
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\JoinpointList $joinpoints
     */
    protected $joinpoints;

    /**
     * Original string definition of the pointcut
     *
     * @var string $string
     */
    protected $string;

    /**
     * @var  $typeMapping <REPLACE WITH FIELD COMMENT>
     */
    protected $typeMapping;

    /**
     * Default constructor
     *
     * @param string $rawString Raw string the pointcuts expressions can be filtered from
     */
    public function __construct($rawString)
    {
        $this->joinpoints = new JoinpointList();
        $this->string = $rawString;
        $this->typeMapping = array(
            'call' => '\AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition',
            'execute' => '\AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition',
            'get' => '\AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition',
            'set' => '\AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition'
        );

        // TODO this cannot be nested! Change resolving algorithm to make use of () and boolean logic
        $tmpTree = explode(self::TOKEN_OR, $rawString);
        $pointcutFactory = new PointcutFactory();
        foreach ($tmpTree as $key => $leaf) {

            $tmpTree[$key] = explode(self::TOKEN_AND, $leaf);

            foreach ($tmpTree[$key] as $index => $expression) {

                $pointcut = $pointcutFactory->getInstance($expression);
                $tmpTree[$key][$index] = $pointcut;
            }
        }

        $this->expressionTree = $tmpTree;
    }

    /**
     * Getter for the expressionTree property
     *
     * @return array
     */
    public function getExpressionTree()
    {
        return $this->expressionTree;
    }

    /**
     * Will recursively build up a string representation of a pointcut tree
     *
     * @param array $tree Array containing a logically sorted tree of pointcuts
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getRecursiveString(array $tree)
    {
        // we have to stringify static pointcuts only, others can be determined during compile time
        $string = '';
        foreach ($tree as $andTree) {

            foreach ($andTree as $pointcut) {

                if (is_array($pointcut)) {

                    $string .= $this->getRecursiveString($pointcut);

                } elseif ($pointcut instanceof Pointcut) {

                    $string .= $pointcut->getString();

                } else {

                    throw new \Exception('Invalid pointcut within expression tree');
                }

                // extend the string
                $string .= ' && ';
            }

            // extend the string
            $string .= ' || ';
        }

        return $string;
    }

    /**
     * Getter for the joinpoints property
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\JoinpointList
     */
    public function getJoinpoints()
    {
        return $this->joinpoints;
    }

    /**
     * Will return all pointcuts which do not have to be explicitly matched against a signature but will be
     * evaluated at runtime anyway
     *
     * @return array
     */
    public function getStaticPointcuts()
    {
        return $this->staticPointcuts;
    }

    /**
     * Return a string representation of the complete pointcut expression
     *
     * @return string
     */
    public function getString()
    {
        return $this->getRecursiveString($this->getExpressionTree());
    }

    /**
     * Getter for the typeMapping property
     *
     * @return array
     */
    public function getTypeMapping()
    {
        return $this->typeMapping;
    }

    /**
     * Check if the passed definition matches this pointcut
     *
     * @param AbstractDefinition $definition
     *
     * @return boolean
     */
    public function matches(AbstractDefinition $definition)
    {
        // check the "or" combined parts first, anyone that fits will result to true
        foreach ($this->getExpressionTree() as $orSubTree) {

            // second we have to evaluate the "and" combined parts
            $result = false;
            foreach ($orSubTree as $andSubTree) {

                // use the "match" method to check a single expression
                $positiveResults = 0;
                foreach ($andSubTree as $pointcut) {

                    if ($pointcut->matches($definition) === true) {

                        $positiveResults ++;
                    }
                }

                if ($positiveResults === count($andSubTree)) {

                    $result = true;
                }
            }

            if ($result === true) {

                return $result;
            }
        }

        return false;
    }
}
