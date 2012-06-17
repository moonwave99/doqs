<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

include __DIR__ . "/lib/markdown.php";

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
	*	@param string $path The resource path
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
	*	Renames resource
	*	@access public
	*	@param string $name The new resource name
	*	@return boolean
	*/
	public function rename($name)
	{

		if(file_exists( DOCS_PATH . $name )) return false;

		return @rename($this -> getAbsolutePath(), DOCS_PATH . $name) && $this -> path = $name;

	}

}

class File extends Resource
{

	/**
	*	Sets resource body
	*	@access public
	*	@param string $body The body being set
	*/
	public function setBody($body)
	{

		$this -> body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');

	}

	/**
	*	Gets resource body
	*	@access public
	*	@param boolean $raw Prevents Markdown parsing if true
	*	@return string
	*/
	public function getBody($raw = false)
	{

		return trim(!$raw ?
			Markdown(@file_get_contents($this -> getAbsolutePath())) :
			@file_get_contents($this -> getAbsolutePath())
		);

	}

	/**
	*	Creates file, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function create()
	{

		return $this -> exists() ? false : $this -> save();

	}

	/**
	*	Saves file, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function save()
	{

		return @file_put_contents($this -> getAbsolutePath(), $this -> body) !== false;

	}

	/**
	*	Deletes file, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function delete()
	{

		return unlink($this -> getAbsolutePath());

	}

}

class Folder extends Resource
{

	/**
	*	Returns folder content
	*	@access public
	*	@return array
	*/
	public function getContent()
	{

		$folders = array();
		$files = array();

		$res = NULL;

		if($this -> path != "")
			$folders[] = new Folder($this -> getParentPath());

		foreach(@scandir($this -> getAbsolutePath()) as $f)
		{

			if(strpos($f, ".") === 0)
				continue;

			is_file(DOCS_PATH . ($path = $this -> path . ($this -> path == "" ? "" : "/") . $f))  ? $files[] = new File($path) : $folders[] = new Folder($path);

		}

		return array_merge($folders, $files);

	}

	/**
	*	Checks if folder is root path
	*	@access public
	*	@return boolean
	*/
	public function isRoot()
	{

		return $this -> getAbsolutePath() == DOCS_PATH;

	}

	/**
	*	Creates folder, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function create()
	{

		return $this -> exists() ? false : @mkdir($this -> getAbsolutePath());

	}

	/**
	*	Deletes folder, returns true if succesfully
	*	@access public
	*	@return boolean
	*/
	public function delete()
	{

		return rmdir($this -> getAbsolutePath());

	}

}