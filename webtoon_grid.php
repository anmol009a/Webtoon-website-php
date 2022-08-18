<?php
define("DIR", __DIR__ . "/");
include DIR . "partials/_dbconnect.php";
include DIR . "functions.php";


// fetching webtoon record if exits with w_id
$stmt = $conn->prepare("SELECT * FROM `chapters` WHERE `w_id`= ? ORDER BY `c_no` DESC LIMIT 2");
$stmt->bind_param("i", $webtoon_id);


// pagination
$offset = isset($_GET['p']) ? $_GET['p'] * 30 : 0;
if ($offset) {
    $sql = "SELECT w_id, w_title, w_url, cover_path from `webtoon_details` ORDER BY `last_mod` DESC LIMIT 30 OFFSET $offset";
} else {
    $sql = "SELECT w_id, w_title, w_url, cover_path from `webtoon_details` ORDER BY `last_mod` DESC LIMIT 30";
}


// $sql = "SELECT * FROM `webtoons`";
$result = mysqli_query($conn, $sql);


// loop to print webtoons
while ($row = mysqli_fetch_assoc($result)) {
    $webtoon_id = $row['w_id'];
    $webtoon_title = $row['w_title'];
    $webtoon_url = $row['w_url'];
    $cover_path = $row['cover_path'];


    // code to display webtoons
    echo '
    <div class="post-item-details col mb-5">
        <div class="container-post-img">
            <a href="' . $webtoon_url . '" target="_blank" title="' . $webtoon_title . '">
                <img class="post-img" src="' . $cover_path . '" alt="' . $webtoon_title . '">
            </a>
        </div>
        <div class="post-details">
            <div class="container-post-title mt-2">
                <h5 class="post-title">
                    <a href="' . $webtoon_url . '" target="_blank">' . $webtoon_title . '
                    </a>
                </h5>
            </div>
        <div class="chapter-list">';

    // fetching chapter details
    $stmt->execute();
    $result2 = $stmt->get_result();
    for ($i = 0; $i < 2; $i++) {
        $row2 = $result2->fetch_assoc();
        if ($row2) {

            // $chapter_name[$i] = isset($row2['c_name']) ? $row2['c_name'] : $row2['c_no'];
            $chapter_no[$i] = "Chapter " . $row2['c_no'];
            $chapter_url[$i] = $row2['c_url'];

            // ---------------------------------------------------------------------------------
            $c_posted_on[$i] = new DateTime($row2['c_posted_on'], new DateTimeZone('Asia/Kolkata'));  // convert the string to a date variable
            $current_date = new DATETIME("now", new DateTimeZone('Asia/Kolkata'));  // Current Date

            $interval[$i] = post_date_format($current_date, $c_posted_on[$i]);

            echo '
                <div class="chapter-item mt-2">
                    <span>
                        <a href="' . $chapter_url[$i] . '" target="_blank">
                            <button type="button" class="btn btn-outline-dark chapter-btn overflow-hidden">' . $chapter_no[$i] . '</button>
                        </a>
                    </span>
                    <span class="post-on d-block">' . $interval[$i] . '</span>
                </div>';
        }
    }

    echo '
            </div>
        </div>
    </div>';
}
