<div class="divider"></div>
<div class="content">
    <div class="container">
        <h1 class="single">Галерия</h1>
        <div class="main-content">
            <div class="slider-con">
                <?php
                $currentPage = isset(getRoute()['params'][0]) && (int)getRoute()['params'][0] > 0 ? (int)getRoute()['params'][0] : 1;
                $itemsPerPage = 12;
                $querySwitch = isset(getRoute()['params'][1]) ? "where category='" . base64_encode(cyrilic((getRoute()['params'][1]))) . "'" : "";
                $allPages = ceil(mysqli_num_rows(mysqli_query(isDbConnected(), "Select * from gallery {$querySwitch}")) / $itemsPerPage);
                $offset = ($currentPage - 1) * $itemsPerPage;
                $query = mysqli_query(isDbConnected(), "Select * from gallery {$querySwitch} limit {$itemsPerPage} offset {$offset} ");
                $i = 0;
                while ($row = mysqli_fetch_array($query)) {
                    $i++;
                    echo '
					     <div class="modal fade" id="image' . $i . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLongTitle">' . base64_decode($row['title']) . '</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<center><img style="max-width:100%; max-height:100%;" title="' . base64_decode($row['title']) . '" alt= "' . base64_decode($row['title']) . '" src="' . base64_decode($row['img_path']) . '"></center>
								</div>
								</div>
							</div>
						</div>
							
						<div class="slide">
							<ul>
								<li ><img class="btn btn-primary gallery_img" data-toggle="modal" data-target="#image' . $i . '" title="' . base64_decode($row['title']) . '" alt= "' . base64_decode($row['title']) . '" src="' . base64_decode($row['img_path']) . '"></li>
							</ul>
						</div>
						';
                }
                echo '</div>';
                echo '<ul class="pagination modal-1">
                      <li><a href="/gallery/' . ($currentPage - 1 == 0 ? 1 : $currentPage - 1) . '" class="prev">&laquo</a></li>';
                for ($i = 1; $i <= $allPages; $i++) {
                    echo "<a href='/gallery/{$i}'>{$i}</a>";
                }
                echo '  <li><a href="/gallery/' . ($currentPage + 1 <= $allPages ? $currentPage + 1 : $currentPage) . '" class="next">&raquo;</a></li></ul>';

                echo '  </div>
                        <aside id="sidebar">
                        <div class="widget sidemenu">
                            <ul> ';

                $query_img_categories = mysqli_query(isDbConnected(), "Select * from  gallery group by category");
                echo '<li><a href="/gallery/">Всички Снимки</a></li>';
                for ($i = 0; $i <= $row = mysqli_fetch_array($query_img_categories); $i++) {
                    $query_num_imgs = mysqli_query(isDbConnected(), "Select * from gallery  WHERE category = '{$row['category']}'  ");
                    $photos_num = mysqli_num_rows($query_num_imgs);
                    if ($photos_num > 0) {
                        echo '<li><a href="/gallery/' . $currentPage . '/' . base64_decode($row['category']) . '">' . base64_decode($row['category']) . '<span class="nr">' . $photos_num . '</span></a></li>';
                    }
                }
                ?>
                </ul>
            </div>
            </aside>
            <!-- / sidebar -->

        </div>
        <!-- / container -->
    </div>
    <script src="//code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
