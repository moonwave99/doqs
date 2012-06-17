<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

class Controller
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
	*	Default constructor.
	*	@access public
	*	@param string $csrf CSRF token stored in session
	*	@param Request $request Request instance coming from router
	*/
	public function __construct($csrf, $request)
	{

		$this -> csrf = $csrf;
		$this -> request = $request;

	}

	public function getFolder($folder)
	{

		if($this -> request -> format == 'json'){

			$response = array('folders' => array(), 'files' => array(), 'root' => $folder -> isRoot());

			foreach($folder -> getContent() as $res)
			{

				$response[ get_class($res) == 'File' ? 'files' : 'folders' ][] = array(
					'name' => $res -> getName(),
					'path' => $res -> getRelativePath()
				);

			}

			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			echo json_encode($response);
			exit;

		}

		include( __DIR__ . "/templates/folder.php" );

	}

	public function getFile($file)
	{

		$this -> request -> raw !== NULL && die( $file -> getBody(true) );

		include( __DIR__ . "/templates/file.php" );

	}

	public function postFolder($folder)
	{

		if($this -> request -> fileName){

			$res = new File($folder -> getRelativePath() . "/" . $this -> request -> fileName . (strpos($this -> request -> fileName, '.md') === false ? '.md' : ''));

		}else{

			$res = new Folder($folder -> getRelativePath() . "/" . $this -> request -> folderName);

		}

		!$res -> create() && header("HTTP/1.0 409 Conflict");

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo json_encode(array(
			'name' => $res -> getName(),
			'path' => $res -> getRelativePath()
		));

	}

	public function putFolder($res)
	{

		!$res -> rename($this -> request -> resName) && header("HTTP/1.0 409 Conflict");

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo json_encode(array(
			'name' => $res -> getName(),
			'path' => $res -> getRelativePath()
		));

	}

	public function putFile($file)
	{

		if($this -> request -> resName){

			!$file -> rename($this -> request -> resName) && header("HTTP/1.0 409 Conflict");

			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			echo json_encode(array(
				'name' => $file -> getName(),
				'path' => $file -> getRelativePath()
			));

		}else{

			$file -> setBody($this -> request -> data);
			$file -> save() ? print( $file -> getBody() ) : header("HTTP/1.0 500 Server Error");

		}

	}

	public function deleteFolder($folder)
	{

		header($folder -> delete() ? "HTTP/1.0 204 No Content" : "HTTP/1.0 500 Server Error");

	}

	public function deleteFile($file)
	{

		header($file -> delete() ? "HTTP/1.0 204 No Content" : "HTTP/1.0 500 Server Error");

	}

}