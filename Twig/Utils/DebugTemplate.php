<?php

namespace ThinkCreative\DebugBundle\Twig\Utils;

class DebugTemplate
{

	protected $ID = '';
	protected $Name = '';
	protected $Count = 0;
	protected $ElapsedTime = 0;

	protected static $TemplateList = array();

	public function __construct($name){
		$this->Name = $name;
	}

	public function getAverageElapsedTime(){
		if($this->Count){
			return $this->ElapsedTime / $this->Count;
		}
		return NULL;
	}

	public function getCount(){
		return $this->Count;
	}

	public function getElapsedTime(){
		return $this->ElapsedTime;
	}

	public function getID(){
		return $this->ID;
	}

	public function getName(){
		return $this->Name;
	}

	public function setElapsedTime($time, $reset=false){
		if(is_numeric($time)){
			$this->ElapsedTime = $reset ? $time : $this->ElapsedTime + $time;
			return true;
		}
		return false;
	}

	public static function addTemplate($name, $as_object=false){
		if($name && $TemplateKey = self::generateTemplateKey($name)){
			if(!isset(self::$TemplateList[$TemplateKey])){
				self::$TemplateList[$TemplateKey] = new self($name);
				self::$TemplateList[$TemplateKey]->ID = $TemplateKey;
			}
			self::$TemplateList[$TemplateKey]->Count++;
			return $as_object ? self::$TemplateList[$TemplateKey] : $TemplateKey;
		}
		return false;
	}

	public static function generateTemplateKey($name){
		return hash('sha512', $name);
	}

	public static function getTemplateList(){
		return self::$TemplateList;
	}

	public static function removeTemplate($template_key){
		if(isset(self::$TemplateList[$template_key])){
			unset(self::$TemplateList[$template_key]);
			return true;
		}
		return false;
	}

}
