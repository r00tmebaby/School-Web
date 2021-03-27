<?php
define('proper_include', True);
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" media="all" href="/admin/css/style.css">
    <script src="//kit.fontawesome.com/d2377df7c7.js" crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href='//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css' rel='stylesheet'
          type='text/css'>
    <title>Управление на сайта</title>
    <link rel="SHORTCUT ICON" href="images/favicon.ico"/>
</head>
<body class="hideScroll">
<?php
$page = isset($_GET['module']) ? $_GET['module'] : "";
$style1 = $style2 = $style3 = $style4 = $style5 = $style6 = $style7 = $style8 = "";

switch ($page) {
    case "news":
        $style1 = "active";
        break;
    case "events":
        $style2 = "active";
        break;
    case "subjects":
        $style3 = "active";
        break;
    case "projects":
        $style4 = "active";
        break;
    case "regulations":
        $style5 = "active";
        break;
    case "personel":
        $style6 = "active";
        break;
    case "gallery":
        $style7 = "active";
        break;
    case "posts":
        $style8 = "active";
        break;
    default:
        $style1 = "active";
}

if (isset($_POST['logout'])) {
    session_destroy();
    echo "<meta http-equiv='refresh' content='0'>";
}
if (!isset($_SESSION['admin'])) {
    echo '	<div class="login">
						<div class="login-triangle"></div>
						<h2 class="login-header">Админ Панел</h2>
					<form class="login-container" method="POST">
						<p><input type="username"  placeholder="Акаунт" name="username" ></p>
						<p><input type="password" placeholder="Парола" name="password" ></p>
						<p><input type="submit"  name="login" value="Влез"></p>
					</form>';
    if (isset($_POST['login'])) {
        if (in_array(null, array($_POST['username'], $_POST['password']))) {
            showMessage("Празни полета!", "Моля, попълните всички полета.", "danger");
        } elseif (!only_numbers_and_letters(array($_POST['username'], $_POST['password']), "en")) {
            showMessage("Невалидни данни!", "Моля, въведете само цифри и букви от английската азбука.", "danger");
        } else {
            if (isAccount($_POST['username'], $_POST['password'])) {
                $_SESSION['admin'] = $_POST['username'];
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                showMessage("Невалидни данни!", "Въвели сте грешно потребителско име или парола.", "danger");
            }
        }
    }
    echo '</div>';
} else {
    echo '
			<header role="banner">
				<h1>Управление на сайта</h1>
				<ul class="utilities" style="padding:17px 17px">				
					<form method="post">
						<input type="submit" class="btn btn-secondary" name="logout"  class="logout warn" value="Изход">
     				<form>				
				</ul>
			</header>

			<nav role="navigation">
				<ul class="main">
					<li class="' . $style1 . '"><a href="?module=news"><i  class="fas fa-rss-square"></i> Новини</a></li>
					<li class="' . $style2 . '"><a href="?module=events"><i   class="far fa-calendar-alt"></i> Събития</a></li>
					<li class="' . $style3 . '"><a href="?module=subjects"><i  class="fas fa-graduation-cap"></i> Специалности</a></li>
					<li class="' . $style4 . '"><a href="?module=projects"><i  class="fas fa-project-diagram"></i> Проекти</a></li>
					<li class="' . $style5 . '"><a href="?module=regulations"><i  class="far fa-file-word"></i> Наредби</a></li>
					<li class="' . $style6 . '"><a href="?module=personel"> <i  class="fas fa-users"></i> Персонал</a></li>
					<li class="' . $style7 . '"><a href="?module=gallery"><i class="far fa-images"></i> Галерия</a></li>
					<li class="' . $style8 . '"><a href="?module=posts"><i class="far fa-images"></i> Мотивационни Постове</a></li>
				</ul>
			</nav>
			<main role="main">';

    isset($_GET['module']) && is_file("modules/" . $_GET['module'] . ".php") ? include_once("modules/" . $_GET['module'] . ".php") : include_once("modules/news.php");

    echo '	</main>	';

}
?>
</body>
<script src="//code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</html>
