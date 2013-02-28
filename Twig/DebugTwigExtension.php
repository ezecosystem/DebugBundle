<?php

namespace ThinkCreative\DebugBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DebugTwigExtension extends \Twig_Extension
{

	protected $Container;

	public function __construct(ContainerInterface $container){
		$this->Container = $container;
	}

	public function getName(){
		return 'thinkcreative_debug_extension';
	}

}
