<?php

namespace ThinkCreative\DebugBundle\Debug;

interface OutputBlockInterface
{

	public function getID();

	public function getOptions();

	public function getTemplateName();

	public function set($name, $value);

	public function setOptions(array $options);

}
