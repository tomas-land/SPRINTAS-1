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
    
<div id="login">

<div class="container pt-5">
    <div id="login-row" class="row justify-content-center align-items-center ">
        <div id="login-column" class="col-md-4">
            <div id="login-box" class="col-md-12">

                <form id="login-form" class="form" action="index.php" method="post">
                    <h3 class="text-center ">Login</h3>
                    <div class="form-group pt-5 text">
                        <label for="username" class="">Username:</label><br>
                        <input type="text" name="username" id="username" placeholder='username:123' class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="">Password:</label><br>
                        <input type="text" name="password" placeholder='password:123' id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" class="btn  btn-md" value="submit">
                    </div>

                </form>
                <h6><?php echo $msg; ?></h6>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>