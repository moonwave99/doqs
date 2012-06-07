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
  		<title><?php echo REPO_NAME ." - browsing /" . $this -> resource -> getRelativePath() ?></title>
  		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

	$tokens = $this -> resource -> getPathTokens();

foreach($tokens as $i => $token):?>
		<li <?php echo $i == count($tokens) -1 ? 'class="active"' : '' ?>>
			<?php if($i > 0):?><span class="divider">/</span><?php endif; ?>
			<a href="<?php echo $token['path'] ?>"><?php echo $token['label'] ?></a>
		</li>
<?php endforeach;?>

			</ul>

			<table class="table table-striped table-bordered">
			  <thead>
			    <tr>
			      <th>File</th>
			    </tr>
			  </thead>
			  <tbody>

				<?php foreach($this -> data['content'] as $i => $res):?>

					<tr>
						<td>
							<i class="<?php echo $res -> isFile() ? "icon-file" : "icon-" . ($i == 0 && $this -> resource -> getName() !== "" ? "arrow-up" : "folder-close") ?>"></i>
							<a class="folder" href="<?php echo $res -> getWebPath() ?>"><?php echo $i == 0 && $this -> resource -> getName() !== "" ? ".." : $res -> getName() ?></a>
						</td>
					</tr>

				<?php endforeach;?>

			  </tbody>
			</table>

			<?php include("footer.php") ?>

		</div>
	</body>
</html>