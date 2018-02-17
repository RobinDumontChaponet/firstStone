<?php

$view->setTitle('Connection');
$view->importStylesheet('style/login.css');

$view->content = function ($data) { ?>
<template id="password-template">
    <style>
		input[type=password] {
			-webkit-text-security: disc !important;
			text-shadow: .7ch 0 4px rgba(0, 0, 0, .25);
			letter-spacing: .5ch;
		}
		input[type=text] {
			-webkit-text-security: none !important;
		}

		div {
			position: relative;
		}
		#toggle {
			position: absolute;
			right: 0;
			top: 0;
			height: 100%;
			width: 16px;
			-webkit-appearance: none;
			margin: 0;
			background-image: url("data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='1024' height='1024' viewBox='0 0 1024 1024'%3E%3Cg id='icomoon-ignore'%3E%3C/g%3E%3Cpath d='M1022.88 504.832c-0.32-1.344-0.128-2.784-0.64-4.096-0.192-0.544-0.672-0.832-0.864-1.344-0.32-0.768-0.256-1.632-0.672-2.368-92.8-177.632-294.816-304.896-507.936-304.896-213.152 0-415.136 127.072-508 304.672-0.416 0.736-0.352 1.6-0.672 2.368-0.224 0.512-0.672 0.8-0.864 1.344-0.512 1.312-0.32 2.752-0.64 4.096-0.576 2.4-1.12 4.704-1.12 7.168s0.576 4.736 1.12 7.168c0.32 1.344 0.128 2.784 0.64 4.096 0.192 0.544 0.672 0.832 0.864 1.344 0.32 0.768 0.256 1.632 0.672 2.368 92.832 177.632 294.848 304.896 508 304.896 213.12 0 415.136-127.072 507.936-304.672 0.448-0.736 0.384-1.6 0.672-2.368 0.192-0.512 0.672-0.8 0.864-1.344 0.512-1.312 0.32-2.752 0.64-4.096 0.544-2.432 1.12-4.736 1.12-7.168s-0.576-4.768-1.12-7.168zM512.736 767.616c-179.68 0-355.584-102.112-443.232-255.84 88.128-153.92 263.776-255.68 443.232-255.68 179.616 0 355.552 102.144 443.2 255.84-88.128 153.952-263.744 255.68-443.2 255.68zM512.736 383.968c8.832 0 15.936 7.136 15.936 16 0 8.8-7.136 15.968-15.936 15.968v0.032c-52.928 0-95.968 43.040-95.968 95.904 0 8.832-7.168 16-15.968 16-8.832 0-16-7.168-16-16 0-70.56 57.184-127.744 127.744-127.872 0.064 0 0.128-0.032 0.192-0.032zM512 288c-123.744 0-224 100.288-224 224s100.288 224 224 224c123.68 0 224-100.32 224-224 0-123.744-100.32-224-224-224zM512 704c-105.888 0-192-86.112-192-192s86.112-192 192-192c105.888 0 192 86.112 192 192s-86.112 192-192 192z'%3E%3C/path%3E%3C/svg%3E");
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center center;
		}
    </style>
    <div role="textbox">
		<input name="password" type="password" />
		<input id="toggle" type="checkbox" value="Show Password" title="Show" />
    </div>
</template>
<header><h1>Connection</h1></header>
<main id="main" role="main">
	<form action="" method="post" name="login-form">
		<label for="user">Identifiant</label>
		<input title="Votre identifiant" id="user" name="user" type="text" value="<?= $_POST['user'] ?? ''; ?>" required autofocus placeholder="adresse email" />
		<label for="password">Mot-de-passe</label>
		<input title="Votre mot-de-passe" id="password" name="password" type="password" required />
		<?= ($data['badInput']) ? '<p class="badpass">Identifiant et/ou mot-de-passe incorrect !</p>' : ''; ?>
		<input class="<?= ($data['badInput']) ? 'warning' : 'ok'; ?>" name="submit" type="submit" value="Se connecter" title="C'est parti !" />
		<nav>
			<a href="signin" title="Rejoignez nous !">S'inscrire</a><br /><a href="forgot" rel="forgot_password" title="Let's find it !">Mot-de-passe oublié ?</a>
		</nav>
	</form>
</main>
<script type="text/javascript">
if (top.frames.length!=0) top.location=self.document.location;

function createPasswordInput(element) {
	var root = document.createElement('x-password');
	var shadowRoot = root.attachShadow({mode: 'open'});
	shadowRoot.appendChild(document.getElementById('password-template').content.cloneNode(true));

	var toggle = shadowRoot.querySelector('#toggle');

	root.input = shadowRoot.querySelector('input[type=password]');

	root.show = function () {
		root.input.type = 'text';
	}
	root.hide = function () {
		root.input.type = 'password';
	}
	toggle.addEventListener('change', function() {
		if(this.checked)
			root.show();
		else
			root.hide();
	});

	if(element) {
		var input = root.input;

		root.input = element.cloneNode(true);
		root.input.style.cssText += window.getComputedStyle(element, "").cssText;
		input.parentNode.insertBefore(root.input, input);
		input.parentNode.removeChild(input);

		element.parentNode.insertBefore(root, element);
		element.parentNode.removeChild(element);
	} else
		return root;
}

// createPasswordInput(document.querySelector('#password'));
</script>
<?php
};
