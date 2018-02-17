<?php


require_once __DIR__.'/../vendor/autoload.php';
set_include_path(__DIR__.'/../config');
require 'default.php';

use Transitive\Core;
use Transitive\Utils;

Utils\Sessions::start();
$front = new Core\WebFront();
// $front->obClean = false;

/*
$front->addRouter(new Core\ListRegexRouter([
    'articles/(?\'id\'\d*)'              => new Route(PRESENTERS.'article.php',         VIEWS.'article.php'),
    'tags/(?\'nId\'[^\/]*)/articles$'    => new Route(PRESENTERS.'tag-articles.php',    VIEWS.'tag-articles.php'),
    'tags/(?\'nId\'[^\/]*)/description$' => new Route(PRESENTERS.'tag-description.php', VIEWS.'tag-description.php'),
    'tags/(?\'id\'\d*)'                  => new Route(PRESENTERS.'tag.php',             VIEWS.'tag.php'),
]));
*/
$front->addRouter(new Core\ListRouter([
    'sitemap' => new Core\Route(PRESENTERS.'sitemap.php', VIEWS.'sitemap.php', null, ['binder' => $front]),
]));
$front->addRouter(new Core\PathRouter(PRESENTERS, VIEWS));

$request = @$_GET['request'];
$front->execute($request ?? 'index');

$front->setLayoutContent(function ($data) use ($request) {
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
