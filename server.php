<?php 
	session_start();

	// Değisken tanımlama
	$username = "";
	$email    = "";
	$errors = array(); 
	$_SESSION['success'] = "";

	// Database bağlantısı
	$db = mysqli_connect('localhost', 'root', '', 'todo');

	// REGISTER USER
	if (isset($_POST['reg_user'])) {
		// formdan gelen bütün giriş değerlerini karşılanması
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		// form doğrulama: formun doğru şekilde doldurulduğundan emin olun
		if (empty($username)) { array_push($errors, "Username is required"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");
		}

		// Gönderilen formda hata yoksa kullanıcı kaydı
		if (count($errors) == 0) {
			$password = md5($password_1);//veritabanına kaydetmeden önce parolayı şifreleyin
			$query = "INSERT INTO users(username, email, password) 
					  VALUES('$username', '$email', '$password')";
			mysqli_query($db, $query);

			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: index.php');
		}

	}

	// ... 

	// Kullanıcı Girişi
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
			$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "Welcome $username";
				header('location: index.php');
			}else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}

