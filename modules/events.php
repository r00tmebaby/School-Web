<?php

$_SESSION['month'] = isset(getRoute()['params'][0]) ? (int)getRoute()['params'][0] : (int)date('m');
$_SESSION['year'] = isset(getRoute()['params'][1]) ? (int)getRoute()['params'][1] : (int)date('Y');
$months = array(1 => 'Яну', 2 => 'Фев', 3 => 'Март', 4 => 'Апр', 5 => 'Май', 6 => 'Юни', 7 => 'Юли', 8 => 'Авг', 9 => 'Сеп', 10 => 'Окт', 11 => 'Нов', 12 => 'Дек');
$week = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд');

if ($_SESSION['month'] > 12) {
    $_SESSION['year'] = getRoute()['params'][1] + 1;
    $_SESSION['month'] = 1;
} elseif ($_SESSION['month'] < 1 || $_SESSION['year'] > 2050 || $_SESSION['year'] < 0) {
    $_SESSION['month'] = (int)date('m');
    $_SESSION['year'] = (int)date('Y');
}
?>
<link rel="stylesheet" media="all" href="css/style.css">
<div class="divider"></div>
<div class="content">
    <div class="container">

        <div class="main-content">
            <h1>Предстоящи събития</h1>
            <section class="posts-con">
                <?php showEvents(1); ?>
            </section>
        </div>

        <aside id="sidebar">
            <div class="widget clearfix calendar">
                <div class="calendar">
                    <h2>Календар</h2>
                    <div class="head">
                        <a class="prev"
                           href="/events/<?php echo $_SESSION['month'] - 1 ?>/<?php echo $_SESSION['year'] ?> "></a>
                        <a class="next"
                           href="/events/<?php echo $_SESSION['month'] + 1 ?>/<?php echo $_SESSION['year'] ?> "></a>
                        <h4><?php echo $months[$_SESSION['month']] . " " . $_SESSION['year'] = ($_SESSION['year'] < 2019) ? $_SESSION['year'] = 2020 : $_SESSION['year'] ?></h4>
                    </div>
                    <div class="table">
                        <table>
                            <tr>
                                <?php
                                foreach ($week as $count => $dayOfWeek) {
                                    echo '<th class="col-' . $count . '">' . $dayOfWeek . '</th>';
                                }
                                ?>
                            </tr>
                            <?php
                            $daysInCurrentMonth = cal_days_in_month(CAL_GREGORIAN, $_SESSION['month'], $_SESSION['year'] = ($_SESSION['year'] < 2019) ? $_SESSION['year'] = 2020 : $_SESSION['year']);
                            $firstDayOfWeek = jddayofweek(gregoriantojd($_SESSION['month'], 1, $_SESSION['year']), 1);
                            $tdOffset = date("w", strtotime($firstDayOfWeek));
                            $days = 0;

                            for ($i = 0; $i < 35; $i++) {
                                while ($tdOffset > 1) {
                                    echo '<td class="col-1">';
                                    $tdOffset--;
                                    $i++;
                                }
                                if ($i % 7 == 0) echo "</tr><tr>";
                                if ($days < $daysInCurrentMonth) {
                                    $days++;
                                    $pattern = strtotime($_SESSION['year'] . '-' . $_SESSION['month'] . '-' . $days);
                                    $checkQuery = mysqli_fetch_array(mysqli_query($conn, "Select count(*) as count, content, title, date, location from events where date between '" . $pattern . "' and '" . ($pattern + 86400) . "' "));
                                    if ($checkQuery['count'] == 1) {
                                        echo '<td class="col-2 upcoming">
										         <div>
										            <div class="tooltip">
												      <div class="holder">
														<h4>' . base64_decode($checkQuery['title']) . '</h4>
									                    <p class="info-line">
														  <span class="time">' . date("H:i", $checkQuery['date']) . '</span>
														  <span class="place">' . base64_decode($checkQuery['location']) . '</span>
														</p>
									                   <p>' . base64_decode($checkQuery['content']) . '</p>									
										              </div>
													</div>
												 ' . $days . '
											    </div>
											 </td>';
                                    } else {
                                        echo '<td class="event">' . $days . '</td>';
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <div class="note">
                        <p class="upcoming-note">Бъдещи събития</p>
                    </div>
                </div>
        </aside>
        <!-- / sidebar -->

    </div>
    <!-- / container -->

</div>
</article>
</section>
</div>

</aside>
<!-- / sidebar -->
</div>
<!-- / container -->
</div>
</body>
</html>
