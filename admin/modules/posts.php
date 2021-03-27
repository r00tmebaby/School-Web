<?php
$explode_url = explode("/", $_SERVER['PHP_SELF']);
end($explode_url) == basename(__FILE__) || !defined('proper_include') || !isset($_SESSION['admin']) ? exit(http_response_code(404)) : "";

?>

<section class="panel important">
    <h2>Мотивационни постове</h2>
    <?php

    $short = isset($_POST['spectextshort']) ? base64_encode(trim($_POST['spectextshort'])) : false;
    $img = isset($_POST['specimg']) ? base64_encode(trim($_POST['specimg'])) : false;
    $title = isset($_POST['spectitle']) ? base64_encode(trim($_POST['spectitle'])) : false;

    if ($short && $img && $title) {
        if (isset($_POST['addspec'])) {
            $count = pagi("posts");
            if ($count['rows'] >= 2) {
                showMessage("Максимален брой!", "Имате право само на два мотивационни поста.", "danger");
            } else {
                mysqli_query(isDbConnected(), "Insert into posts (
				img_path,
				title,
				content) values 
				('{$img}','{$title}','{$short}')");
                $_SESSION['spec'] = [];
            }
        } elseif (isset($_POST['savespec'])) {
            mysqli_query(isDbConnected(), "Update posts SET 
			img_path = '{$img}' ,
			title = '{$title}',
			content = '{$short}'
			WHERE id = '{$_SESSION['specid']}'  ");
            $_SESSION['spec'] = [];
        }
    }

    set_id("posts");
    $pagi = pagi("posts");

    if ($pagi['rows'] > 0) {

        echo $pagi['navi'];

        echo '<table class="table">
				<tr>
					<td	class="th">Снимка</td>
					<td	class="th">Заглавие</td>
					<td class="th">Опции</td>
				</tr>';

        while ($row = mysqli_fetch_array($pagi['query'])) {
            echo "<form method='post'>
					<tr>
						<td style='width: 2%'><img style='width:50px; height:50px' src= '" . htmlentities(base64_decode($row['img_path'])) . "'</td>
						<td >" . htmlentities(base64_decode($row['title'])) . "</td>
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

    $title_f = isset($_SESSION['spec']['title']) ? $_SESSION['spec']['title'] : "";
    $image_f = isset($_SESSION['spec']['img_path']) ? $_SESSION['spec']['img_path'] : "";
    $short_f = isset($_SESSION['spec']['content']) ? $_SESSION['spec']['content'] : "";
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
            <input type="text" name="specimg" value="<?php echo $image_f ?>" size="40"
                   placeholder="Пример: https://google.com/img1.jpg"/><br/>
            <label>Мотивиращ текст:</label>
            <textarea name="spectextshort"><?php echo $short_f ?></textarea><br/>
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

