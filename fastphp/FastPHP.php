<?php
class Fastphp{
	protected $_config = [];
	public function __construct($config)
	{
		$this->_config = $config;
	}


	public function run()
	{	
		spl_autoload_register(array($this,'loadClass'));
		$this->setReporting();
		$this->removeMagicQuotes();
		$this->unregisterGlobals();
		$this->setDbConfig();
		$this->route();
	}

	public function route()
	{
		$controllerName = $this->_config['defaultController'];
		$actionName = $this->_config['defaultAction'];
		$param = array();

		$url = $_SERVER['REQUEST_URI'];

		$l_url = getcwd();
		$l_url = trim($l_url,'/');
		$l_url = explode("\\",$l_url);
		$l_url =array_pop($l_url);
		$l_position = strpos($url,$l_url);
		$url = substr($url,$l_position,0);

		$position = strpos($url,'?');
		$url = $position === false ? $url : substr($url, 0 ,$position);

		$url = trim($url,'/');

		if($url){
			$urlArray = explode("/",$url);
			
			$urlArray = array_filter($urlArray);

			$controllerName = ucfirst($urlArray[0]);

			array_shift($urlArray);
			$actionName = $urlArray ? $urlArray[0] : $actionName;

			array_shift($urlArray);
			$queryString = empty($urlArray) ? array() : $urlArray;

			//获取url参数
			array_shift($urlArray);
			$param = $urlArray ? $urlArray : array();
		}
		
		$controller = $controllerName.'Controller';
		if(!class_exists($controller)){
			exit($controller.'控制器不存在');
		}
		if(!method_exists($controller,$actionName)){
			exit($actionName.'方法不存在');
		}

		$dispath = new $controller($controllerName,$actionName);

		call_user_func_array(array($dispath,$actionName),$param);
		
	}

	function setReporting()
	{
		if(APP_DEBUG == true){
			error_reporting(E_ALL);//php错误报告级别
			ini_set('display_errors','On');

		}else{
			error_reporting(E_ALL);
			ini_set('display_errors','Off');
			ini_set('log_errors','On');
			//ini_set('error_log',RUNTIME_PATH.'logs/error.log');

		}
	}

	function stripSlashesDeep($value)
	{
		//stripslashes()去除转义字符
		$value = is_array($value) ? array_map('stripSlashesDeep',$value) : stripslashes($value);
		return $value;
	}

	function removeMagicQuotes()
	{
		if(get_magic_quotes_gpc()){
			$_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : '';
			$_POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : '';
			$_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
			$_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
		}
	}

	function unregisterGlobals()
	{
		if(ini_get('register_globals')){
			$array = array('_SESSION','_POST','_GET','_COOKIE','_REQUEST','_SERVER','_ENV','_FILES');
			foreach($array as $value){
				foreach($GLOBALS[$value] as $key => $var){
					if($var === $GLOBALS[$key]){
						unset($GLOBALS[$key]);
					}
				}
			}
		}
	}

	public function setDbConfig()
	{
		if($this->_config['db']){
			Model::setDbConfig($this->_config['db']);
		}
	}
	static function loadClass($class)
	{
		$frameworks = __DIR__.'/'.$class.'.php';//调用fastphp文件夹下 的框架文件
		$controllers = APP_PATH.'application/controllers/'.$class.'.php';
		$models = APP_PATH.'application/models/'.$class.'.php';
		if(file_exists($frameworks)){
			include $frameworks;
		}elseif(file_exists($controllers)){
			include $controllers;
		}elseif(file_exists($models)){
			include $models;
		}else{
			
		}
	}
}