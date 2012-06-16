<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

define('BASE_PATH', 'http://somesite.com/path/to/');
define('DOCS_PATH', __DIR__ . "/docs/");
define("REPO_NAME", 'Doqs');
define("REPO_DESC", 'Markdown document repository.');

include __DIR__ . "/src/doqs.php";

$nav = new Navigator;

$nav -> route();