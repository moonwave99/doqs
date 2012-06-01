<?php

include __DIR__ . "/markdown.php";

function pre($stuff)
{

	echo '<pre>';
	print_r($stuff);
	echo '</pre>';

}

class Resource
{

	protected $path;

	public function __construct($path)
	{

		$this -> path = $path;

	}

	public function getName()
	{

		return array_pop(explode("/", $this -> path));

	}

	public function isFile()
	{

		return stripos($this -> path, ".md") !== false;

	}

	public function exists()
	{

		return file_exists($this -> getAbsolutePath());

	}

	public function getAbsolutePath()
	{

		return DOCS_PATH . $this -> path;

	}

	public function getWebPath()
	{

		return BASE_PATH . $this -> path;

	}

	public function getRelativePath()
	{

		return $this -> path;

	}

	public function getParentPath()
	{

		$xpl = explode("/", $this -> path);
		array_pop($xpl);
		return implode("/", $xpl);

	}

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

	public function getBody()
	{

		return $this -> isFile() ? Markdown(file_get_contents($this -> getAbsolutePath())) : false;

	}

}

class Navigator
{

	protected $resource;

	protected $data;

	public function __construct()
	{

		$this -> data = array();

		$this -> resource = new Resource($this -> getPatternFromURI());

	}

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

	public function route()
	{

		!$this -> resource -> exists() && $this -> notFound();

		if($this -> resource -> isFile())
		{

			include(__DIR__ . "/templates/doc.php");

		}else{

			$this -> data['content'] = $this -> resource -> getContent();

			include(__DIR__ . "/templates/folder.php");

		}

	}

	protected function notFound()
	{

		header("HTTP/1.0 404 Not Found");
		include(__DIR__ . "/templates/404.php");
		exit;

	}

}