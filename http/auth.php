<?php
if ($file == 'auth') {
	if ($action == 'login') {
		$email = secure_data($_POST['email']);
		$password = secure_data($_POST['password']);

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
	if ($action == "register") {
		$firstname = secure_data($_POST['firstname']);
		$lastname = secure_data($_POST['lastname']);
		$othername = secure_data($_POST['othername']);
		$tel = secure_data($_POST['tel']);
		$email = secure_data($_POST['email']);
		$class_id = secure_data($_POST['class_id']);
		$password = secure_data($_POST['password']);
		$confirm_password = secure_data($_POST['confirm_password']);

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
			$actiontudent_data = [
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

			if (save_data("users", $actiontudent_data)) {
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
	if ($action == "change_pwd") {
		$old_pwd = secure_data($_POST['old_pwd']);
		$new_pwd = secure_data($_POST['new_pwd']);
		$confirm_pwd = secure_data($_POST['confirm_pwd']);
		if (password_verify($old_pwd, $global_var['user']['password'])) {
			$user_data = [
				"password" => password_hash($new_pwd, PASSWORD_DEFAULT),
			];
			if ($new_pwd != $confirm_pwd) {

				$data = array(
					'status'	=>	201,
					'message'	=>	'Password Mismatch!'
				);
			} elseif (strlen($new_pwd) < 6) {
				$data = [
					'status'  => 201,
					'message' => 'Password must be at least 6 characters long!'
				];
			} else if (update_data("users", $user_data, "WHERE id = '" . $global_var['user']['id'] . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Password changed successfully!',
					'url' => 'index.php?page=user_profile'

				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Class registration failed!'
				);
			}
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Enter Correct Password!'
			);
		}
	}
}
