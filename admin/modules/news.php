<?php

$explode_url = explode("/", $_SERVER['PHP_SELF']);
end($explode_url) == basename(__FILE__) || !defined('proper_include') || !isset($_SESSION['admin']) ? exit(http_response_code(404)) : "";

$dateFrom = isset($_POST['dateFrom']) ? htmlentities($_POST['dateFrom']) : "";
$dateTo = isset($_POST['dateTo']) ? htmlentities($_POST['dateTo']) : "";
?>
<section class="panel important">
    <h2>Новини</h2>
    <table class="filter">
        <tr>
            <form method="post">
                <td class="bordernone">От дата: <input autocomplete="off" type="text" value="<?PHP ECHO $dateFrom ?>"
                                                       name="dateFrom" id="fromdate"></td>
                <td class="bordernone">До дата: <input autocomplete="off" type="text" value="<?PHP ECHO $dateTo ?>"
                                                       name="dateTo" id="todate"></td>
                <td class="bordernone"><input type="submit" class="button" name="filterDate" value="Избери"></td>
            </form>
        </tr>
    </table>
    <?php

    set_id("news");
    $pagi = pagi("news");

    if ($pagi['rows'] > 0) {
        echo $pagi['navi'];
        echo ' 	
					<table class="table">
						<tr>
							<td	class="th">Заглавие</td>
							<td	class="th">Дата</td>
							<td class="th">Промени</td>
						</tr>';
        while ($row = mysqli_fetch_array($pagi['query'])) {
            echo "
					<form method='post'>
						<tr>
							<td>" . htmlentities(base64_decode($row['title'])) . "</td>
							<td>" . myStrtotime(date("d-M-Y / H:i:s", $row['date'])) . "</td>
						<td class='edit_remove'>
							<input  type='submit' name='newsedit' value='&#xf044;' class='fa fa-input btn btn-secondary'>
							<input class='fa fa-input btn btn-secondary' type='submit' name='newsdelete' value='&#xf00d;'>
						</td>						
						<tr>
						<input type='hidden' name='newsid' value='{$row['id']}'>
					</form>";
        }
        echo " </table>";
    }
    ?>


</section>
<section class="panel important">
    <h2>Добавяне на новина</h2>
    <?php

    $title = isset($_POST['addtitle']) ? base64_encode(trim($_POST['addtitle'])) : false;
    $news = isset($_POST['addnewstext']) ? base64_encode(trim($_POST['addnewstext'])) : false;

    if ($title && $news) {
        if (isset($_POST['savenews'])) {
            mysqli_query(isDbConnected(), "Update news set title = '" . $title . "' ,content = '" . $news . "', date = '" . time() . "' WHERE id ={$_SESSION['news']['id']}  ");
        } elseif (isset($_POST['addnews'])) {
            mysqli_query(isDbConnected(), "Insert into news (title,content,date) values ('" . $title . "','" . $news . "','" . time() . "')");
        }
        $_SESSION['news'] = [];
        echo '<meta http-equiv="refresh" content="0">';
    }

    if (isset($_POST['newsid']) && (int)$_POST['newsid'] > 0) {
        $_SESSION['news'] = [];

        if (isset($_POST['newsedit'])) {
            $_SESSION['news'] = mysqli_fetch_array(mysqli_query(isDbConnected(), "Select * from news where id={$_POST['newsid']}"));
        } elseif (isset($_POST['newsdelete'])) {
            mysqli_query(isDbConnected(), "Delete from news where id='" . (int)$_POST['newsid'] . "'");
        }
        echo '<meta http-equiv="refresh" content="0">';
    }
    ?>
    <div class="twothirds">
        <form method="post">
            <p>Заглавие:</p>
            <input type="text" autocomplete="off" name="addtitle"
                   value="<?php echo htmlentities(isset($_SESSION['news']['title']) ? base64_decode($_SESSION['news']['title']) : "") ?>"
                   size="40"/><br/><br/>
            <textarea name="addnewstext"
                      height="600px"><?php echo htmlentities(isset($_SESSION['news']['content']) ? base64_decode($_SESSION['news']['content']) : "") ?></textarea><br/>
            <div class="inline">
                <?php
                $changeBtn = isset($_SESSION['news']) && count($_SESSION['news']) > 0 ?
                    '<input type="submit" class="button-big" name="savenews" value="Запази" />' :
                    '<input type="submit" class="button-big" name="addnews" value="Добави" />';
                echo $changeBtn;
                ?>
                <input type="submit" class="button-big" name="reset" value="Изчисти"/>
            </div>
        </form>
    </div>
</section>
<script src="/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
        ],

        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
        menubar: false,
        toolbar_items_size: 'small',
        height: "800",

    });
</script>

<link rel="stylesheet" type="text/css" href="/js/jquery.datetimepicker.css"/ >
<script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.min.js"></script>

<script>
    jQuery.datetimepicker.setLocale('bg');
    jQuery('#fromdate, #todate').datetimepicker({
        i18n: {
            bg: {
                months: [
                    'Януари', 'Февруари', 'Март', 'Април',
                    'Май', 'Юни', 'Юли', 'Август',
                    'Септември', 'Октомври', 'Ноември', 'Декември',
                ],
                dayOfWeek: [
                    "По", "Вт", "Ср", "Чет",
                    "Пет", "Съб", "Нед",
                ],
            }
        },
        timepicker: false,
        format: "d-m-Y"
    });
    jQuery('#eventdate').datetimepicker({

        timepicker: true,
        format: "d-m-Y H:i"
    });
</script>