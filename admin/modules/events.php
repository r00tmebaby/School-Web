<?php

$explode_url = explode("/", $_SERVER['PHP_SELF']);
end($explode_url) == basename(__FILE__) || !defined('proper_include') || !isset($_SESSION['admin']) ? exit(http_response_code(404)) : "";


$dateFrom = isset($_POST['dateFrom']) ? htmlentities($_POST['dateFrom']) : "";
$dateTo = isset($_POST['dateTo']) ? htmlentities($_POST['dateTo']) : "";
?>
<section class="panel important">
    <h2>Събитие</h2>
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

    $title = isset($_POST['addtitle']) ? base64_encode($_POST['addtitle']) : false;
    $events = isset($_POST['addeventstext']) ? base64_encode(trim($_POST['addeventstext'])) : false;
    $date = isset($_POST['eventdate']) ? $_POST['eventdate'] : false;
    $location = isset($_POST['location']) ? base64_encode($_POST['location']) : false;

    if ($title && $events && validDate(explode(" ", $date)[0]) && $location) {
        if (isset($_POST['saveevents'])) {
            mysqli_query(isDbConnected(), "Update events set title = '" . $title . "' ,content = '" . $events . "', date = '" . strtotime($date) . "', location ='" . $location . "' WHERE id ={$_SESSION['events']['id']}  ");
        } elseif (isset($_POST['addevents'])) {
            mysqli_query(isDbConnected(), "Insert into events (title,content,date, location) values ('" . $title . "','" . $events . "','" . strtotime($date) . "', '" . $location . "' )");
        }
        $_SESSION['events'] = [];

    }

    if (isset($_POST['eventsid']) && (int)$_POST['eventsid'] > 0) {
        $_SESSION['events'] = [];

        if (isset($_POST['eventsedit'])) {
            $_SESSION['events'] = mysqli_fetch_array(mysqli_query(isDbConnected(), "Select * from events where id={$_POST['eventsid']}"));
            $_SESSION['events']['date'] = date("d-m-Y H:i", $_SESSION['events']['date']);
        } elseif (isset($_POST['eventsdelete'])) {
            mysqli_query(isDbConnected(), "Delete from events where id='" . (int)$_POST['eventsid'] . "'");
        }
    }


    set_id("events");
    $pagi = pagi("events");

    if ($pagi['rows'] > 0) {
        echo $pagi['navi'];
        echo ' 	
				<table class="table">
					<tr>
						<td	class="th">Събитие</td>
						<td	class="th">Дата</td>
						<td	class="th">Локация</td>
						<td class="th">Промени</td>
					</tr>
				';
        while ($row = mysqli_fetch_array($pagi['query'])) {
            echo "<form method='post'>
							<tr>
								<td>" . base64_decode($row['title']) . "</td>
								<td>" . myStrtotime(date("d-M-Y / H:i:s", $row['date'])) . "</td>
								<td>" . base64_decode($row['location']) . "</td>
							<td class='edit_remove'>
								<input  type='submit' name='eventsedit' value='&#xf044;' class='fa fa-input btn btn-secondary'>
								<input class='fa fa-input btn btn-secondary' type='submit' name='eventsdelete' value='&#xf00d;'>
							</td>					
							<tr>
							<input type='hidden' name='eventsid' value='{$row['id']}'>
						</form>";
        }
        echo "</table>";
    }
    ?>
</section>
<section class="panel important">
    <h2>Добавяне на новина</h2>
    <div class="twothirds">
        <form method="post">

            <p>Име на събитие:</p>
            <input type="text" autocomplete="off" name="addtitle"
                   value="<?php echo htmlentities(isset($_SESSION['events']['title']) ? base64_decode($_SESSION['events']['title']) : "") ?>"
                   size="40"/><br/><br/>

            <p>Дата на събитие:</p>
            <input style="font-weight:600" type="text" id="eventdate" autocomplete="off" name="eventdate"
                   value="<?php echo isset($_SESSION['events']['date']) ? $_SESSION['events']['date'] : "" ?>"
                   size="40"/><br/><br/>

            <p>Локация на събитие:</p>
            <input type="text" autocomplete="off" name="location"
                   value="<?php echo htmlentities(isset($_SESSION['events']['location']) ? base64_decode($_SESSION['events']['location']) : "") ?>"
                   size="40"/><br/><br/>

            <p>Повече информация:</p>
            <textarea
                    name="addeventstext"><?php echo htmlentities(isset($_SESSION['events']['content']) ? base64_decode($_SESSION['events']['content']) : "") ?></textarea><br/>

            <div class="inline">
                <?php
                $changeBtn = isset($_SESSION['events']) && count($_SESSION['events']) > 0 ?
                    '<input type="submit" class="button-big" name="saveevents" value="Запази" />' :
                    '<input type="submit" class="button-big" name="addevents" value="Добави" />';
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

    });</script>
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