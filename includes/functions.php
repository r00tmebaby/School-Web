<?php
session_start();


### Pagination ###
// @parms
// 	$table 		 	- string  	| table that is requested for the pagination
// 	$itemsPerPage 	- integer  	| how many items to be displayed ped page
// @return
//  array 						| first parameter query and second parameter the pagination style
///////////////////
function pagi($table, $itemsPerPage = 3)
{
    $queryChange = "";

    if (isset($_POST['filterDate'])) {
        if (validDate($_POST['dateFrom']) && validDate($_POST['dateTo'])) {
            $queryChange = "where date between " . strtotime(trim($_POST['dateFrom'])) . " and " . (strtotime($_POST['dateTo']) + 24 * 60 * 60);
        }
    }

    $currentPage = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
    $allPages = ceil(mysqli_num_rows(mysqli_query(isDbConnected(), "Select * from " . $table . " {$queryChange}")) / $itemsPerPage);
    $offset = bcmul($currentPage - 1, $itemsPerPage);
    $query = mysqli_query(isDbConnected(), "Select * from " . $table . " {$queryChange} ORDER BY id Desc limit {$itemsPerPage} offset {$offset} ");
    $content = '<div class="pagination"><a href="/admin/index.php?module=' . $table . '&page=' . ($currentPage - 1 == 0 ? 1 : $currentPage - 1) . '"><i class="fa btn btn-secondary fa-chevron-circle-left fa-1x"></i></a>';

    for ($i = 1; $i <= $allPages; $i++) {
        $content .= "<a class='btn btn-secondary fa fa-1x' href='/admin/index.php?module=" . $table . "&page={$i}'>{$i}</a>";
    }

    $content .= '  <a href="/admin/index.php?module=' . $table . '&page=' . ($currentPage + 1 <= $allPages ? $currentPage + 1 : $currentPage) . '" ><i class="btn btn-secondary fa fa-chevron-circle-right fa-1x"></i></a></div>';
    return array("query" => $query, "navi" => $content, "rows" => mysqli_num_rows($query));
}

function getHost()
{
    $protocol = $_SERVER['SERVER_PROTOCOL'] === 'https' ? 'http' : 'https';
    return $protocol . '://' . $_SERVER['HTTP_HOST'];
}

