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
  		<title><?php echo REPO_NAME ." - Reading " . $this -> resource -> getName() ?></title>
  		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  		<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>web/css/bootstrap.min.css"/>
  		<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>web/css/main.css"/>
	</head>
	<body class="normal swiss">

		<?php include("nav.php") ?>

  		<div class="container">
			<p>
				<a class="btn btn-mini btn-primary" href="<?php echo BASE_PATH . $this -> resource -> getParentPath() ?> ">Back to Folder</a>
			</p>
			<div class="well doc">

			<?php echo $this -> resource -> getBody() ?>

			</div>

			<?php include("footer.php") ?>

		</div>
	</body>
</html>