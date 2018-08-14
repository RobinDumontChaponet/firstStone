<?php

use Transitive\Web;
use Transitive\Routing;
use Transitive\Routing\Route;
use Transitive\Utils;

if((include __DIR__.'/../vendor/autoload.php') === false) {
	echo 'Dependencies are not installed, please run `composer install` or `composer update`.';
	exit(1);
}
require __DIR__.'/../config/default.php';

$timed = Utils\Optimization::newTimer();

$front = new Web\Front();

$front->addRouter(new Routing\ListRouter([
	'sitemap' => new Route(PRESENTERS.'sitemap.php', VIEWS.'sitemap.php', null, ['binder' => $front])
]));
/*
$front->addRouter(new Core\ListRegexRouter([
    'articles/(?\'id\'\d*)'              => new Route(PRESENTERS.'article.php',         VIEWS.'article.php'),
    'tags/(?\'nId\'[^\/]*)/articles$'    => new Route(PRESENTERS.'tag-articles.php',    VIEWS.'tag-articles.php'),
    'tags/(?\'nId\'[^\/]*)/description$' => new Route(PRESENTERS.'tag-description.php', VIEWS.'tag-description.php'),
    'tags/(?\'id\'\d*)'                  => new Route(PRESENTERS.'tag.php',             VIEWS.'tag.php'),
]));
*/
$front->addRouter(new Routing\PathRouter(PRESENTERS, VIEWS));

// $front->obClean = false; // do not ob_get_clean to FrontController->obContent.

$front->execute(@$_GET['request'] ?? 'index');

$front->setLayoutContent(function ($data) {
?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 7]>   <html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 8]>   <html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if gt IE 8]><html class="get-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<head>
<meta charset="UTF-8">
<?= $data['view']->getMetas(); ?>
<?= $data['view']->getTitle('{{projectName}}'); ?>
<base href="<?= ($self = null == dirname($_SERVER['PHP_SELF'])) ? '/' : $self.'/'; ?>" />
<base href="<?php echo (null == constant('SELF')) ? '/' : constant('SELF').'/'; ?>" />
<link rel="author" href="humans.txt"/>
<link rel="start" href="/"/>
<!--[if IE]><link rel="shortcut icon" href="style/favicon-32.ico"><![endif]-->
<link rel="icon" href="style/favicon-96.png">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="style/favicon-144.png">
<link rel="apple-touch-icon" href="style/favicon-152.png">
<link rel="stylesheet" type="text/css" href="style/reset.min.css" />
<link rel="stylesheet" type="text/css" href="style/style.combined.css" />
<?= $data['view']->getStyles(); ?>
<!--[if lt IE 9]><script type="text/javascript" src="script/html5shiv.min.js"></script><![endif]-->
<?= $data['view']->getScripts(); ?>
</head>
<body>
	<div id="wrapper">
		<header>
			<h1><a href="<?= SELF; ?>" accesskey="1">{{projectName}}</a></h1>
		</header>
		<?php
        if($data['view']->hasContent('html'))
            echo $data['view']->getContent('html');
        else
            echo $data['view'];
    ?>
	</div>
	<footer></footer>
</body>
</html>

<?php
});

echo $front;

//echo $front->getObContent();
