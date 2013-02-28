<?php

namespace ThinkCreative\DebugBundle\Twig\Utils;

class DumpVariable
{

	protected $Type = '';
	protected $Value = NULL;
	protected $Attributes = array();
	protected $Label = '';
	protected $ComplexVariable = false;

	private $Item = NULL;

	public function __construct($item){
		$this->Value = $this->Item = $item;
		$this->ComplexVariable = is_array($item) || is_object($item);
		if(($this->Type = gettype($item)) && $this->Type == 'double'){
			$this->Type = 'float';
		}
	}

	public function getAttributes(){
		return $this->Attributes;
	}

	public function getLabel(){
		return $this->Label;
	}

	public function getType(){
		return $this->Type;
	}

	public function getValue(){
		switch($this->Type){
			case 'string': {
				return '"' . $this->Value . '"';
			}
			case 'boolean': {
				return $this->Value ? 'true' : 'false';
			}
			default: {
				return $this->Value;
			}
		}
	}

	public function isComplex(){
		return $this->ComplexVariable;
	}

	public function setLabel($label){
		$this->Label = $label;
		return $this;
	}

	protected function processComplexVariable($system_directory='', $has_depth=true){
		$this->Value = $has_depth ? $this->Value : array();
		$this->Attributes = array(
			'count' => count($this->Item)
		);
		if($this->Type == 'object'){
			$Reflector = new \ReflectionObject($this->Item);
			$this->Attributes = array(
				'classname' => $Reflector->name,
				'filename' => str_replace($system_directory, '', $Reflector->getFileName())
			);
			if($has_depth){
				if(method_exists($this->Item, 'attributes') && method_exists($this->Item, 'attribute')){
					foreach($this->Item->attributes() as $AttributeName){
						$Properties[$AttributeName] = $this->Item->attribute($AttributeName);
					}
				} else {
					foreach($Reflector->getProperties(\ReflectionProperty::IS_PUBLIC) as $Property){
						$Properties[strtolower($Property->name)] = $Property->getValue($this->Item);
					}
					foreach($Reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $Key => $Item){
						if(!$Item->getNumberOfRequiredParameters() && $hasMatch = preg_match('/^(get|is)(.*)/', $Item->name, $Name)){
							try {
								$Properties[strtolower($Name[2])] = $Item->invoke($this->Item);
							} catch (\Exception $e) {
								$Properties[strtolower($Name[2])] = "{unknown value}";
							}
						}
					}
				}
				if(isset($Properties)){
					$this->Value = $Properties;
					$this->Attributes['count'] = count($Properties);
				}
			}
		}
		return $this->Value;
	}

	public static function process($item, $max_depth=2, $system_directory='', $level=0){
		$Variable = new self($item);
		if($Variable->isComplex()){
			foreach($Variable->processComplexVariable($system_directory, $level+1 <= $max_depth) as $Key => $Value){
				$Variable->Value[$Key] = self::process($Value, $max_depth, $system_directory, $level+1);
			}
		}
		return $Variable;
	}

}
