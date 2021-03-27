<?php

$explode_url = explode("/", $_SERVER['PHP_SELF']);
end($explode_url) == basename(__FILE__) || !defined('proper_include') || !isset($_SESSION['admin']) ? exit(http_response_code(404)) : "";

?>
<section class="panel important">
    <h2>Галерия</h2>
    <?php

    $content = isset($_POST['content']) ? base64_encode(trim($_POST['content'])) : false;
    $category = isset($_POST['category']) ? base64_encode(trim($_POST['category'])) : false;
    $title = isset($_POST['alt']) ? base64_encode(trim($_POST['alt'])) : false;


    if ($content && $category && $title) {
        if (isset($_POST['addspec'])) {
            mysqli_query(isDbConnected(), "Insert into gallery (
			category,
			title,
			img_path) values 
			('{$category}','{$title}','{$content}')");
            $_SESSION['spec'] = [];
            unset($_SESSION['new_category']);

        } elseif (isset($_POST['savespec'])) {
            mysqli_query(isDbConnected(), "Update gallery SET 
			category = '{$category}',
			img_path = '{$content}',
			title = '{$title}'
			WHERE id = {$_SESSION['specid']}");
            $_SESSION['spec'] = [];
            unset($_SESSION['new_category']);
        }
    }
    set_id("gallery");
    $pagi = pagi("gallery");


    if ($pagi['rows'] > 0) {

        echo $pagi['navi'];

        echo ' <table class="table">
				<tr>
					<td	class="th">#</td>
					<td class="th">Описание</td>
					<td class="th">Категория</td>
					<td class="th">Промени</td>
				</tr>';

        while ($row = mysqli_fetch_array($pagi['query'])) {
            echo "<form method='post'>
					<tr>
						<td style='width:2%'>
							<span><img style='width:50px; height:50px' src='" . htmlentities(base64_decode($row['img_path'])) . "'></span>
						</td>
						<td>
						  " . htmlentities(base64_decode($row['title'])) . "
						</td>
						<td>" . ($row['category'] == 'LQ==' ? " - " : htmlentities(base64_decode($row['category']))) . "</td>
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

    if (isset($_POST['add_new_cat']) && !empty($_POST['new_cat'])) {
        $_SESSION['new_category'] = $_POST['new_cat'];
    } elseif (isset($_POST['clearcategory'])) {
        unset($_SESSION['new_category']);
    }

    $content_f = isset($_SESSION['spec']['img_path']) ? $_SESSION['spec']['img_path'] : "";
    $category_f = isset($_SESSION['spec']['category']) ? $_SESSION['spec']['category'] : "";
    $alt_f = isset($_SESSION['spec']['title']) ? $_SESSION['spec']['title'] : "";

    ?>
</section>
<!-- Modals Start -->
<div class="modal fade" id="add_newcat" tabindex="-1" role="dialog" aria-labelledby="add_newcat" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_newcat">Нова Категория</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="new_cat" class="form-control">
                    </div>

            </div>

            <input type="submit" name="add_new_cat" value="Добави" class="btn btn-secondary">

            </form>
        </div>
    </div>
</div>
<section class="panel important">
    <h2>Добавяне на Снимка</h2>
    <div class="twothirds">
        <?php echo isset($_SESSION['spec']) ? "<div class='image_adder'><img width='100%' height='100%' src='" . $content_f . "'></div>" : "" ?>
        <form enctype="multipart/form-data" method="post">
            <label>Категории:</label>
            <div class="half-inputs">
                <select id="selectcategory" name="category">
                    <option selected disabled>-</option>
                    <?php
                    if (isset($_SESSION['new_category'])) {
                        echo "<option selected value='" . htmlentities($_SESSION['new_category']) . "'>" . htmlentities($_SESSION['new_category']) . "</option>";
                    } else {
                        $cats = mysqli_query(isDbConnected(), "Select * from gallery where category != '' group by category order by category");
                        while ($row = mysqli_fetch_array($cats)) {
                            $selected = $category_f == base64_decode($row['category']) ? "selected" : "";
                            echo "<option " . $selected . " value='" . htmlentities(base64_decode($row['category'])) . "'>" . htmlentities(base64_decode($row['category'])) . "";
                        }
                    }
                    ?>
                </select>


                &nbsp;<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#add_newcat"
                              data-whatever="@getbootstrap"> +
                </button>
                &nbsp;<input type="submit" name="clearcategory" style="width:40px" value="-" class="btn btn-secondary">
            </div>
            <br>
            <label>Кратко описание </label>
            <input name="alt" type="text" value="<?php echo $alt_f ?>"
                   placeholder=" * Важно е за SEO оптимизация. Едно изречение е достатъчно"><br/>
            <br/>
            <label>Файл Дестинация</label>
            <input name="content" type="text" value="<?php echo $content_f ?>"
                   placeholder="Пример: https://www.google.com/myfile.jpg"><br/>

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

    });

    $(document).ready(function () {
        $("#selectcategory").change(function () {
            document.getElementById("newcategory").disabled = true;
        });
        $("#newcategory").change(function () {
            document.getElementById("selectcategory").disabled = true;
        });
    });

</script>
