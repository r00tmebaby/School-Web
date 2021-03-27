<?php
$sql = "SELECT * FROM posts LIMIT 2";
$result = mysqli_query(isDbConnected(), $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<article>
				<div class="pic"><img width="121" src="' . base64_decode($row['img_path']) . '" alt=""></div>
				<div class="info">
					<h3>' . base64_decode($row['title']) . '</h3>
					<p>' . base64_decode($row['content']) . '</p>
				</div>
			</article>';
    }
} else {
    echo "<div style='min-height:142px;margin:0 auto;text-align:center;'>Няма добавени мотивационни постове</div>";
}

?>