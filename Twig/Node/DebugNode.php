<?php

namespace ThinkCreative\DebugBundle\Twig\Node;

class DebugNode extends \Twig_Node
{

	protected $OutputToken;

	protected static $hasOutputToken = false;

	public function __construct(\Twig_Node_Expression $expression = null, $line_number, $tag = null, $output_token){
		parent::__construct(array('expression' => $expression), array(), $line_number, $tag);
		$this->OutputToken = $output_token;
	}

	public function compile(\Twig_Compiler $compiler){
		if(!self::$hasOutputToken){
			self::$hasOutputToken = true;
			$compiler->write('echo "' . $this->OutputToken . '";');
		}
	}

	public static function hasOutputToken(){
		return self::$hasOutputToken;
	}

}
