<?php

require_once __DIR__ . '/vendor/autoload.php';

use Parsehub\Parsehub;


function post_date_format($baseObject, $targetObject)
{
    $interval = date_diff($baseObject, $targetObject);    // Calculate the difference

    // interval format decider
    if ($interval->format('%d') > 8) {    // if days > 8, print date
        $interval = date_format($targetObject, "M d, Y");
    } elseif ($interval->format('%d') > 0) {    // calculate days
        $interval = $interval->format('%d days ago');
        echo "<br>";
    } elseif ($interval->format('%h')) {    // calculate hours
        $interval = $interval->format('%h hours ago');
    } elseif ($interval->format('%i')) {    // calculate minutes
        $interval = $interval->format('%i minutes ago');
    } else {    // calculate second
        $interval = $interval->format('%s seconds ago');
    }
    return $interval;
}

function save_img($img_url, $img_name, $img_path)
{

    if (file_put_contents($img_path, file_get_contents($img_url))) { //check if img link works
        // Function to write image into file
        // storing img
        echo "Img Saved! : $img_name";
        echo "<br>";
    } else {
        echo "Failed to download img : $img_name";
        echo "<br>";
    }
}

function download_file($url, $file_name)
{
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using basename
    if (file_put_contents($file_name, file_get_contents($url))) {
        echo "File downloaded successfully";
        echo "<br>";
    } else {
        echo "File downloading failed.";
        echo "<br>";
        echo "<br>";
    }
}

function open_file($file_name)
{
    // ---------------------------------------------------------------------
    $fptr = fopen($file_name, "r");  // opening file in read mode
    $content = fread($fptr, filesize($file_name));   // reading contents of the file
    fclose($fptr);  // closing file
    return $content;
}

function run($api_key, $project_token)
{
    // Run a parsehub project:
    $parsehub = new Parsehub($api_key);
    $options = array();
    $run_obj = $parsehub->runProject($project_token, $options);
    $run_token = $run_obj->run_token;
    echo "Starting a new Run<br>";
    echo "Run Token : $run_token";
    echo "<br>";
    return $run_token;
}

function checkRunStatus($api_key, $run_token)
{
    // check run status
    // Get a particular run, Pass the run token:
    $parsehub = new Parsehub($api_key);
    $run = $parsehub->getRun($run_token);
    $run_status = $run->status;
    echo "Run Status : $run_status";
    echo "<br>";
    return $run_status;
}

function getRunData($api_key, $run_token)
{
    // Get data for a particular run, Pass the run token:
    $parsehub = new Parsehub($api_key);
    $data = $parsehub->getRunData($run_token);
    $data =  json_decode($data);    // converts json into array of object
    // print_r($data);
    $webtoons =  isset($data) ? $data->webtoon : NULL;    // array of objects
    return $webtoons;
}

function printWebtoons($webtoons)
{
    foreach ($webtoons as $webtoon) {
        $project_title = $webtoon->name;
        $webtoon_url = $webtoon->url;
        // $webtoon_cover_url = $webtoon->cover;
        $webtoon_cover_url = isset($webtoon->cover) ? $webtoon->cover : "";
        echo "<hr>Webtoon Title : $project_title<br>Webtoon URL : $webtoon_url<br>Webtoon Cover URL : $webtoon_cover_url<br>";
        if (isset($webtoon->chapter)) {
            for ($i = 0; $i < count($webtoon->chapter); $i++) {
                $chapter[$i] = $webtoon->chapter[$i]->name;
                $chapter_url[$i] = $webtoon->chapter[$i]->url;
                echo "$chapter[$i] : $chapter_url[$i]<br>";
            }
        }
    }
}

function deleteRun($api_key, $run_token)
{
    $parsehub = new Parsehub($api_key);
    $delete_run = $parsehub->deleteProjectRun($run_token);
    // var_dump($delete_run);
}

function db_connect()
{
    // Connecting to the Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    // $database = "webtoon_world";
    $database = "webtoon_world2";

    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Die if connection was not successful
    if (!$conn) {
        die("Sorry we failed to connect: " . mysqli_connect_error());
    } else {
        // echo "Connection was successful<br>";
    }
}
