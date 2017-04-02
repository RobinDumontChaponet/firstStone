<?php

namespace Transitive;

require_once __DIR__.'/../vendor/autoload.php';

set_include_path(__DIR__.'/../config');
require 'default.php';

$transit = new Core\FrontController();

$transit->addRouter(new Core\PathRouter(PRESENTERS, VIEWS));

$transit->execute(@$_GET['request'] ?? 'index');

$transit->layout = function ($transit) {
?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 7]>   <html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 8]>   <html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if gt IE 8]><html class="get-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<head>
<meta charset="UTF-8">
<?php $transit->printMetas() ?>
<?php $transit->printTitle('{{projectName}}') ?>
<base href="<?php echo (constant('SELF') == null) ? '/' : constant('SELF').'/'; ?>" />
<!--[if IE]><link rel="shortcut icon" href="style/favicon-32.ico"><![endif]-->
<link rel="icon" href="style/favicon-96.png">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="style/favicon-144.png">
<link rel="apple-touch-icon" href="style/favicon-152.png">
<link rel="stylesheet" type="text/css" href="style/reset.min.css" />
<link rel="stylesheet" type="text/css" href="style/style.combined.css" />
<?php $transit->printStyles() ?>
<!--[if lt IE 9]><script type="text/javascript" src="script/html5shiv.min.js"></script><![endif]-->
<?php $transit->printScripts() ?>
</head>
<body>
	<div id="wrapper">
		<header>
			<h1><a href="<?= SELF ?>" accesskey="1">{{projectName}}</a></h1>
		</header>
		<?php $transit->printContent(); ?>
	</div>
	<footer></footer>
</body>
</html>

<?php
};

$transit->print();
