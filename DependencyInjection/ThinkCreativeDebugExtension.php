<?php

namespace ThinkCreative\DebugBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ThinkCreativeDebugExtension extends Extension
{

	public function load(array $configs, ContainerBuilder $container){
		$loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.xml');

		$TwigOptions = $container->getParameter('twig.options');
		if(!isset($TwigOptions['base_template_class'])){
			$TwigOptions['base_template_class'] = 'ThinkCreative\DebugBundle\Twig\DebugBaseTemplate';
			$container->setParameter('twig.options', $TwigOptions);
		}
	}

	public function getAlias(){
		return "think_creative_debug";
	}

}
