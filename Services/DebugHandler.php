<?php

namespace ThinkCreative\DebugBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use ThinkCreative\DebugBundle\Debug\OutputBlockInterface;

class DebugHandler
{

	protected $Container;

	protected static $OutputBlocks = array();

	public function __construct(ContainerInterface $container){
		$this->Container = $container;
	}

	public function addOutputBlock(OutputBlockInterface $output_block){
		$OutputBlockID = $output_block->getID();
		self::$OutputBlocks[$OutputBlockID] = $output_block;
		return $OutputBlockID;
	}

	public function createOutputBlock($class = 'ThinkCreative\DebugBundle\Debug\OutputBlock', array $options = array()){
		if(is_array($class)){
			$options = $class;
			$class = 'ThinkCreative\DebugBundle\Debug\OutputBlock';
		}
		$OutputBlock = new $class();
		if($options){
			$OutputBlock->setOptions($options);
		}
		return $this->addOutputBlock($OutputBlock);
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
			foreach(self::$OutputBlocks as $ID => $OutputBlock){
				self::$OutputBlocks[$ID]->set('standalone', false);
			}
			$DebugOutput = $this->Container->get('twig')->render('ThinkCreativeDebugBundle::debug.html.twig', array(
				'blocks' => self::$OutputBlocks
			));
			$Content = $getSubstring($Content, 0, $OutputPosition) . $DebugOutput . $getSubstring($Content, $OutputPosition);
			$response->setContent($Content);
		}
	}

	public function removeOutputBlock($id, $return = false){
		if(isset(self::$OutputBlocks[$id])){
			if($return){
				$OutputBlock = self::$OutputBlocks[$id];
			}
			unset(self::$OutputBlocks[$id]);
			return $return ? $OutputBlock : true;
		}
		return false;
	}

	public function renderOutputBlock($id){
		$OutputBlock = $this->removeOutputBlock($id, true);
		return $this->Container->get('twig')->render($OutputBlock->getTemplateName(), $OutputBlock->getOptions());
	}

}
