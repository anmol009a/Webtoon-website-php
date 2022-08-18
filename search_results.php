<?php
$dir = "C://xampp//htdocs//Webtoon.me//";
include $dir . 'partials/_dbconnect.php';
include $dir . 'functions.php';
?>

<!doctype html>
<html lang="en">

<head>
    <?php include $dir . 'partials/_header.php'; ?>
</head>

<body>

    <!-- Navbar -->
    <?php include $dir . 'partials/_navbar.php'; ?>

    <!-- Search Results -->
    <div class="container pt-2">
        <h2 class="text-center">Search Results</h2>
        <hr>
        <!--  -->
        <div class="post-listing row row-cols-2 row-cols-md-4 row-cols-lg-6">
            <?php
            $searchString = $_GET['s'];
            $noresults = true;

            // fetching webtoon record if exits with w_id
            $stmt = $conn->prepare("SELECT * FROM `chapters` WHERE `w_id`= ? ORDER BY `c_no` DESC LIMIT 2");
            $stmt->bind_param("i", $webtoon_id);


            $sql = "SELECT * FROM `webtoon_details` WHERE `w_title` LIKE '%$searchString%' ORDER BY `last_mod` DESC LIMIT 30";
            $result = mysqli_query($conn, $sql);

            // loop to print webtoons
            while ($row = mysqli_fetch_assoc($result)) {
                $noresults = false;
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

                        $chapter_name[$i] = isset($row2['c_name']) ? $row2['c_name'] : $row2['c_no'];
                        $chapter_url[$i] = $row2['c_url'];

                        // ---------------------------------------------------------------------------------
                        $c_posted_on[$i] = new DateTime($row2['c_posted_on'], new DateTimeZone('Asia/Kolkata'));  // convert the string to a date variable
                        $current_date = new DATETIME("now", new DateTimeZone('Asia/Kolkata'));  // Current Date

                        $interval[$i] = post_date_format($current_date, $c_posted_on[$i]);

                        echo '
                            <div class="chapter-item mt-2">
                                <span>
                                    <a href="' . $chapter_url[$i] . '" target="_blank">
                                        <button type="button" class="btn btn-outline-dark chapter-btn">' . $chapter_name[$i] . '</button>
                                    </a>
                                </span>
                                <span class="post-on">' . $interval[$i] . '</span>
                            </div>';
                    }
                }


                echo '
            </div>
        </div>
    </div>';
            }
            if($noresults){
                echo "No Webtoons found for '$searchString'";
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
    <!-- footer -->
    <?php include 'partials/_footer.php'; ?>
    <!-- JAVASCRIPT -->
    <?php include 'js/_bootstrap_script.php'; ?>
</body>

</html>