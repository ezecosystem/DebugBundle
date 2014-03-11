<?php

namespace ThinkCreative\DebugBundle\Debug;

class OutputBlock implements OutputBlockInterface
{

	private $ID;
	protected $Options = array();

	public function __construct(){
		$this->ID = spl_object_hash($this);
		$this->setOptions(
			array(
				'standalone' => true,
				'block_label' => '',
			)
		);
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
		$this->Options = array_replace_recursive(
			$this->Options, $options
		);
	}

}
