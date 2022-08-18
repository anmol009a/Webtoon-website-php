<?php
include "partials/_dbconnect.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Update the record
    if (isset($_POST['snoEdit'])) {
        $w_id = $_POST["snoEdit"];
        $webtoon_title = $_POST["titleEdit"];
        $c_no = $_POST["chaptersEdit"];

        // Sql query to be executed
        $sql = "UPDATE `webtoons` SET `w_title` = '$webtoon_title' WHERE `webtoons`.`w_id` = '$w_id'";
        $result = mysqli_query($conn, $sql);

        // fetch last c_no of webtoon
        $sql = "SELECT `c_no` FROM `chapters` where `chapters`.`w_id` = '$w_id' ORDER BY `c_no` DESC LIMIT 1";
        $result2 = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result2);
        if ($result && $result2) {
            $update = true;
        } else {
            echo "We could not update the record successfully";
        }
        
    }
    // -------------------------------------------------
    else {
        // add new webtoon
        $webtoon_title = $_POST['webtoonTitle'];
        $webtoon_url = $_POST['webtoonUrl'];
        $chapter_url = $_POST['chapterUrl'];
        $chapter_no = $_POST['webtoonChapters'];
        $w_cover = $_POST['webtoonCover'];


        // insert webtoon into db
        $sql = "INSERT INTO `webtoons` (`w_title`, `w_url`, `w_cover`) VALUES ('$webtoon_title', '$webtoon_url', '$w_cover')";
        $result = mysqli_query($conn, $sql);

        // fetch current w_id
        $sql = "SELECT `w_id` FROM `webtoons` where `webtoons`.`w_title` ='$webtoon_title'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $webtoon_id = $row['w_id'];

        // insert chapters into db
        $sql = "INSERT INTO `chapters` (`c_no`, `c_url`, `w_id`) VALUES ('$chapter_no', '$chapter_url', '$webtoon_id')";
        $result = mysqli_query($conn, $sql);

        // Alert Confirmation
        if ($result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
    }
}

// =========================================================
// delete Webtoon
if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $delete = true;
    $sql = "DELETE FROM `webtoons` WHERE `webtoons`.`w_id` = $sno";
    $result = mysqli_query($conn, $sql);
}
?>


<!doctype html>
<html lang="en">

<head>
    <?php include 'partials/_header.php'; ?>

    <!-- Jquerry CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

        });
    </script>

    <!-- data table CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
</head>

<body w_id="body-theme" class="bg-dark text-light">

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this Webtoon</h5>
                </div>
                <form action="update_webtoon.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Webtoon Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                        </div>

                        <div class="form-group">
                            <label for="chapters">Note chapters</label>
                            <input type="number" class="form-control" id="chaptersEdit" name="chaptersEdit" rows="3">
                        </div>
                    </div>
                    <div class="modal-footer d-block mr-auto">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <?php include 'partials/_navbar.php'; ?>

    <div class="container my-3">
        <h3>Insert New Webtoon</h3>
        <hr>

        <!-- form to insert webtoon -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="mb-3">
                <label for="webtoonTitle" class="form-label">Webtoon Title</label>
                <input type="text" class="form-control" w_id="webtoonTitle" name="webtoonTitle" aria-describedby="webtoon-title">
            </div>
            <div class="mb-3">
                <label for="webtoonUrl" class="form-label">Webtoon Url</label>
                <input type="text" class="form-control" w_id="webtoonUrl" name="webtoonUrl">
            </div>
            <div class="mb-3">
                <label for="chapterurl" class="form-label">Chapter Url</label>
                <input type="text" class="form-control" w_id="chapterurl" name="chapterUrl">
            </div>
            <div class="mb-3">
                <label for="webtoonChapters" class="form-label">Chapters</label>
                <input type="number" class="form-control" w_id="webtoonChapters" name="webtoonChapters">
            </div>
            <div class="mb-3">
                <label for="webtoonCover" class="form-label">Cover</label>
                <input type="text" class="form-control" w_id="webtoonCover" name="webtoonCover">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- ================================================================ -->
    <div class="container my-4 bg-light">
        <h3 class="text-dark">Webtoons in Database</h3>
        <hr>
        <!-- table -->
        <table class="table" w_id="myTable">
            <!-- cloumns -->
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">chapters</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <!-- rows -->
            <tbody>
                <?php
                $sql = "SELECT * FROM `webtoons` ORDER BY `last_mod` DESC";
                $result = mysqli_query($conn, $sql);
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno = $sno + 1;
                    $w_id = $row['w_id'];

                    $sql2 = "SELECT * FROM `chapters` where w_id= $w_id";
                    $result2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);

                    echo "<tr class=''>
                            <th scope='row' w_id='$w_id'>" . $sno . "</th>
                            <td><a href='" . $row['w_url'] . "'>" . $row['w_title'] . "</a></td>
                            <td>" . $row2['c_no'] . "</td>
                            <td> <button class='edit btn btn-sm btn-primary' w_id='" . $row['w_id'] . "'>Update</button> <button class='delete btn btn-sm btn-primary' w_id='d" . $row['w_id'] . "'>Delete</button>  </td>
                        </tr>";
                }
                ?>


            </tbody>
        </table>
    </div>
    <hr>

    <!-- footer -->
    <?php include 'partials/_footer.php'; ?>

    <!-- JAVASCRIPT -->
    <?php include 'js/_bootstrap_script.php'; ?>

    <script>
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                sno = e.target.w_id.substr(1);
                console.log(sno);
                if (confirm("Are you sure you want to delete this note!")) {
                    console.log("yes");
                    // window.location = `update_webtoon.php?delete=${sno}`;
                    function deleteWebtoon() {
                        url = `update_webtoon.php?delete=${sno}`;
                        fetch(url).then((response) => {
                            return response.text();
                        }).then((data) => {
                            // console.log(data);
                        })
                    }
                    deleteWebtoon();
                    document.getElementById(sno).parentNode.remove();
                } else {
                    console.log("no");
                }
            })
        })

        // edit webtoon
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                chapters = tr.getElementsByTagName("td")[1].innerText;
                console.log(title, chapters);
                titleEdit.value = title;
                chaptersEdit.value = chapters;
                snoEdit.value = e.target.w_id;
                console.log(e.target.w_id)
                $('#editModal').modal('toggle');
            })
        })

        function updateWebtoon() {
            url = "update_webtoon.php";
            data = '{"titleEdit": titleEdit.value,"salary":"123","age":"23"}'
            params = {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: data
            }
            fetch(url, params).then(response => response.json())
                .then(data => console.log(data))
        }
    </script>

</body>

</html>