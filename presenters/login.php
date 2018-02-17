<?php

use Transitive\Utils\Sessions as Sessions;

$badAgents = array('Java', 'Jakarta', 'User-Agent', 'compatible ;', 'libwww, lwp-trivial', 'curl, PHP/', 'urllib', 'GT::WWW', 'Snoopy', 'MFC_Tear_Sample', 'HTTP::Lite', 'PHPCrawl', 'URI::Fetch', 'Zend_Http_Client', 'http client', 'PECL::HTTP');
$presenter->add('badInput', false);
foreach($badAgents as $agent) {
    if(false !== strpos($_SERVER['HTTP_USER_AGENT'], $agent)) {
        http_response_code(403);
        $_SERVER['REDIRECT_STATUS'] = 403;

        $route->view = VIEWS.'genericHttpErrorHandler.view.php';
    }
}

if (403 != $_SERVER['REDIRECT_STATUS'] && Sessions::exist('user') && 'User' == get_class(Sessions::get('user')))
    $binder->redirect('/');
elseif (isset($_POST['submit'])) {
    if ('' == $_POST['user'] || '' == $_POST['password']) {
        $presenter->add('badInput', true);
    } else {
        $user = UserDAO::getByLogin($_POST['user']);
        if (null != $user) {
            if (empty($user) || !password_verify($_POST['password'], $user->getPasswordHash())) {
                $presenter->add('badInput', true);
                sleep(1);
            } else {
                Sessions::set('user', $user);
                $user->connect();
/*
                if(!empty($_SESSION['referrer']) && $_SESSION['referrer']!='login' && $_SESSION['referrer']!='logout')
                    $request->redirect($_SESSION['referrer']);
                else
*/
                    $binder->redirect('/');
            }
        } else
            $presenter->add('badInput', true);
    }
}
