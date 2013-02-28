<?php

namespace ThinkCreative\DebugBundle\Twig\TokenParser;

use ThinkCreative\DebugBundle\Twig\Node;

class DebugTokenParser extends \Twig_TokenParser
{

	protected $OutputToken;

	public function __construct($output_token){
		$this->OutputToken = $output_token;
	}

	public function parse(\Twig_Token $token){
		$Expression = null;
		if(!$this->parser->getStream()->test(\Twig_Token::BLOCK_END_TYPE)) {
			$Expression = $this->parser->getExpressionParser()->parseExpression();
		}
		$this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
		return new Node\DebugNode($Expression, $token->getLine(), $this->getTag(), $this->OutputToken);
	}

	public function getTag(){
		return 'debug';
	}

}
