<?php include('server.php') ?>
<?php 
  if (isset($_SESSION['username'])) {
  	header('location: Home.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="Style.css">
</head>
<body>
  <div class="header">
  	<h2>Login</h2>
  </div>
	 
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Username</label>
  		<input type="text" name="username" value="<?php echo $username; ?>" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Login</button>
  	</div>
  	<p>
  		Not yet a member? <a href="Registration.php">Sign up</a>
  	</p>
  </form>
</body>
</html>
