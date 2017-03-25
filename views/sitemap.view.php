<?php

$view->setTitle('Sitemap');

$view->content = function ($data) {
?>

<main role="main">
	<h1>routeMap/siteMap :</h1>
	<ul>
	<?php foreach($data['routes'] as $pattern => $route)
		echo '<li><a href="'.$pattern.'">'.$pattern.'</a></li>', PHP_EOL;
    ?>
	</ul>
</main>

<?php
};
