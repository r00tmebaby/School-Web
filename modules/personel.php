<?php

$content = "";
$categories = mysqli_query(isDbConnected(), "SELECT * FROM personel group by category");
$currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$itemsPerPage = 12;
$addCategory = isset(getRoute()['params'][0]) ? "where category='" . base64_encode(urldecode(cyrilic(getRoute()['params'][0]))) . "'" : "";
$querySwitch = isset(getRoute()['params'][1]) ? $addCategory . "and sub_category='" . base64_encode(urldecode(cyrilic(getRoute()['params'][1]))) . "'" : $addCategory;
$allPages = ceil(mysqli_num_rows(mysqli_query(isDbConnected(), "Select * from personel {$querySwitch}")) / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$query = mysqli_query(isDbConnected(), "Select * from personel {$querySwitch}  ORDER BY name ASC limit {$itemsPerPage} offset {$offset} ");
$personelID = isset($_GET['id']) && checkID("personel", $_GET['id']) ? (int)$_GET['id'] : false;


//Right side menu logic

while ($row = mysqli_fetch_array($categories)) {
    $cats = mysqli_query(isDbConnected(), "SELECT * FROM personel where category = '" . $row['category'] . "'");
    $has_subat = mysqli_num_rows(mysqli_query(isDbConnected(), "SELECT * FROM personel where category = '" . $row['category'] . "' and sub_category != ' '"));
    if ($has_subat == 0) {
        $content .= '<a href="/personel/' . base64_decode($row['category']) . '">' . base64_decode($row['category']) . '</a>';
    } else {
        while ($rows = mysqli_fetch_array($cats)) {
            $num = mysqli_num_rows(mysqli_query(isDbConnected(), "SELECT * FROM personel where sub_category = '" . $row['sub_category'] . "'"));
            $content = '
				<a class="dropdown-btn"><img style="float:right;margin:20px 20px" src="/images/bg_arrow_select.png"> 
					' . base64_decode($rows['category']) . ' 				
				</a>
				<div class="dropdown-container">		    
					<a href="/personel/' . base64_decode($row['category']) . '/' . base64_decode($row['sub_category']) . '">
						' . base64_decode($row['sub_category']) . '<span class="nr">' . $num . '</span>
					</a>';
        }
        $content .= "</div>";
    }
}

// Menu End
?>


<div class="divider"></div>
<div class="content">

    <div class="container">
        <h1 class="single">Нашият Екип</h1>
        <aside id="sidebar">
            <div class="widget sidemenu">
                <div class="sidenav">
                    <?php echo $content ?>
                </div>
            </div>
        </aside>
        <div class="main-content">

            <div class="slider-con">
                <?php
                if (!$personelID) {
                    while ($row = mysqli_fetch_array($query)) {
                        $names = base64_decode($row['name']) . " " . base64_decode($row['lastname']);
                        $hasInfo = $row['biography'] != "PCFET0NUWVBFIGh0bWw+DQo8aHRtbD4NCjxoZWFkPg0KPC9oZWFkPg0KPGJvZHk+DQoNCjwvYm9keT4NCjwvaHRtbD4=" ? '<a href="/personel/?id=' . $row['id'] . '">' . $names . '</a>' : $names;
                        echo '
								<div class="slide">
									<ul>
										<li><img src="' . base64_decode($row['image_path']) . '">
										<p>' . $hasInfo . '</p>
						
										</li>
									</ul>
								</div>
							';
                    }
                    if ($allPages > 0) {
                        echo '</div>				 
								<ul class="pagination modal-1">
									<li><a href="?page=' . ($currentPage - 1 == 0 ? 1 : $currentPage - 1) . '" class="prev">&laquo</a></li>';
                        for ($i = 1; $i <= $allPages; $i++) {
                            echo "<a href='?page={$i}'>{$i}</a>";
                        }
                        echo '<li><a href="?page=' . ($currentPage + 1 <= $allPages ? $currentPage + 1 : $currentPage) . '" class="next">&raquo;</a></li>
								</ul>';
                    }
                } else {
                    echo '<div><a class="btn blue" href="/personel">Назад</a></div> ';
                    echo base64_decode(mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM personel where id={$personelID}"))['biography']);
                }

                ?>

            </div>
        </div>

    </div>
    <script>
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>