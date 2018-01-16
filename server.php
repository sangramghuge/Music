<?php
session_start();

// variable declaration
$username = "";
$name = "";
$email    = "";
$errors = array(); 
$_SESSION['success'] = "";

// connect to database
$db = mysqli_connect('localhost', 'root', 'root', 'Music');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $name = mysqli_real_escape_string($db, $_POST['name']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($name)) { array_push($errors, "name is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }
  if (preg_match('/[\'^£$%&*()}{#~?><>,|=+¬-]/', $username))
  {
      array_push($errors, "Username has special charachter");
  }
  if (preg_match('/[\'^£$%&*()}{#~?><>,|=+¬-]/', $name))
  {
        array_push($errors, "Name has special charachter");
  }
  if (preg_match('/[\'^£$%&*()}{#~?><>,|=+¬-]/', $email))
  {
        array_push($errors, "Email has special charachter");
  }




  $query = "SELECT * FROM Users WHERE username='$username'";
  $results = mysqli_query($db, $query);
  if(mysqli_num_rows($results)>0)
  {
      array_push($errors,"Username already exists");
  }

    $query = "SELECT * FROM Users WHERE email ='$email'";
    $results = mysqli_query($db, $query);
    if(mysqli_num_rows($results)>0)
    {
        array_push($errors,"EmailID already exists, Try Logging In");
    }



  // register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database
  	$query = "INSERT INTO Users (username, name, email, password) 
  			  VALUES('$username', '$name', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: Home.php');
  }

}
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM Users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: Home.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>
