<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

define('APP_PATH', str_replace('public', '', realpath('..')));
if (!empty($_POST)) {
    $db_host = $_POST['dbhost'];
    $db_user = $_POST['dbusername'];
    $db_pwd = $_POST['dbpassword'];
    $db_name = $_POST['dbname'];
    $user_name = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_password2 = $_POST['password2'];
    $baseurl = $_POST['baseurl'];
    $pagetitle = $_POST['pagetitle'];

    if ($db_host && $db_user && $db_name
        && $user_name && $user_email && $user_password && $user_password2
        && $baseurl && $pagetitle
    ) {
        if ($user_password == $user_password2) {
            // Create connection
            $conn = new mysqli($db_host, $db_user, $db_pwd, $db_name);
            // Check connection
            if ($conn->connect_error) {
                echo "Connection database failed: " . $conn->connect_error;
            } else {
                $sql = file_get_contents('truecustomer.sql');
                $sql .= "
                    insert  into `users`(`id`,`created`,`user_created_id`,`deleted`,`username`,`email`,`password`,`name`,`status`,`is_admin`)
                    values (1,NOW(),1,0,'$user_name','$user_email','" . md5($user_password) . "','Admin','Active',1);
                ";

                if ($conn->multi_query($sql) === TRUE) {
                    $caro_db = array();
                    $caro_db['host'] = $db_host;
                    $caro_db['username'] = $db_user;
                    $caro_db['password'] = $db_pwd;
                    $caro_db['dbname'] = $db_name;
                    $caro_db['charset'] = 'utf8';

                    $file = fopen(APP_PATH . '/app/config/database.php', 'w');
                    fwrite($file, "<?php\n\nreturn " . var_export($caro_db, true) . ";\n");
                    fclose($file);

                    $caro_config = array();
                    $caro_config['systems'] = array(
                        'base_url' => $baseurl,
                        'page_title' => $pagetitle,
                        'theme' => 'default',
                        'logo' => 'https://avatars2.githubusercontent.com/u/24425526?v=3&s=460',
                    );
                    $caro_config['email']['email'] = 'info@up5.vn';

                    $file = fopen(APP_PATH . '/app/config/system.php', 'w');
                    fwrite($file, "<?php\n\nreturn " . var_export($caro_config, true) . ";\n");
                    fclose($file);

                    header('Location: ' . $baseurl);
                } else {
                    echo "Error database." . $db_name . "<br>" . $conn->error;
                }

                $conn->close();
            }
        } else {
            echo 'Password again incorrect!';
        }
    } else {
        echo 'Data is empty!';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>TrueCustomer Installation</title>
    <meta name="generator" content="Jacky" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="A Open Source Framework base on Phalcon PHP" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container-full">
    <div style="width: 960px; margin: 0 auto">
        <div class="text-center">
            <h1>TrueCustomer</h1>
            <p class="lead">A Open Source Framework base on Phalcon PHP</p>
        </div>

        <p class="lead">TrueCustomer Config</p>
        <p style="font-style: italic">We will override info database in apps/config/database.php</p>

        <form action="index.php" method="post" class="form-horizontal" style="padding: 20px; padding-top: 30px; border: 1px solid #fff;">
            <div class="row">
                <div class="col-xs-12">
                    <h4 style="text-align: center">Database Config</h4>

                    <div class="form-group">
                        <label for="dbhost" class="col-sm-4 control-label">DB Host</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dbhost" name="dbhost" value="localhost" placeholder="DB Host" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dbusername" class="col-sm-4 control-label">DB User</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dbusername" name="dbusername" value="root" placeholder="DB User" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dbpassword" class="col-sm-4 control-label">DB Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="dbpassword" name="dbpassword" placeholder="DB Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dbname" class="col-sm-4 control-label">DB Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dbname" name="dbname" value="caroframework" placeholder="DB Name" required>
                        </div>
                    </div>

                    <h4 style="text-align: center">User Admin Config</h4>

                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="username" name="username" value="admin" placeholder="Username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" value="support@carodev.com" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password2" class="col-sm-4 control-label">Password Again</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password2" name="password2" placeholder="Password Again" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin: 20px 0; border-top: 1px solid #fff">
                <div class="col-xs-12">
                    <h4 style="text-align: center">Page Config</h4>

                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">Base Url</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="baseurl" name="baseurl" value="http://<?php echo $current_url ?>" placeholder="Base Url" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">Page title</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pagetitle" name="pagetitle" value="CaroFramework" placeholder="Page title" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6"></div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Install</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <p class="pull-right">
                ©Copyright 2015 Jacky - CaroCRM<sup>TM</sup>. &nbsp;
                <a href="http://www.bootply.com">Template from Bootply</a>
            </p>
            <br><br>
        </div>
    </div>
</div>

</body>
</html>
