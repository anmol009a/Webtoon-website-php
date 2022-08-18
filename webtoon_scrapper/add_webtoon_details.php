<?php
// ----------------------- sql prepare and bind ---------------------------

// insert webtoon into db
$stmt1 = $conn->prepare("INSERT INTO webtoons (`w_title`, `w_url`) VALUES (?,?)");
$stmt1->bind_param("ss", $webtoon_title, $webtoon_url);

// insert chapter into db
$stmt2 = $conn->prepare("INSERT INTO `chapters` (`c_name`,`c_no`, `c_url`, `w_id`) VALUES (?, ?, ?, ?)");
$stmt2->bind_param("sssi", $chapter_name, $chapter_no, $chapter_url, $webtoon_id);

// insert cover_url
$stmt3 = $conn->prepare("INSERT INTO covers (`cover_url`,`w_id`) VALUES (?, ?)");
$stmt3->bind_param("si", $webtoon_cover_url, $webtoon_id);

// fetch w_id
$stmt4 = $conn->prepare("SELECT w_id FROM webtoons WHERE w_title = ?");
$stmt4->bind_param("s", $webtoon_title);

// fetch w_id, cover_url
$stmt13 = $conn->prepare("SELECT w_id, cover_url FROM webtoon_details WHERE w_title = ?");
$stmt13->bind_param("s", $webtoon_title);

// update last_mod, w_url
$stmt5 = $conn->prepare("UPDATE `webtoons` SET `w_url` = ?,`last_mod` = CURRENT_TIMESTAMP WHERE `webtoons`.`w_id` = ?");
$stmt5->bind_param("si", $webtoon_url, $webtoon_id);

// update chapters
$stmt9 = $conn->prepare("UPDATE `chapters` SET `c_name` = ?, c_no = ?, c_url = ? WHERE `w_id` = ? AND c_no = ?");
$stmt9->bind_param("sssis", $chapter_name, $chapter_no, $chapter_url, $webtoon_id, $c_no);

// get chapter No
$stmt10 = $conn->prepare("SELECT c_no, c_name FROM chapters WHERE w_id = ? and c_no = ?");
$stmt10->bind_param("id", $webtoon_id, $chapter_no);

// update covers
$stmt11 = $conn->prepare("UPDATE covers SET `cover_url` = ? WHERE `w_id` = ?");
$stmt11->bind_param("si", $webtoon_cover_url, $webtoon_id);

// get last chapter No
$stmt12 = $conn->prepare("SELECT c_no FROM chapters WHERE w_id = ? ORDER BY c_no ASC LIMIT 1");
$stmt12->bind_param("i", $webtoon_id);

foreach ($webtoons as $webtoon) {
    $webtoon_title = $webtoon->name;
    $webtoon_url = $webtoon->url;
    // $webtoon_cover_url = $webtoon->cover;
    $webtoon_cover_url = isset($webtoon->cover) ? $webtoon->cover : "";

    // fetching webtoon records if exits 
    // fetch w_id 
    $result = $stmt13->execute() or die($stmt13->error);
    $result = $stmt13->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        echo "<hr>";
        echo "Webtoon Present : $webtoon_title";
        echo "<br>";

        $webtoon_id = $row['w_id']; // fetch w_id 
        $cover_url = $row['cover_url']; // fetch cover_url 

        try {
            // update cover details
            if (isset($cover_url)) {
                $result = $stmt11->execute() or die($stmt11->error);
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
                    $result = $stmt10->execute() or die($stmt10->error);
                    $result = $stmt10->get_result();
                    $row = $result->fetch_assoc();
                    if ($row) {
                        $chapter_name = $row['c_name'];
                        if ($result) {
                            echo "Chapter Present : $chapter_name";
                            echo "<br>";
                        }
                    } else {
                        // get last c_no
                        $result = $stmt12->execute() or die($stmt12->error);
                        $result = $stmt12->get_result();
                        $row = $result->fetch_assoc();
                        $c_no = isset($row['c_no']) ? $row['c_no'] : false;

                        if (!$c_no) {
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
                    $stmt5->execute() or die($stmt5->error);
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
            // inserting webtoon details into db
            $result1 = $stmt1->execute() or die($stmt1->error);  // insert webtoon into db 
            if ($result1) {
                echo "<hr>";
                echo "Inserted webtoon: " . $webtoon_title;
                echo "<br>";

                // fetch w_id 
                $stmt4->execute() or die($stmt4->error);
                $result = $stmt4->get_result();
                $row = $result->fetch_assoc();
                $webtoon_id = $row['w_id'];

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
$stmt4->close();
$stmt5->close();
$stmt9->close();
$stmt10->close();
$stmt11->close();
$stmt12->close();
$stmt13->close();
