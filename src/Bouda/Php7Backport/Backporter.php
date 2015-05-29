<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;


class Backporter
{
    /** @var PhpParser\PrettyPrinterAbstract */
    private $printer;


    public function __construct()
    {
        $this->printer = new Printer;
    }


    /**
     * Backports PHP7 code to PHP5.
     *
     * @param string $code
     * @return string ported code
     * @throws PhpParser\Error
     */
    public function port($code)
    {
        $lexer = new PhpParser\Lexer\Emulative(array(
            'usedAttributes' => array(
                'comments',
                'startLine',
                'endLine',
                'startFilePos',
                'endFilePos',
                'startTokenPos',
                'endTokenPos'
            )
        ));

        $parser = new PhpParser\Parser($lexer);

        $traverser = new PhpParser\NodeTraverser;
        
        // add starting <?php tag if necessary
        if (strpos($code, '<?') === false)
        {
            $code = '<?php ' . $code;
        }

        $parsedStatements = $parser->parse($code);

        $tokens = $lexer->getTokens();

        $changedNodes = new ChangedNodes;

        $visitor = new Visitor($tokens, $changedNodes);
        $traverser->addVisitor($visitor);

        $portedStatements = $traverser->traverse($parsedStatements);

        $offset = 0;

        foreach ($changedNodes->getSortedNodes() as $changedNode)
        {
            $start = $changedNode->getOriginalStartPosition($offset);
            $end = $changedNode->getOriginalEndPosition($offset);
            
            $originalLength = $changedNode->getOriginalLength();
            
            $renderedNode = $this->printer->printNode($changedNode->getNode());

            $newLength = strlen($renderedNode);

            $code = substr_replace($code, $renderedNode, $start, $originalLength);

            $offset += $newLength - $originalLength;
        }

        return $code;
    }
}
