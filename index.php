<?php
session_start();



// DELETE FILE
if (isset($_POST['delete'])) {
    $deleted_file = $_POST['delete'];
    if (is_file($_POST['delete'])) {
        unlink($deleted_file);
    }
}


// CREATE FOLDER
if (isset($_POST['create'])) {
    $new_folder = $_GET["path"] . $_POST['create'];
    if (is_dir($new_folder)) {
        echo 'Directory exist';
    } else {
        mkdir($new_folder);
    }
}


// FILE DOWNLOAD 
if (isset($_POST['download'])) {
    $file = './' . $_GET["path"] . $_POST['download'];
    $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
    ob_clean();
    ob_start();
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileToDownloadEscaped));
    ob_end_flush();
    readfile($fileToDownloadEscaped);
    exit;
}


// FILE UPLOAD
if (isset($_FILES['image'])) {
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
    $extensions = array("jpeg", "jpg", "png");
    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }
    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }
    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, $_GET['path'] . $file_name);
        $message = 'File uploaded';
    } else {
        print_r($errors);
    }
}


// LOGOUT

if (isset($_GET['action']) and $_GET['action'] == 'logout') {
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
    unset($_SESSION['action']);
    header('Location: login.php');
}


// LOGIN

$msg = '';
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($_POST['username'] == '123' && $_POST['password'] == '123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = 'Mindaugas';
    } else {
        $msg = 'Wrong username or password';
    }
}

// COOKIE
setcookie("counter", "Tomas", time() + 3600, "/", "", 0);
session_start();
if (isset($_SESSION['counter'])) {
    $_SESSION['counter'] += 1;
} else {
    $_SESSION['counter'] = 1;
}



?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>

<body>




    <?php
    if ($_SESSION['logged_in'] == true) {


        print('<span class="mt-5 col-10 d-flex justify-content-end " >Click here to  &nbsp <a href="index.php?action=logout" style="color:#96b0d3">  LOGOUT.</a> </span>');

        $path = './' . $_GET['path'];
        $files_and_dirs = scandir($path);
        print('<div class="d-flex justify-content-end col-2 mt-1"><button class="btn"><a href="'.dirname($_GET['path']) . '">back</a></button></div>'); 
        ?>


        <div class="container col-10 mt-2">
            <table class="table table-striped text-center ">
                <tr>
                    <th style="width: 20%">Type</th>
                    <th class="">Name</th>
                    <th class="">Action</th>
                    <?php


                    print('<h4>Current directory: /SPRINTAS1/' . (urldecode($_GET['path'])) . '</h4>');
                    foreach ($files_and_dirs as $fnd) {
                        if ($fnd != ".." and $fnd != ".") {
                            print('<tr>');
                            print('<td>' . (is_dir($_GET["path"].$fnd) ? "Directory" : "File") . '</td>');
                            print('<td>' .
                                '<a href="' . (is_dir($_GET["path"].$fnd) ? ((isset($_GET['path'])) ? $_SERVER['REQUEST_URI']. $fnd . '/' : $_SERVER['REQUEST_URI'] . '?path=' . $fnd . '/') 
                                                                                                                                        : $_GET["path"] . $fnd) . '">' . $fnd . '</a></td>');
                            print('<td id="action-td">');

                            if (is_file($_GET['path'] . $fnd)) {
                                print('<form action="" method="POST">
                            <input type="hidden" name="delete" value="' . $_GET["path"] . $fnd . '">
                            <input type="submit" class="btn btn-danger" value="delete">
                          </form>

                          <form action="" method="POST">
                            <input type="hidden" name="download" value="' . $_GET["path"]. $fnd . '">
                            <button class="btn btn-warning" type="submit">Download</button>
                          </form>');
                            }

                            print('</td></tr>');
                        }
                    }
                    print("</table></div><br>");
                  
                    ?>


                    <div class="create_upload">


                        <div class="upload">
                            <div>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input class="" type="file" name="image">
                                    <button type="submit" class="btn btn-success">Upload</button>
                                    <?php echo '<span style="color:#96b0d3">' . $message . '</span>' ?>
                                </form>
                            </div>
                            <ul>
                                <br>
                                <li>Sent file: <?php echo $_FILES['image']['name'];  ?>
                                <li>File size: <?php echo $_FILES['image']['size'];  ?>
                                <li>File type: <?php echo $_FILES['image']['type']; ?>
                            </ul>
                        </div>

                        <form action="" method="POST">
                            <input type="text" class="create_dir" name="create">
                            <input type="submit" class="btn btn-success" value="Create directory">

                        </form>



                    </div>
                    <br>
                    <br>
                    <div class="text-center">YUO HAVE VISITED <?php print($_SESSION['counter']) ?></div>
                <?php } else { 
                   
                   header('Location: login.php');
                    ?>



                    


                <?php     } ?>
</body>

</html>


<!-- kai logout lieka url iejus neina narsyt 
neina ieiti i gilesni folder-->