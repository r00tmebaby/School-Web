<?php
$content = "";
$categories = array();
$projectID = isset(getRoute()['params'][0]) ? (int)getRoute()['params'][0] : 0;
$sql = mysqli_query(isDbConnected(), "SELECT * FROM regulations group by category");
$query = mysqli_query(isDbConnected(), "SELECT * FROM regulations");
$default = mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM regulations"));
while ($row = mysqli_fetch_array($sql)) if (strlen($row['category']) > 0) $categories[] = $row['category'];

foreach ($categories as $key => $cat) {
    $query = mysqli_query(isDbConnected(), "SELECT * FROM regulations where category = '" . $cat . "'");

    $content .= '<a class="dropdown-btn"> ' . base64_decode($cat) . ' <img style="float:right;margin:20px 20px" src="/images/bg_arrow_select.png"><span class="nr">' . mysqli_num_rows($query) . '</span></a><div class="dropdown-container">';
    while ($row = mysqli_fetch_array($query)) $content .= '<a href="/regulations/' . $row['filename'] . '">' . base64_decode($row['title']) . '</a>';
    $content .= "</div>";
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.js"
        integrity="sha256-vZy89JbbMLTO6cMnTZgZKvZ+h4EFdvPFupTQGyiVYZg=" crossorigin="anonymous"></script>
<div class="divider"></div>
<div class="content ">
    <div class="container">
        <h1 class="single">Наредби</h1>
        <aside id="sidebar">
            <div class="widget sidemenu">
                <div class="sidenav">
                    <?php echo $content ?>
                </div>
            </div>
        </aside>
        <div class="main-content">
            <?php

            $query = mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM regulations order by id desc limit 1"));
            if (isset(getRoute()['params'][0])) {
                $filename = getRoute()['params'][0];
                if (is_file("documents/" . $filename)) {
                    $query = mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM regulations where filename='{$filename}'"));
                }
            }
            if ($query['title']) {
                echo '
				<span style="font-weight:600;">' . base64_decode($query['title']) . '</span>
				<embed class="iframvew" src="https://drive.google.com/viewerng/viewer?embedded=true&url=' . getHost() . '/documents/' . $query['filename'] . '" ></embed>			
				';
            } else {
                echo "<div style='min-height:142px;margin:0 auto;text-align:center;'>Няма добавени наредби</div>";
            }
            ?>
        </div>

        <!-- / sidebar -->
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
<style>
    .iframvew {
        width: 100%;
        min-width: 500px;
        left: 0;
        min-height: 920px;
        margin-top: 5%;
        margin-bottom: 5%;
        background: #FFF;
        overflow-x: auto;
    }
</style>