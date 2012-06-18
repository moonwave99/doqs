<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

define('DOCS_PATH', __DIR__ . "/docs/");
define("REPO_NAME", 'Doqs');
define("REPO_DESC", 'Markdown document repository.');

include __DIR__ . "/src/router.php";
include __DIR__ . "/src/controller.php";
include __DIR__ . "/src/entities.php";

$router = new Router();
$router -> route();