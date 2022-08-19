<?php

/*
# prepared stmt cache at 000.webhost was not able to store 12 prepared sql stmt, therefore I had to use
dynamic sql to perform the CRUD operations

# Also, there was ssl verification problem for "file_get_content()" function and there was two methods to solve this:
    1. to by-pass ssl verification
    2. to download certificates

# Below prepared stmt are used because normal sql stmt are prone to sql injection, specifically in this case webtoon name and chapter name
contains special characters which destroys the sql
*/


// insert webtoon into db
$stmt1 = $conn->prepare("INSERT INTO webtoons (`w_title`, `w_url`) VALUES (?,?)");
$stmt1->bind_param("ss", $webtoon_title, $webtoon_url);

// insert chapter into db
$stmt2 = $conn->prepare("INSERT INTO `chapters` (`c_name`,`c_no`, `c_url`, `w_id`) VALUES (?, ?, ?, ?)");
$stmt2->bind_param("sssi", $chapter_name, $chapter_no, $chapter_url, $webtoon_id);

// insert cover_url
$stmt3 = $conn->prepare("INSERT INTO covers (`cover_url`,`w_id`) VALUES (?, ?)");
$stmt3->bind_param("si", $webtoon_cover_url, $webtoon_id);

// update chapters
$stmt9 = $conn->prepare("UPDATE `chapters` SET `c_name` = ?, c_no = ?, c_url = ? WHERE `w_id` = ? AND c_no = ?");
$stmt9->bind_param("sssis", $chapter_name, $chapter_no, $chapter_url, $webtoon_id, $c_no);

foreach ($webtoons as $webtoon) {
    $webtoon_title = $webtoon->name;
    $webtoon_url = $webtoon->url;
    // $webtoon_cover_url = $webtoon->cover;
    $webtoon_cover_url = isset($webtoon->cover) ? $webtoon->cover : "";

    
    // fetch w_id, cover_url
    $sql = "SELECT w_id, cover_url FROM webtoon_details WHERE w_title = '$webtoon_title'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        echo "<hr>";
        echo "Webtoon Present : $webtoon_title";
        echo "<br>";

        $webtoon_id = $row['w_id']; // fetch w_id 
        $cover_url = $row['cover_url']; // fetch cover_url 

        try {
            // update cover details
            if (isset($cover_url)) {
                // update covers
                $sql = "UPDATE covers SET `cover_url` = '$webtoon_cover_url' WHERE `w_id` = $webtoon_id";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    echo "Updated cover details : " . $webtoon_cover_url;
                    echo "<br>";
                }
            } else {
                $result = $stmt3->execute() or die($stmt3->error);    // insert cover details

                if ($result) {
                    echo "Inserted cover details : " . $webtoon_cover_url;
                    echo "<br>";
                }
            }

            if (isset($webtoon->chapter)) {

                $chapter_updated = false;

                for ($i = 1; $i > -1; $i--) {
                    $chapter_name = $webtoon->chapter[$i]->name;
                    preg_match('/[\d.]{1,5}/', $chapter_name, $matches);
                    $chapter_no = $matches[0];
                    $chapter_url = $webtoon->chapter[$i]->url;
                    // get C-no
                    // fetch chapter No
                    $sql = "SELECT c_no, c_name FROM chapters WHERE w_id = $webtoon_id and c_no = $chapter_no";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $chapter_name = $row['c_name'];
                        if ($result) {
                            echo "Chapter Present : $chapter_name";
                            echo "<br>";
                        }
                    } else {
                        // get last c_no
                        // get last chapter No
                        $sql = "SELECT c_no FROM chapters WHERE w_id = $webtoon_id ORDER BY c_no ASC LIMIT 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);


                        $c_no = isset($row['c_no']) ? $row['c_no'] : false;

                        if (!$c_no) {
                            // insert chapter into db
                            $result = $stmt2->execute() or die($stmt2->error); // insert chapter into db
                            if ($result) {
                                echo "Inserted Chapter : $chapter_name";
                                echo "<br>";
                                $chapter_updated = true;
                            }
                        } elseif ($c_no < $chapter_no - 1) {
                            $result = $stmt9->execute() or die($stmt9->error); // update chapter into db
                            if ($result) {
                                echo "Updated Chapter : $chapter_name";
                                echo "<br>";
                                $chapter_updated = true;
                            }
                        }
                    }
                }
                if ($chapter_updated) {
                    // update last_mod, w_url
                    $sql = "UPDATE `webtoons` SET `w_url` = '$webtoon_url',`last_mod` = CURRENT_TIMESTAMP WHERE `webtoons`.`w_id` = $webtoon_id";
                    $result = mysqli_query($conn, $sql);

                    echo "Upadted Last_mod : $webtoon_title";
                    echo "<br>";
                }
            }
        } catch (Exception $e) {
            // echo "<br>";
            echo "Failed to update webtoon details : " . $e->getMessage();
            echo "<br>";
        }
    } else {
        try {
            $result1 = $stmt1->execute() or die($stmt1->error);  // insert webtoon into db 

            if ($result) {
                echo "<hr>";
                echo "Inserted webtoon: " . $webtoon_title;
                echo "<br>";

                // fetch w_id 
                // fetch w_id, cover_url
                $sql = "SELECT w_id, cover_url FROM webtoon_details WHERE w_title = '$webtoon_title'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $webtoon_id = $row['w_id'];

                // insert cover_url
                $result = $stmt3->execute() or die($stmt3->error);    // insert cover details
                if ($result) {
                    echo "Inserted cover details : " . $webtoon_cover_url;
                    echo "<br>";
                }

                if (isset($webtoon->chapter)) {

                    for ($i = 0; $i < 2; $i++) {
                        $chapter_name = $webtoon->chapter[$i]->name;
                        preg_match('/[\d.]{1,4}/', $chapter_name, $matches);
                        $chapter_no = $matches[0];
                        $chapter_url = $webtoon->chapter[$i]->url;

                        // insert chapter into db
                        $result = $stmt2->execute() or die($stmt2->error); // insert chapter into db
                        if ($result) {
                            echo "Inserted Chapter : $chapter_name";
                            echo "<br>";
                        }
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Failed to insert webtoon details : " . $e->getMessage();
            echo "<br>";
        }
    }
}
$stmt1->close();
$stmt2->close();
$stmt3->close();
$stmt9->close();

// bypasses ssl verification to download img
$arrContextOptions=array(
    "ssl"=>array(
         "verify_peer"=>false,
         "verify_peer_name"=>false,
    ),
);