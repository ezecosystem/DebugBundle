<?php

namespace ThinkCreative\DebugBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DebugTwigExtension extends \Twig_Extension
{

	protected $Container;

	public function __construct(ContainerInterface $container){
		$this->Container = $container;
	}

	public function getFunctions(){
		return array(
			'dump_vars' => new \Twig_Function_Method($this, 'dumpVariables', array(
				'is_safe' => array("html")
			))
		);
	}

	public function getName(){
		return 'thinkcreative_debug_extension';
	}

	public function getTokenParsers(){
		return array(
			new TokenParser\DebugTokenParser($this->Container->get('thinkcreative.debug')->getOutputToken()),
		);
	}

	public function dumpVariables($variable=false, $max_depth=2, $label=false, $debug=NULL){
		$Kernel = $this->Container->get('kernel');
		$DebugHandler = $this->Container->get('thinkcreative.debug');

		$OutputBlockID = $DebugHandler->createOutputBlock('ThinkCreative\DebugBundle\Debug\OutputBlocks\DumpVariables', array(
			'variable' => Utils\DumpVariable::process($variable, $max_depth, str_replace($Kernel->getName(), '', $Kernel->getRootDir())),
			'label' => $label,
			'block_label' => 'dump_vars()',
			'standalone' => true,
		));

		if($debug === false || ($debug === NULL && !$DebugHandler->hasOutputToken())){
			return $DebugHandler->renderOutputBlock($OutputBlockID);
		}
	}

}
