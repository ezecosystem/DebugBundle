<?php

namespace ThinkCreative\DebugBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class DebugHandler
{

	protected $Container;

	public function __construct(ContainerInterface $container){
		$this->Container = $container;
	}

	public function injectDebugOutput(Response $response){
		if(function_exists('mb_stripos')){
			$findPosition = 'mb_strripos';
			$getSubstring = 'mb_substr';
		} else {
			$findPosition = 'strripos';
			$getSubstring = 'substr';
		}

		$Content = $response->getContent();
		$OutputPosition = $findPosition($Content, '</body>');
		if($OutputPosition !== false){
			$DebugOutput = $this->Container->get('twig')->render('ThinkCreativeDebugBundle::debug.html.twig');
			$Content = $getSubstring($Content, 0, $OutputPosition) . $DebugOutput . $getSubstring($Content, $OutputPosition);
			$response->setContent($Content);
		}
	}

}
