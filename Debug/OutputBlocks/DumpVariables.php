<?php

namespace ThinkCreative\DebugBundle\Debug\OutputBlocks;

use ThinkCreative\DebugBundle\Debug\OutputBlock;

class DumpVariables extends OutputBlock
{

	function getTemplateName(){
		return 'ThinkCreativeDebugBundle:dumpvars:main.html.twig';
	}

}
