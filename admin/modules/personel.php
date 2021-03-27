<?php

$explode_url = explode("/", $_SERVER['PHP_SELF']);
end($explode_url) == basename(__FILE__) || !defined('proper_include') || !isset($_SESSION['admin']) ? exit(http_response_code(404)) : "";

?>
<section class="panel important">
    <h2>Екип</h2>
    <?php
    $name = isset($_POST['name']) ? trim($_POST['name']) : false;
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : false;
    $category = isset($_POST['category']) ? trim($_POST['category']) : false;
    $scategory = isset($_POST['sub_category']) ? trim($_POST['sub_category']) : false;
    $biography = isset($_POST['biography']) ? trim($_POST['biography']) : "";
    $image = isset($_POST['image_path']) ? trim($_POST['image_path']) : false;


    if ($name && $lastname && $category && $image) {
        if (in_array(substr($image, -4), array(".jpg", ".png", ".jpeg", ".bmp", ".gif"))) {
            if (isset($_POST['addspec'])) {
                mysqli_query(isDbConnected(), "Insert into personel (name,lastname,category,sub_category,biography,image_path) values (
					'" . base64_encode($name) . "',
					'" . base64_encode($lastname) . "',
					'" . base64_encode($category) . "', 
					'" . base64_encode($scategory) . "', 
					'" . base64_encode($biography) . "', 
					'" . base64_encode($image) . "'
					)");
                $_SESSION['spec'] = [];
                unset($_SESSION['new_category']);
                unset($_SESSION['new_subcategory']);
            } elseif (isset($_POST['savespec'])) {
                mysqli_query(isDbConnected(), "Update personel SET 
					name = 			'" . base64_encode($name) . "',
					lastname = 		'" . base64_encode($lastname) . "',
					category = 		'" . base64_encode($category) . "',
					sub_category = 	'" . base64_encode($scategory) . "',
					image_path = 	'" . base64_encode($image) . "',
					biography = 	'" . base64_encode($biography) . "'
					WHERE id = {$_SESSION['specid']}");
                $_SESSION['spec'] = [];
                unset($_SESSION['new_category']);
                unset($_SESSION['new_subcategory']);
            }
        } else {
            showMessage("ERROR:", "Only jpg, png, bmp, gif files are supported", "warning");
        }
    }

    $name_f = htmlentities(isset($_SESSION['spec']['name']) ? $_SESSION['spec']['name'] : "");
    $lastname_f = htmlentities(isset($_SESSION['spec']['lastname']) ? $_SESSION['spec']['lastname'] : "");
    $image_f = htmlentities(isset($_SESSION['spec']['image_path']) ? $_SESSION['spec']['image_path'] : "");
    $biography_f = isset($_SESSION['spec']['biography']) ? $_SESSION['spec']['biography'] : "";
    $category_f = isset($_SESSION['spec']['category']) ? $_SESSION['spec']['category'] : "";
    $scategory_f = isset($_SESSION['spec']['sub_category']) ? $_SESSION['spec']['sub_category'] : "";

    set_id("personel");
    $pagi = pagi("personel");

    if ($pagi['rows'] > 0) {
        echo $pagi['navi'];
        echo '  <table class="table">
					<tr>
					    <td class="th">#</td>
						<td class="th">Име</td>
						<td class="th">Фамилия</td>
						<td class="th">Категория</td>
						<td class="th">Специалност</td>
						<td class="th">Промяна</td>
					</tr>';
        while ($row = mysqli_fetch_array($pagi['query'])) {
            echo "<form method='post'>
					<tr>
						<td style='width:2%'>
							<span><img style='width:50px; height:50px' src='" . htmlentities(base64_decode($row['image_path'])) . "'></span>
						</td>
						<td>" . htmlentities(base64_decode($row['name'])) . "</td>
						<td>" . htmlentities(base64_decode($row['lastname'])) . "</td>
						<td>" . ($row['category'] == "LQ==" ? " - " : htmlentities(base64_decode($row['category']))) . "</td>
						<td>" . ($row['sub_category'] == "LQ==" ? " - " : htmlentities(base64_decode($row['sub_category']))) . "</td>
						<td class='edit_remove' >
							<input type='submit' name='specedit' value='&#xf044;' class='fa fa-input btn btn-secondary'>
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
    } elseif (isset($_POST['add_new_subcat']) && !empty($_POST['new_subcat'])) {
        $_SESSION['new_subcategory'] = $_POST['new_subcat'];
    } elseif (isset($_POST['clearsubcategory'])) {
        unset($_SESSION['new_subcategory']);
    } elseif (isset($_POST['clearcategory'])) {
        unset($_SESSION['new_category']);
    }
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


<div class="modal fade" id="add_newsub" tabindex="-1" role="dialog" aria-labelledby="add_newsub" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_newsub">Нова под-категория</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="new_subcat" class="form-control">
                    </div>
            </div>
            <input type="submit" name="add_new_subcat" value="Добави" class="btn btn-secondary">
            </form>
        </div>
    </div>
</div>

<!-- Modal End -->
<section class="panel important">
    <h2>Добавяне на Персонал</h2>
    <div class="twothirds">
        <form method="post">
            <div class="half-inputs">
                <input type="text" placeholder="*&nbsp;Име" name="name" value="<?php echo $name_f ?>"/>
                <input type="text" placeholder="*&nbsp;Фамилия" name="lastname" value="<?php echo $lastname_f ?>"/>
            </div>
            <br/>
            <label>* Категория:</label>
            <div class="half-inputs">
                <select id="category" name="category">
                    <option selected disabled>-</option>
                    <?php
                    if (isset($_SESSION['new_category'])) {
                        echo "<option selected value='" . htmlentities($_SESSION['new_category']) . "'>" . htmlentities($_SESSION['new_category']) . "</option>";
                    } else {
                        $cats = mysqli_query(isDbConnected(), "Select * from personel where category != '' group by category order by category");
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
            <br/>

            <label> Под-категория:</label>
            <div class="half-inputs">
                <select id="sub_category" name="sub_category">
                    <option selected disabled>-</option>
                    <?php

                    if (isset($_SESSION['new_subcategory'])) {
                        echo "<option selected value='" . htmlentities($_SESSION['new_subcategory']) . "'>" . htmlentities($_SESSION['new_subcategory']) . "</option>";
                    } else {
                        $cats = mysqli_query(isDbConnected(), "Select * from personel where sub_category != '' group by sub_category order by sub_category");
                        while ($row = mysqli_fetch_array($cats)) {
                            $selected = $scategory_f == base64_decode($row['sub_category']) ? "selected" : "";
                            echo "<option " . $selected . " value='" . htmlentities(base64_decode($row['sub_category'])) . "'>" . htmlentities(base64_decode($row['sub_category'])) . "";
                        }
                    }
                    ?>
                </select>
                &nbsp;<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#add_newsub"
                              data-whatever="@getbootstrap">+
                </button>
                &nbsp;<input type="submit" name="clearsubcategory" style="width:40px" value="-"
                             class="btn btn-secondary">
            </div>

            <br/>
            <label> Снимка:</label>
            <br/>
            <span class="image_url">
				<input placeholder="Пример: https://www.google.com/myimage.jpg" name="image_path" type="text"
                       value="<?php echo $image_f ?>"><br/>
			</span>
            <br/>
            <label> Биография:</label>
            <textarea name="biography"><?php echo $biography_f ?></textarea><br/>
            <br/>
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

    $('#add_new_cat').modal(options)

</script>