### Update Delete Function ###
// @parms
// 	$table 		 	- string  	| table that is requested for the deletion
// @void
//   check if item id is selected and set session
///////////////////
function set_id($table)
{
    if (isset($_POST['specid']) && (int)$_POST['specid'] > 0) {
        $_SESSION['spec'] = [];
        if (isset($_POST['specedit'])) {
            $_SESSION['spec'] = array_map("base64_decode", mysqli_fetch_array(mysqli_query(isDbConnected(), "Select * from " . $table . " where id='" . (int)$_POST['specid'] . "'")));
            $_SESSION['specid'] = $_POST['specid'];
        } elseif (isset($_POST['specdelete'])) {

            if ($table == "regulations") {
                $info = mysqli_fetch_array(mysqli_query(isDbConnected(), "Select * from " . $table . " where id='" . (int)$_POST['specid'] . "'"));
                if (is_file($_SERVER['DOCUMENT_ROOT'] . "/documents/" . $info['filename'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/documents/" . $info['filename']);
                }
            }
            mysqli_query(isDbConnected(), "Delete from " . $table . " where id='" . (int)$_POST['specid'] . "'");
        }

    } elseif (isset($_POST['reset'])) {
        $_SESSION['spec'] = [];
        unset($_SESSION['new_category']);
        unset($_SESSION['new_subcategory']);
    } else {
        unset($_SESSION['specid']);
        unset($_SESSION['spec']);
    }
}

### Is Database Connected ###
// returns the connection with the database true or false.
function isDbConnected()
{
    require('config.php');
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    return $conn;
}

### URL to cyrilic ###
//Convert text back from url to proper cyrilic
function cyrilic($string, $swap = 1)
{
    return $swap ? urldecode(mb_convert_encoding($string, "utf-8", "Windows-1251")) :
        urldecode(mb_convert_encoding($string, "Windows-1251", "utf-8"));

}

### Check Base64 Encode  ###
//Cheks wehther string is already encoded to base64
function is_base64($data)
{
    return preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data);
}

;

### My Str to time ###
// Replace the ENG lang to BG Lang
function myStrtotime($date_string, $format = '')
{
    $search = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $replace = array('Яну', 'Фев', 'Март', 'Апр', 'Май', 'Юни', 'Юли', 'Авг', 'Септ', 'Окт', 'Нов', 'Дек');
    return str_replace($search, $replace, $date_string);
}

### Validate Date ###
// @param: integer date (timestamp)
// @return: boolean

function validDate($date)
{
    $explode = explode("-", $date);
    if (count($explode) >= 3) {
        $elem = array_map("trim", $explode);
        return checkdate($elem[1], $elem[0], $elem[2]);
    }
    return false;
}


### Show Events Function ###
// Id 1 shows the long news in events.php file.
// Id 2 shows the short events in the main.php file.
function showEvents($id)
{
    $sql = "SELECT * FROM events order by date asc LIMIT 4 ";
    $result = mysqli_query(isDbConnected(), $sql);
    if (mysqli_num_rows($result) > 0) {
        for ($i = 0; $i < $row = mysqli_fetch_assoc($result); $i++) {
            switch ($id) {
                case 1:
                    if ($row['date'] >= time()) {
                        echo '
                      <article>
                            <div class="current-date">
                                <p>' . myStrtotime(date('M', $row['date']), 'M') . '</p>
                                <p class="date">' . date('d', $row['date']) . '</p>
                            </div>
                            <div class="info">
                                <h3>' . base64_decode($row['title']) . '</h3>
                                <p class="info-line"><span class="time">' . date("H:i", $row['date']) . '</span><span class="place">' . base64_decode($row['location']) . '</span></p>
                                <p>' . base64_decode($row['content']) . '</p>
                            </div>
                        </article>';
                    }
                    break;

                case 2:
                    if ($row['date'] >= time()) {
                        echo '
                        <article>
                        <div class="current-date">
                           <p>' . myStrtotime(date('M', $row['date']), 'M') . '</p>
                            <p class="date">' . date('d', $row['date']) . '</p>
                        </div>
                        <div class="info">
                            <h4>' . base64_decode($row['title']) . '</h4>
                            <p>' . substr(strip_tags(base64_decode($row['content'])), 0, 235) . '...</p>
                            <a class="more" href="/events">Виж повече</a>
                        </div>
                        </article>';
                    }
                    break;
            }
        }
    } else {
        echo "<div style='min-height:142px;margin:0 auto;text-align:center;'>Няма добавени събития</div>";
    }
}

### Get Route Function ###
// Create the path automatic, if there is file.
// Works with parameters.
function getRoute()
{

    $url = trim($_SERVER['REQUEST_URI'], '/');
    $urlSegments = explode('/', $url);

    $scheme = ['page', 'params'];
    $route = [];

    foreach ($urlSegments as $index => $segment) {
        if ($scheme[$index] == 'params') {
            $route['params'] = array_slice($urlSegments, $index);
            break;
        } else {
            $route[$scheme[$index]] = $segment;
        }
    }

    return $route;

}

### Show News Function ###
// Id 1 shows the long news in news.php file.
// Id 2 shows the short news in the main.php file.
function showNews($id)
{
    $sql = "SELECT * FROM news order by date desc LIMIT 2";
    $result = mysqli_query(isDbConnected(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            switch ($id) {
                case 1:
                    echo '<article>
                        <div class="current-date">
                           <p>' . myStrtotime(date('M', $row['date']), 'M') . '</p>
                            <p class="date">' . date('d', $row['date']) . '</p>
                        </div>
                        <div class="info">
                            <h3>' . base64_decode($row['title']) . '</h3>
                            <p>' . base64_decode($row['content']) . '</p>
                        </div>
                        </article>';
                    break;

                case 2:
                    echo '<article>
                        <div class="info">
                            <h4>' . base64_decode($row['title']) . '</h4>
                            <p class="date">' . myStrtotime(date('d - M- Y', $row['date']), 'd - M- Y') . '</p>
                            <p>' . substr(strip_tags(base64_decode($row['content'])), 0, 250) . '</p>
                            <a class="more" href="/news">Виж повече</a>
                        </div>
                         </article>';
                    break;
            }
        }
    } else {
        echo "
<div style='min-height:142px;margin:0 auto;text-align:center;'>Няма добавени новини</div>";
    }
}


### Only Numbers and Letters Function (Security Func.) ###
//  Put all vars in array and check them for forbidden chars.
//  Returns true if you are in the range of prep_grep, else false.
//  There are 2 options for English and Bulgarian language.
function only_numbers_and_letters($arr = array(), $option = "")
{
    switch ($option) {
        case "en":
            return preg_grep('/^[a-zA-Z0-9]+$/', $arr);
            break;

        case "bg":
            mb_convert_encoding($arr, 'UTF8', 'CP1251');
            return preg_grep('/[а-яА-Я]+/u', $arr);
            break;
    }
}

### Show Message Function ###
// $shortMessage = short message
// $message = more info, about the problem
//  $typeMessage = type of message alert "warning, danger and success" from BOOTSTRAP 4
function showMessage($shortMessage = "", $message = "", $typeMessage = "")
{
    switch ($typeMessage) {
        case "warning":
        case "danger":
            echo '
                <div class="alert alert-' . $typeMessage . ' alert-dismissible fade show" role="alert">
                <strong>' . $shortMessage . '</strong> ' . $message . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>';
            break;
        case "success":
            echo '
                <div class="alert alert-' . $typeMessage . ' alert-dismissible fade show" role="alert">
                <strong>' . $shortMessage . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>';
            break;
    }
}


function isAccount($account, $password)
{
    $data = array();
    $query = mysqli_query(isDbConnected(), "Select * from user");
    while ($row = mysqli_fetch_array($query)) {
        $data[] = md5($row['username'] . $row['password']);
    }
    return in_array(md5($account . $password), $data);
}

function checkID($table, $id)
{
    $data = array();
    $query = mysqli_query(isDbConnected(), "Select * from {$table}");
    while ($row = mysqli_fetch_array($query)) {
        $data[] = $row['id'];
    }
    return in_array($id, $data);
}

function failLoginIpSave()
{
    mysqli_query(isDbConnected(), "INSERT INTO `ip` (`ip_adress` ,`time`) VALUES ('".takeIP()."',CURRENT_TIMESTAMP)");
}

function isLoginBlocked()
{
    $result = mysqli_query(isDbConnected(), "SELECT COUNT(*) FROM `ip` WHERE `ip_adress` LIKE '".takeIP()."' AND `time` > (now() - interval 10 minute)");
    $count = mysqli_fetch_array($result, MYSQLI_NUM);
    return ($count[0] >= 3) ? true : false;
}

function takeIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


