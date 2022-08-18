<?php

$dir = "C://xampp//htdocs//Webtoon.me//";
include $dir . "partials/_dbconnect.php";
include $dir . "functions.php";

$no_results = true;

$search_string = $_GET['s'];

$website_url = 'https://www.webtoon.xyz/read/';


$sql = "SELECT * FROM `webtoons` WHERE `w_title` LIKE '%$search_string%' ORDER BY `last_mod` LIMIT 6";
$result = mysqli_query($conn, $sql);

echo '<ul class="list-group">';
while ($row = mysqli_fetch_assoc($result)) {
    $noresults = false;
    $webtoon_title = $row['w_title'];
    $webtoon_url = $row['w_url'];



    // Display the search result
    echo '<li class="search-item">
            <a href="' . $webtoon_url . '" class="text-decoration-none text-reset" target="_blank">
                <div class="py-1 px-2">' . $webtoon_title . '</div>
            </a>
        </li>';
}

if ($noresults) {
    echo '<li class="search-item">
                    No webtoons found
                </li>';
}
echo '</ul>';