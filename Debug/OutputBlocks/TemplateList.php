<?php

namespace ThinkCreative\DebugBundle\Debug\OutputBlocks;

use ThinkCreative\DebugBundle\Debug\OutputBlock;
use ThinkCreative\DebugBundle\Twig\Utils\DebugTemplate;

class TemplateList extends OutputBlock
{

	function getOptions(){
		return array_replace_recursive(
			$this->Options,
			array(
				'template_list' => DebugTemplate::getTemplateList(),
			)
		);
	}

	function getTemplateName(){
		return 'ThinkCreativeDebugBundle::template_list.html.twig';
	}

}
