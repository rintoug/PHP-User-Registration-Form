<?php
$error = array();
//Check the form post triggers
if($_SERVER['REQUEST_METHOD']=='POST') {
	
	//Form validation
	/* Form Required Field Validation */
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
			$error[] = "All Fields are required";
			break;
		}
	}
	
	/* Email Validation */	
	if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$error[] = "Invalid Email Address";
		
	}
	/* Password Match Validation */
	if($_POST['password'] != $_POST['confirm_password']){ 
		$error[] = 'Passwords does not match'; 
	}
	
	if(empty($error)) {
		//Success we can enter the entry to Database
		//Database connection
		try {
				$conn = new PDO("mysql:host=localhost;dbname=tutsplanet", 'root', '');		
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT); //hashing password
				
				$query = "INSERT INTO `customers` (`username`, `firstname`,`lastname`, `email`, `password`, `gender`) 
										   VALUES (:username, :firstname,:lastname, :email, :password, :gender)";
				$stmt = $conn->prepare($query);
				$stmt->bindParam('username', $_POST['username']);
				$stmt->bindParam('firstname', $_POST['firstname']);
				$stmt->bindParam('lastname', $_POST['lastname']);
				$stmt->bindParam('email', $_POST['email']);
				$stmt->bindParam('password', $password);
				$stmt->bindParam('gender', $_POST['gender']);
				$stmt->execute();
				header("Location:index.php?msg=1");
			}
			catch(PDOException $e){
				echo "Connection failed: " . $e->getMessage();exit;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PHP registration form</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="width:500px;background:#E6EFCB;">
  <?php if(!empty($error)):?>
  <div class="alert alert-danger">
    <?php foreach($error as $value):?>
    <?php print $value?><br>
    <?php endforeach;?>
  </div>
  <?php endif;?>
  <?php if(isset($_GET['msg'])&& $_GET['msg']==1):?>
  <div class="alert alert-success"> <strong>Success!</strong> Regsitered successfully. </div>
  <?php endif;?>
  <form method="post">
    <div class="form-group">
      <label for="Username">Username</label>
      <input type="text" class="form-control" id="username" name="username" placeholder="Username"  value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
    </div>
    <div class="form-group">
      <label for="Firstname">Firstname</label>
      <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; ?>">
    </div>
    <div class="form-group">
      <label for="Lastname">Lastname</label>
      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>">
    </div>
    <div class="form-group">
      <label for="Email">Email</label>
      <input type="email" class="form-control" id="email"  name="email" placeholder="Email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
    </div>
    <div class="form-group">
      <label for="Password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="form-group">
      <label for="Confirm Password">Confirm Password</label>
      <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <label for="Gender">Gender</label>
      <label class="radio-inline">
        <input type="radio" name="gender"  value="male" checked>
        Male </label>
      <label class="radio-inline">
        <input type="radio" name="gender"  value="female">
        Female </label>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <br><br>
  </form>
</div>
</body>
</html>