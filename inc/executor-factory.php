<?php

require_once "abstract-executor.php";
require_once "executors/default.php";

class ExecutorFactory
{
	private $action;

	public function __construct($action)
	{
		$this->action = $action;
	}

	public function create() 
	{
		$executors = scandir(__DIR__ . "/executors");

		foreach($executors as $exector_file)
		{
			if($exector_file == "." || $exector_file == "..") 
			{
				continue;
			}

			include_once __DIR__ . "/executors/" . $exector_file;
			$className = 'Executors_' . ucfirst(strtolower(str_replace('.php', '', $exector_file)));

			if(class_exists($className)) {
				
				$executor = new $className;

				if(in_array($this->action, $executor::$hooks)) {
					
					return $executor;
				}
			}
		}

		return new Executors_Default();
	}
}