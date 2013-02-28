<?php

namespace ThinkCreative\DebugBundle\Debug;

class OutputBlock implements OutputBlockInterface
{

	private $ID;
	protected $Options;

	public function __construct(){
		$this->ID = spl_object_hash($this);
	}

	public function getID(){
		return $this->ID;
	}

	public function getOptions(){
		return $this->Options;
	}

	public function getTemplateName(){
		return 'ThinkCreativeDebugBundle::output_block.html.twig';
	}

	public function set($name, $value){
		$this->Options[$name] = $value;
	}

	public function setOptions(array $options){
		$this->Options = $options;
	}

}
