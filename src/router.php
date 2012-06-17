<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

/**
*	Router Class - routes requests to controller.
*/
class Router
{

	/**
	*	@access protected
	*	@var string $csrf
	*/
	protected $csrf;

	/**
	*	@access protected
	*	@var Request $request
	*/
	protected $request;

	/**
	*	@access protected
	*	@var Controller $controller
	*/
	protected $controller;

	/**
	*	Default constructor.
	*	@access public
	*/
	public function __construct()
	{

		session_start();

		if( !isset($_SESSION['csrf']) )
			$_SESSION['csrf'] = sha1(microtime());

		$this -> csrf = $_SESSION['csrf'];
		$this -> request = new Request();
		$this -> controller = new Controller($this -> csrf, $this -> request);

	}

	/**
	*	Handles client request.
	*	@access public
	*/
	public function route()
	{

		!file_exists(DOCS_PATH . ($path = $this -> getPatternFromURI())) && $this -> notFound();

		$this -> request -> getMethod() !== 'GET' && !$this -> csrfCheck() && $this -> forbid();

		$this -> controller -> {strtolower($this -> request -> getMethod()) . (is_file(DOCS_PATH . $path) ? 'File' : 'Folder')}((is_file(DOCS_PATH . $path) ? new File($path) : new Folder($path)));

	}

	/**
	*	Returns resource path from full URI
	*	@access protected
	*	@return string
	*/
	protected function getPatternFromURI()
	{

		$xpl = str_replace(array_pop(explode(' ', '^(.*)$ index.php')), '', $_SERVER['SCRIPT_NAME']);

		$route = explode('?', str_replace(
			$xpl == "/" ? '' : $xpl,
			'',
			$_SERVER['REQUEST_URI']
		));

		return strpos($_SERVER['REQUEST_URI'], 'index.php') !== false ?
			array_shift(explode('&', array_pop($route))) :
			array_shift($route);

	}

	/**
	*	Checks if request CSRF token matches the one stored in session
	*	@access protected
	*	@return boolean
	*/
	protected function csrfCheck()
	{

		return $this -> request -> csrf === $this -> csrf;

	}

	/**
	*	Returns a 403 response to client
	*	@access protected
	*/
	protected function forbid()
	{

		header('HTTP/1.0 403 Forbidden');
		exit;

	}

	/**
	*	Returns a 404 response to client
	*	@access protected
	*/
	protected function notFound()
	{

		header("HTTP/1.0 404 Not Found");
		include( __DIR__ . "/templates/404.php");
		exit;

	}

}

/**
*	Request Class - wraps HTTP request.
*/
class Request
{

	/**
	*	@access protected
	*	@var array $values
	*/
	protected $values;

	/**
	*	@access protected
	*	@var string $method
	*/
	protected $method;

	/**
	*	Default constructor.
	*	@access public
	*/
	public function __construct()
	{

		$this -> values = array();

		$this -> method = $_SERVER['REQUEST_METHOD'];

		$this -> cleanRequest();

	}

	/**
	*	Magic getter.
	*	@access public
	*	@param string $key The key looking for
	*	@return string
	*/
	public function __get($key)
	{

		return $this -> values[$key];

	}

	/**
	*	Returns request method.
	*	@access public
	*	@return string
	*/
	public function getMethod(){ return $this -> method; }

	/**
	*	Cleans request against XSS, listens to raw input in PUT/DELETE requests, fills $values array.
	*	@access public
	*/
	protected function cleanRequest()
	{

		$_RAW = array();

		($this -> method == 'PUT' || $this -> method == 'DELETE') && parse_str(file_get_contents('php://input'), $_RAW);

		foreach(array($_GET, $_POST, $_RAW, $_COOKIE, $_SERVER) as $req)
		{

			if (get_magic_quotes_gpc())
			{

			    $strip_slashes_deep = function ($value) use (&$strip_slashes_deep) {
			        return is_array($value) ? array_map($strip_slashes_deep, $value) : stripslashes($value);
			    };

			    $req = array_map($strip_slashes_deep, $req);

			}

			foreach($req as $key => $val)
			{

			    $entities = function ($value) use (&$entities) {
			        return is_array($value) ? array_map($entities, $value) : htmlentities($value, ENT_QUOTES, 'UTF-8');
			    };

				$this -> values[$key] = $entities($val);

			}

		}

		unset($_GET);
		unset($_POST);
		unset($_RAW);
		unset($_COOKIE);

	}

}