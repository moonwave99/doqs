<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

include __DIR__ . "/markdown.php";

/**
*	Resource Class - represents filesystem entry.
*/
class Resource
{

	/**
	*	@access protected
	*	@var string $path
	*/
	protected $path;

	/**
	*	Default constructor.
	*	@access public
	*/
	public function __construct($path)
	{

		$this -> path = $path;

	}

	/**
	*	Returns resource name
	*	@access public
	*	@return string
	*/
	public function getName()
	{

		return array_pop(explode("/", $this -> path));

	}

	/**
	*	Returns true if resource is a file, false if it is a folder
	*	@access public
	*	@return boolean
	*/
	public function isFile()
	{

		return stripos($this -> path, ".md") !== false;

	}

	/**
	*	Returns true if file exists
	*	@access public
	*	@return boolean
	*/
	public function exists()
	{

		return file_exists($this -> getAbsolutePath());

	}

	/**
	*	Returns full path of resource in filesystem
	*	@access public
	*	@return string
	*/
	public function getAbsolutePath()
	{

		return DOCS_PATH . $this -> path;

	}

	/**
	*	Returns web path of resource
	*	@access public
	*	@return string
	*/
	public function getWebPath()
	{

		return BASE_PATH . $this -> path;

	}

	/**
	*	Returns relative path
	*	@access public
	*	@return string
	*/
	public function getRelativePath()
	{

		return $this -> path;

	}

	/**
	*	Returns parent folder path
	*	@access public
	*	@return string
	*/
	public function getParentPath()
	{

		$xpl = explode("/", $this -> path);
		array_pop($xpl);
		return implode("/", $xpl);

	}

	/**
	*	Splits path into tokens, split at slash char
	*	@access public
	*	@return array
	*/
	public function getPathTokens()
	{

		$tokens = array(
			array(
				"label" => "Home",
				"path" => BASE_PATH
			)
		);

		$xpl = explode("/", $this -> path);

		$path = substr(BASE_PATH, 0, -1);

		foreach($xpl as $tok)
		{

			$path .= "/" . $tok;

			$tokens[] = array(
				"label" => $tok,
				"path" => $path
			);

		}

		return $tokens;

	}

	/**
	*	Returns folder content
	*	@access public
	*	@return array
	*/
	public function getContent()
	{

		if($this -> isFile())
			return false;

		$folders = array();
		$files = array();

		$res = NULL;

		if($this -> path != "")
			$folders[] = new Resource($this -> getParentPath());

		foreach(@scandir($this -> getAbsolutePath()) as $f)
		{

			if(strpos($f, ".") === 0)
				continue;

			$res = new Resource($this -> path . ($this -> path == "" ? "" : "/") . $f);
			$res -> isFile() ? $files[] = $res : $folders[] = $res;

		}

		return array_merge($folders, $files);

	}

	/**
	*	Sets resource body
	*	@access public
	*	@param string $body The body being set
	*/
	public function setBody($body)
	{

		$this -> body = $body;

	}

	/**
	*	Creates resource, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function create()
	{

		return (!$this -> isFile() || $this -> exists()) ? false : $this -> save();

	}

	/**
	*	Deletes resource, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function delete()
	{

		if(!$this -> isFile())
			return;

		return unlink($this -> getAbsolutePath());

	}

	/**
	*	Saves resource, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function save()
	{

		return @file_put_contents($this -> getAbsolutePath(), $this -> body) !== false;

	}

	/**
	*	Gets resource body
	*	@access public
	*	@param boolean $raw Prevents Markdown parsing if true
	*	@return string
	*/
	public function getBody($raw = false)
	{

		return $this -> isFile() ?
			trim(!$raw ?
				Markdown(@file_get_contents($this -> getAbsolutePath())) :
				@file_get_contents($this -> getAbsolutePath())
			) : false;

	}

}

/**
*	Navigator Class - routes and handles requests.
*/
class Navigator
{

	/**
	*	@access protected
	*	@var Resource $resource
	*/
	protected $resource;

	/**
	*	@access protected
	*	@var string $csrf
	*/
	protected $csrf;

	/**
	*	Default constructor.
	*	@access public
	*/
	public function __construct()
	{

		$this -> resource = new Resource($this -> getPatternFromURI());

		session_start();

		if( !isset($_SESSION['csrf']) )
			$_SESSION['csrf'] = sha1(microtime());

		$this -> csrf = $_SESSION['csrf'];

	}

	/**
	*	Handles client request.
	*	@access public
	*/
	public function route()
	{

		!$this -> resource -> exists() && $this -> notFound();

		if($_SERVER['REQUEST_METHOD'] == "POST"){

			!$this -> csrfCheck() && $this -> forbid();

			if($this -> resource -> isFile()){

				$this -> resource -> setBody($_POST['data']);
				$this -> resource -> save() ? print( $this -> resource -> getBody() ) : header("HTTP/1.0 500 Server Error");

				exit;

			}

			$res = new Resource(
				$this -> resource -> getRelativePath() . $_POST['fileName'] . (strpos('.md', $_POST['fileName']) !== false ? "" : ".md")
			);

			!$res -> create() && header("HTTP/1.0 409 Conflict");

			echo json_encode(array(
				'relativePath' => $res -> getRelativePath(),
				'url' => $res -> getWebPath()
			));

		}else if(isset($_GET['raw'])){

			!$this -> csrfCheck() && $this -> forbid();

			echo $this -> resource -> getBody(true);

		}else

			include( sprintf (__DIR__ . "/templates/%s.php", $this -> resource -> isFile() ? 'doc' : 'folder'));

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

		return $_REQUEST['csrf'] === $_SESSION['csrf'];

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