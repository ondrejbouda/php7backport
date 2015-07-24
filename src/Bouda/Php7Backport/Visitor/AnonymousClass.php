<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;


/** 
 * Create standard class declaration from anonymous class, 
 * replace usage as argument of function/method and append declaration 
 * to the end of file.  
 *  
 * Example: 
 *  
 * $util->setLogger(new class("test.log") {
 *     function __construct($file) {}
 *     public function log($msg)
 *     {
 *         echo $msg;
 *     }
 * }); 
 *  
 * becomes
 *  
 * $util->setLogger(new AnonymousClass_1(\'test.log\'));
 * 
 * class AnonymousClass_1
 * {
 *     function __construct($file)
 *     {
 *     }
 *     public function log($msg)
 *     {
 *         echo $msg;
 *     }
 * }
 * 
 */
class AnonymousClass extends Php7Backport\Visitor
{
    /** @var string */
    private $newClassName;


    public function leaveNode(Node $node)
    {
        if ($node instanceof Arg 
            && isset($node->value->class)
            && $node->value->class instanceof Class_
            && $node->value->class->name === null)
        {
            $this->newClassName = 'AnonymousClass_' . $this->patches->getUniqueId();

            $this->createAndAddClassDeclaration($node->value->class);

            return $this->tranformAndSave($node);
        }
    }


    private function createAndAddClassDeclaration(Class_ $class)
    {
        $class->name = $this->newClassName;

        $patch = $this->patchFactory->create($class);
        $this->patches->append($patch);
    }


    protected function transform(Node $node)
    {
        $node->value->class = new Name([$this->newClassName]);

        return $node;
    }
}
