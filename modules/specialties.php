<div class="divider"></div>
<div class="content">

    <div class="content-figure">
        <h1>Специалности</h1>
        <?php
        $specId = isset(getRoute()['params'][0]) ? (int)getRoute()['params'][0] : 0;

        if ($specId > 0 && checkID("specialties", $specId)) {
            $result = mysqli_query(isDbConnected(), "SELECT * FROM specialties where id='" . $specId . "' ORDER BY specialty_name ASC");
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo '   
								<section>
									<h1>' . base64_decode($row['specialty_name']) . '</h1>  
									<div><a class="btn blue" href="/specialties">Назад</a></div> 
									<hr>
									<p>' . base64_decode($row['specialty_info_long']) . '</p>
									<hr>
								</section>';
                }
            } else {
                echo "<div style='min-height:258px;'></div>";
            }
        } else {
            $result = mysqli_query(isDbConnected(), "SELECT * FROM specialties ORDER BY specialty_name ASC");
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo '
							<figure class="snip1369 green">
								<img src="' . base64_decode($row['image']) . '"  />
								<div class="image"><img src="' . base64_decode($row['image']) . '"/></div>
								<figcaption>
									<h3>' . base64_decode($row['specialty_name']) . '</h3>
									<p>' . base64_decode($row['specialty_info_short']) . '</p>
								</figcaption><span class="read-more">
									Прочети повече </span>
								<a href="/specialties/' . $row['id'] . '"></a>
							</figure>';
                }
            } else {
                echo "<div style='min-height:258px'></div>";
            }
        }
        ?>


    </div>
</div>



