<?php

namespace ThinkCreative\DebugBundle\Twig;

abstract class DebugBaseTemplate extends \Twig_Template
{

	public function display(array $context, array $blocks = array()){
		$StartTime = microtime(true);
		parent::display($context, $blocks);
		$EndTime = microtime(true);
		if($Template = Utils\DebugTemplate::addTemplate($this->getTemplateName(), true)){
			$Template->setElapsedTime($EndTime - $StartTime);
		}
	}

}
