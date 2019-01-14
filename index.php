<?php
require __DIR__ . '/init.php';

  if (isset($_POST['send'])) {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $username = $fname . ' ' . $lname;

    $secret = '6LfUfIkUAAAAAPzMEuG5PaQhvEqJocYGTg0hfSGl';
    $response = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $data = [
      'secret' => $secret,
      'response' => $response
    ];

    $options = [
      'http' => [
        'method' => 'POST',
        'content' => http_build_query($data)
      ]
    ];

    $context = stream_context_create($options);
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success == false) {
      echo 'false';
    } else if ($captchaSuccess->success == true) {
      DB::query('INSERT INTO registration VALUE (null, :fname, :lname, :username, :password, :email)', [':fname' => $fname, ':lname' => $lname, ':username' => $username, ':email' => $email, ':password' => password_hash($pass, PASSWORD_BCRYPT)]);
      echo 'Data store!';
    }

  }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="fname" placeholder="Firstname..."><br><br>
  <input type="text" name="lname" placeholder="Lastname..."><br><br>
  <input type="email" name="email" placeholder="Email..."><br><br>
  <input type="password" name="password" placeholder="Password..."><br><br>
  <div class="g-recaptcha" data-sitekey="6LfUfIkUAAAAAOJQhQ_91_e-5LSQ5_nJeb7j_o5k"></div>
  <input type="submit" name="send" value="Send"><br><br>
</form>
<script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>