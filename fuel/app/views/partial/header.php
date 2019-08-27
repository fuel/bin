<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="/assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta name="author" content="<?=$author?>" />
		<title><?=$title?></title>
		<link rel="stylesheet" type="text/css" href="/assets/css/bin.css" />
		<script src="/assets/js/require.config.js"></script>
		<script data-main="bin-built" src="/assets/js/require.js"></script>
	</head>
	<body class="<?=$body_class?>">
		<header>
			<div class="column">
				<a id="site-title" href="<?=Uri::base()?>">FuelPHP Bin</a>
<?=$login?>
			</div>
		</header>
