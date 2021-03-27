<?php
$explode_url = explode("/", $_SERVER['PHP_SELF']);
end($explode_url) == basename(__FILE__) || !defined('proper_include') || !isset($_SESSION['admin']) ? exit(http_response_code(404)) : "";

?>

<section class="panel important">
    <h2>Специалности</h2>
    <?php

    $short = isset($_POST['spectextshort']) ? base64_encode(trim($_POST['spectextshort'])) : false;
    $long = isset($_POST['spectextlong']) ? base64_encode(trim($_POST['spectextlong'])) : false;
    $img = isset($_POST['specimg']) ? base64_encode(trim($_POST['specimg'])) : false;
    $title = isset($_POST['spectitle']) ? base64_encode(trim($_POST['spectitle'])) : false;

    if ($short && $long && $img && $title) {
        if (isset($_POST['addspec'])) {
            mysqli_query(isDbConnected(), "Insert into specialties (
			specialty_name,
			specialty_info_short,
			specialty_info_long,
			image) values 
			('{$title}','{$short}','{$long}','{$img}')");
            $_SESSION['spec'] = [];
        } elseif (isset($_POST['savespec'])) {
            mysqli_query(isDbConnected(), "Update specialties SET 
			specialty_name = '{$title}' ,
			specialty_info_short = '{$short}',
			specialty_info_long = '{$long}',
			image = '{$img}'  
			WHERE id = {$_SESSION['specid']}  ");
            $_SESSION['spec'] = [];
        }

    }

    set_id("specialties");
    $pagi = pagi("specialties");

    if ($pagi['rows'] > 0) {

        echo $pagi['navi'];

        echo '<table class="table">
				<tr>
					<td	class="th">Специалност</td>
					<td class="th">Опции</td>
				</tr>';

        while ($row = mysqli_fetch_array($pagi['query'])) {
            echo "<form method='post'>
					<tr>
						<td >" . htmlentities(base64_decode($row['specialty_name'])) . "</td>
						<td class='edit_remove'>
							<input  type='submit' name='specedit' value='&#xf044;' class='fa fa-input btn btn-secondary'>
							<input class='fa fa-input btn btn-secondary' type='submit' name='specdelete' value='&#xf00d;'>
						</td>							
					<tr>
					<input type='hidden' name='specid' value='{$row['id']}'>
				</form>";
        }
        echo "</table>";
    }

    $title_f = isset($_SESSION['spec']['specialty_name']) ? $_SESSION['spec']['specialty_name'] : "";
    $image_f = isset($_SESSION['spec']['image']) ? $_SESSION['spec']['image'] : "";
    $short_f = isset($_SESSION['spec']['specialty_info_short']) ? $_SESSION['spec']['specialty_info_short'] : "";
    $long_f = isset($_SESSION['spec']['specialty_info_long']) ? $_SESSION['spec']['specialty_info_long'] : "";
    ?>

</section>

<section class="panel important">
    <form method="post" action=""></form>
    <h2>Добавяне на специалност</h2>
    <div class="twothirds">
        <form method="post">
            <label>Заглавие: </label>
            <input type="text" name="spectitle" value="<?php echo $title_f ?>" size="40"/><br/>
            <label>Линк към снимка:</label>
            <input type="text" name="specimg" value="<?php echo $image_f ?>" size="40"/><br/>
            <label>Кратко описание на специалността:</label>
            <textarea name="spectextshort"><?php echo $short_f ?></textarea><br/>
            <label>Пълно описание на специалността:</label>
            <textarea name="spectextlong"><?php echo $long_f ?></textarea><br/>
            <div class="inline">
                <?php
                $changeBtn = isset($_SESSION['spec']) && count($_SESSION['spec']) > 0 ?
                    '<input type="submit" class="button-big" name="savespec" value="Запази" />' :
                    '<input type="submit" class="button-big" name="addspec" value="Добави" />';
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

