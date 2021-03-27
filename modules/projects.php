<?php
$content = "";
$categories = array();
$projectID = isset(getRoute()['params'][0]) ? (int)getRoute()['params'][0] : 0;
$sql = mysqli_query(isDbConnected(), "SELECT * FROM projects group by category");
$query = mysqli_query(isDbConnected(), "SELECT * FROM projects");
$default = mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM projects"));
while ($row = mysqli_fetch_array($query)) if (strlen($row['category']) == 0) $content .= "<a href='/projects/" . $row['id'] . "'>" . base64_decode($row['title']) . "</a>";
while ($row = mysqli_fetch_array($sql)) if (strlen($row['category']) > 0) $categories[] = $row['category'];

foreach ($categories as $key => $cat) {
    $query = mysqli_query(isDbConnected(), "SELECT * FROM projects where category = '" . $cat . "'");
    $content .= '<a class="dropdown-btn"> ' . base64_decode($cat) . ' <img style="float:right;margin:20px 20px" src="/images/bg_arrow_select.png"><span class="nr">' . mysqli_num_rows($query) . '</span></a><div class="dropdown-container">';
    while ($row = mysqli_fetch_array($query)) $content .= '<a href="/projects/' . $row['id'] . '">' . base64_decode($row['title']) . '</a>';
    $content .= "</div>";
}

?>

<div class="divider"></div>
<div class="content">
    <div class="container">
        <h1 class="single">Нашите Проекти</h1>
        <aside id="sidebar">
            <div class="widget sidemenu">
                <div class="sidenav">
                    <?php echo $content ?>
                </div>
            </div>
        </aside>
        <div class="main-content">
            <?php
            if (checkID("projects", $projectID)) {
                $query = mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM projects where id={$projectID}"));
            } else {
                $query = mysqli_fetch_array(mysqli_query(isDbConnected(), "SELECT * FROM projects order by id asc limit 1"));
            }
            echo "<span style='font-weight:600;'>" . base64_decode($query['title']) . "</span></br>";
            echo base64_decode($query['category']);
            echo base64_decode($query['content']);
            ?>
        </div>
        <!-- / sidebar -->

    </div>
    <!-- / container -->
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