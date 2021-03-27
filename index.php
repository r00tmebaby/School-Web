<?php
header('Content-Type: text/html; charset=utf-8');
include $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>

<!DOCTYPE html>
<!--[if IE 8]>
<html class="ie8 oldie" lang="bg"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="bg_BG"> <!--<![endif]-->
<head>
    <title>ПГТ Пенчо Семов Габрово</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="//kit.fontawesome.com/d2377df7c7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" media="all" href="/css/style.css">
    <link rel="stylesheet" media="all" href="/css/spec.css">
    <link rel="SHORTCUT ICON" href="images/favicon.ico"/>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="hideScroll">

<header id="header">
    <div class="container">
        <a href='/main' id="logo" title="ПГТ Пенчо Семов Габрово">ПГТ Пенчо Семов Габрово</a>
        <div class="menu-trigger"></div>
        <nav id="menu">
            <ul>
                <li><a href='/news'>Новини</a></li>
                <li><a href='/events'>Събития</a></li>
                <li><a href="/specialties">Специалности</a></li>
                <li><a href="/projects">Проекти</a></li>
            </ul>
            <ul>
                <li><a href="/regulations">Наредби</a></li>
                <li><a href='/personel'>Екип</a></li>
                <li><a href='/gallery'>Галерия</a></li>
                <li><a href="#fancy" class="get-contact">Кандидатствай</a></li>
            </ul>
        </nav>
        <!-- / navigation -->
    </div>
    <!-- / container -->

</header>
<!-- / header -->

<!-- Main Content -->
<div id="main">
    <?php
    $modulesPath = 'modules/' . getRoute()['page'] . ".php";
    is_file($modulesPath) ? include_once($modulesPath) : include_once 'modules/main.php';
    ?>
</div>
<!-- / Main Content -->

<footer id="footer">
    <div class="container">
        <section>
            <article class="col-1">
                <h3>За Контакти</h3>
                <ul>
                    <li class="address"><a href="#">ул. „Бенковска“ 18<br>Габрово, 5300</a></li>
                    <li class="mail"><a href="#">pgtgabrovo@globcom.net</a></li>
                    <li class="phone last"><a href="#">(066) 801073</a></li>
                </ul>
            </article>
            <article class="col-2">
                <h3>Социални Медии</h3>
                <ul>
                    <li class="facebook"><a href="https://www.facebook.com/pgt.p.semov" target="_blank">Facebook</a>
                    </li>
                </ul>
            </article>
        </section>
        <p class="copy">Copyright <?php echo date("Y") ?> ПГТ Пенчо Семов Габрово. По проект на <a
                    href="https://github.com/enemyssss" target="_blank">Лальо Бояджиев</a> и <a
                    href="https://github.com/r00tmebaby" target="_blank"> Здравко Георгиев </a> .Всички права запазени.

        </p>
    </div>
    <!-- / container -->
</footer>
<!-- / footer -->

<div id="fancy">
    <h2>Информация за кандидатстване</h2>
    <article>
        <h3>Къде да изпратим нашите документи?</h3>
        <p> - Кандидастването не се извършва директно в училището, а при РУО Габрово.</p>
        <p>За повече информация - <a href="http://ruo-gabrovo.org/index.php/bg/" target="_blank">ТУК</a></p>
    </article>
</div>

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>window.jQuery || document.write("<script src='/js/jquery-1.11.1.min.js'>\x3C/script>")</script>
<script src="/js/plugins.js"></script>
<script src="/js/main.js"></script>
<script src="/js/scripts.js"></script>
</body>
</html>

