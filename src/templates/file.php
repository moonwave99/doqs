<?php

/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

?>
<!DOCTYPE html>
<html>
	<head>
  		<meta charset="utf-8"/>
  		<title><?php echo REPO_NAME ." - Reading " . $file -> getName() ?></title>
  		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="csrf" content="<?php echo $this -> csrf ?>"/>
  		<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>web/css/bootstrap.min.css"/>
  		<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>web/css/main.css"/>
	</head>
	<body class="normal swiss">

		<?php include("nav.php") ?>

  		<div class="container">
			<p>
				<a class="btn btn-mini btn-inverse" href="<?php echo BASE_PATH . $file -> getParentPath() ?> "><i class="icon-arrow-left icon-white"></i> Back to Folder</a>
				<a class="btn btn-mini btn-primary" href="#" data-controller="docs" data-action="showEditPane"><i class="icon-pencil icon-white"></i> Edit Document</a>
			</p>
			<div class="well doc">

				<div id="box" style="display:none">

					<h2 class="title">
						Editing <?php echo $file -> getName() ?>
						<span class="pull-right">
						<a class="btn btn-mini btn-primary" href="#" data-controller="docs" data-action="save"><i class="icon-ok icon-white"></i> Save Changes</a>
						<a class="btn btn-mini" href="#original"><i class="icon-file"></i> Original</a>
						<a class="btn btn-mini" href="#" data-controller="docs" data-action="hideEditPane"><i class="icon-remove "></i> Close</a>
						</span>

					</h2>

					<div id="editor"></div>

				</div>

				<div id="original">

					<?php echo $file -> getBody() ?: 'This document is empty.' ?>
				</div>

			</div>

			<?php include("footer.php") ?>

		</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo BASE_PATH ?>web/js/underscore-min.js"></script>
		<script src="<?php echo BASE_PATH ?>web/js/ace.js"></script>
		<script src="<?php echo BASE_PATH ?>web/js/theme-twilight.js"></script>
		<script src="<?php echo BASE_PATH ?>web/js/mode-markdown.js"></script>
		<script src="<?php echo BASE_PATH ?>web/js/scripts.js"></script>
	</body>
</html>