
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Assignment Management System</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url();?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=base_url();?>assets/css/floating-label.css" rel="stylesheet">
  </head>

  <body>
    <form class="form-signin" action="<?=site_url('app/login_process');?>" method="POST">
      <div class="text-center mb-4">
        <img class="mb-4" src="<?=base_url();?>assets/img/ams.png" alt="" width="120" height="120">
        <h1 class="h3 mb-3 font-weight-normal">Assignment Management System</h1>
        
      </div>

      <div class="form-label-group">
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <label for="inputEmail">Username</label>
      </div>

      <div class="form-label-group">
        <input type="password"name="password" class="form-control" placeholder="Password" required>
        <label for="inputPassword">Password</label>
      </div>

      <!-- <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div> -->
      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      <p class="mt-5 mb-3 text-muted text-center">&copy; 2018</p>
    </form>
  </body>
</html>
