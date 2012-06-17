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
  		<title><?php echo REPO_NAME ." - browsing /" . $folder -> getRelativePath() ?></title>
  		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="csrf" content="<?php echo $this -> csrf ?>"/>
  		<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>web/css/bootstrap.min.css"/>
  		<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>web/css/main.css"/>
		<style>@media print {#generated-toc{display:none!important}}</style>
	</head>
	<body>

		<?php include("nav.php") ?>

  		<div class="container">

			<p><?php echo REPO_DESC ?></p>

			<ul class="breadcrumb">
				<li><i class="icon-home"></i></li>
<?php

	$tokens = $folder -> getPathTokens();

foreach($tokens as $i => $token):?>
		<li <?php echo $i == count($tokens) -1 ? 'class="active"' : '' ?>>
			<?php if($i > 0):?><span class="divider">/</span><?php endif; ?>
			<a href="<?php echo $token['path'] ?>"><?php echo $token['label'] ?></a>
		</li>
<?php endforeach;?>

			</ul>

			<table id="folder-table" class="table table-striped table-bordered" data-controller="folder" data-action="fetch" data-startup>
			  <thead>
			    <tr>
			      <th>File</th>
				  <th>Actions</th>
			    </tr>
			  </thead>
			  <tbody>

			  </tbody>
			</table>
			<p class="folder-actions">
				<a class="btn btn-mini btn-primary" href="#" data-controller="folder" data-action="newDoc"><i class="icon-file icon-white"></i> New Document</a>
				<a class="btn btn-mini btn-primary" href="#" data-controller="folder" data-action="newFolder"><i class="icon-folder-open icon-white"></i> New Folder</a>
			</p>
			<?php include("footer.php") ?>

		</div>

		<?php include("templates.php") ?>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo BASE_PATH ?>web/js/underscore-min.js"></script>
		<script>var basePath = '<?php echo BASE_PATH ?>';</script>
		<script src="<?php echo BASE_PATH ?>web/js/scripts.js"></script>
	</body>
</html>