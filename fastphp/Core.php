<?php
class Fast{

	function run()
	{
		spl_autoload_register(array($this,'loadClass'));
		$this->setReporting();
		$this->removeMagicQuotes();
		$this->unregisterGlobals();
		$this->callHook();
	}

	function callHook()
	{
		if(!empty($_GET['url'])){
			$url = $_GET['url'];
			$urlArray = explode("/",$url);

			$controllerName = ucfirst(empty($urlArray[0]) ? 'Index' : $urlArray[0]);
			$controller = $controllerName.'Controller';

			array_shift($urlArray);
			$action = empty($urlArray[0]) ? 'index' : $urlArray[0];

			array_shift($urlArray);
			$queryString = empty($urlArray) ? array() : $urlArray;
		}

		$action = empty($action) ? 'index' : $action;
		$queryString = empty($queryString) ? array() : $queryString;
	}
}