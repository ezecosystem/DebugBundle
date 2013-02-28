<?php

namespace ThinkCreative\DebugBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class DebugBlockListener implements EventSubscriberInterface
{

	protected $Container;

	public function __construct(ContainerInterface $container){
		$this->Container = $container;
	}

	public function onKernelResponse(FilterResponseEvent $event){
		$Kernel = $this->Container->get('kernel');
		$DebugHandler = $this->Container->get('thinkcreative.debug');

		if($Kernel->isDebug() && HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()){
			$Request = $event->getRequest();
			$Response = $event->getResponse();
			if(!$Request->isXmlHttpRequest() && !$Response->isRedirection() && $this->isHTML($Request, $Response)){
				$DebugHandler->injectDebugOutput($Response);
			}
		}
	}

	protected function isHTML(Request $request, Response $response){
		$hasContentType = $response->headers->has('Content-Type');
		return ($hasContentType && strpos($response->headers->get('Content-Type'), 'html') !==  false) || $request->getRequestFormat() == 'html';
	}

	public static function getSubscribedEvents(){
		return array(
			KernelEvents::RESPONSE => array('onKernelResponse', -128),
		);
	}

}
