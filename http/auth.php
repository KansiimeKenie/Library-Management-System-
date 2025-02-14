<?php
if ($f == 'auth') {
	if ($s == 'login') {
		$email = __secure($_POST['email']);
		$password = __secure($_POST['password']);

		if (login($email, $password)) {
			$user = $db->where('email', $email)->getOne('users');
			$_SESSION['user_id'] = $user->id;
			$_COOKIE['user_id'] = $user->id;
			$data = array(
				'status' => 200,
				'message'	=>	'Logged In',
				'url'	=>	'index.php?page=index'
			);
		} else {
			$data = array(
				'status' => 201,
				'message'	=>	'Incorrect credentials'
			);
		}
	}
	if ($s == "register") {
		$firstname = __secure($_POST['firstname']);
		$lastname = __secure($_POST['lastname']);
		$othername = __secure($_POST['othername']);
		$tel = __secure($_POST['tel']);
		$email = __secure($_POST['email']);
		$class_id = __secure($_POST['class_id']);
		$password = __secure($_POST['password']);
		$confirm_password = __secure($_POST['confirm_password']);

		if (empty($firstname) || empty($lastname) || empty($tel) || empty($email) || empty($class_id) || empty($password) || empty($confirm_password)) {
			$data = [
				'status'  => 201,
				'message' => 'All fields are required!'
			];
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$data = [
				'status'  => 201,
				'message' => 'Invalid email format!'
			];
		} elseif ($password !== $confirm_password) {
			$data = [
				'status'  => 201,
				'message' => 'Passwords do not match!'
			];
		} elseif (strlen($password) < 6) {
			$data = [
				'status'  => 201,
				'message' => 'Password must be at least 6 characters long!'
			];
		} elseif (exists("users", "WHERE email = '" . $email . "'")) {
			$data = [
				'status'  => 201,
				'message' => 'Student already registered!'
			];
		} else {
			$student_data = [
				"firstname"    => $firstname,
				"lastname"     => $lastname,
				"othername"    => $othername,
				"class_id"     => $class_id,
				"tel"          => $tel,
				"email"        => $email,
				"user_type"    => "stud",
				"password"     => password_hash($password, PASSWORD_DEFAULT),
				"date_created" => getCurrentDate(),
			];

			if (save_data("users", $student_data)) {
				$data = [
					'status'  => 200,
					'message' => 'Registered successfully!',
					'url'     => 'index.php?page=login'
				];
			} else {
				$data = [
					'status'  => 201,
					'message' => 'Registration failed!'
				];
			}
		}
	}
}
