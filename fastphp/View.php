<?php
class View
{
	protected $variables = array();
	protected $_controller;
	protected $_action;

	function __construct($controller,$action)
	{
		$this->_controller = $controller;
		$this->_action = $action;

	}

	public function assign($name,$value)
	{
		$this->variables[$name] = $value;
	}

	public function render()
	{
		extract($this->variables);
		$defaultHeader = APP_PATH.'application/views/header.php';
		$defaultFooter = APP_PATH.'application/views/footer.php';

		$controllerHeader = APP_PATH.'application/views/'.$this->_controller.'header.php';
		$controllerFooter = APP_PATH.'application/views/'.$this->_controller.'/footer.php';
		$controllerLayout = APP_PATH.'application/views/'.$this->_controller.'/'.$this->_action.'.php';

		if(file_exists($controllerHeader)){
			include($controllerHeader);
		}else{
			include($defaultHeader);
		}

		include($controllerLayout);

		if(file_exists($controllerFooter)){
			include($controllerFooter);
		}else{
			include ($defaultFooter);
		}
	}
}