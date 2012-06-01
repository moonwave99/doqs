<?php

define('BASE_PATH', 'http://www.somesite.com/some/path/');
define('DOCS_PATH', __DIR__ . "/docs/");
define("REPO_NAME", 'Doqs');
define("REPO_DESC", 'Markdown document repository.');

include "src/doqs.php";

$nav = new Navigator;

$nav -> route();