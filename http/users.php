<?php
if ($f == "users") {

	if ($s == "add_new_librarian") {
		$firstname = __secure($_POST['fname']);
		$lastname = __secure($_POST['lname']);
		$othername = __secure($_POST['oname']);
		$email = __secure($_POST['email']);
		$tel = __secure($_POST['tel']);
		$password = password_hash(__secure($_POST['password']), PASSWORD_DEFAULT);


		if (empty($firstname)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert First Name'
			);
		} elseif (empty($lastname)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Last Name!'
			);
		} elseif (empty($email)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Email!'
			);
		} elseif (empty($tel)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Tel No.!'
			);
		} elseif (empty($password)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert password!'
			);
		} else {

			$user_data = [
				"firstname" => $firstname,
				"lastname" => $lastname,
				"othername" => $othername,
				"email" => $email,
				"tel" => $tel,
				"password" => $password,
				"user_type" => "lib",
				"date_created" => getCurrentDate(),
				"created_by" => 1,
			];

			if (save_data("users", $user_data)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Librarian registered successfully!',
					'url' => 'index.php?page=all_libs'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Class registration failed!'
				);
			}
		}
	}
	if ($s == "edit_class") {
		$id = __secure($_POST['id']);
		$name = __secure($_POST['name']);
		$abbrev = __secure($_POST['abbrev']);

		if (empty($name)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert class Name'
			);
		} elseif (empty($abbrev)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert class abbreviation!'
			);
		} else {

			$class_data = [
				"name" => $name,
				"abbrev" => $abbrev,
				"date_updated" => getCurrentDate(),
				"updated_by" => 1,
			];

			if (update_data("classes", $class_data, "WHERE id = '" . $id . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Class updated successfully!',

				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Class registration failed!'
				);
			}
		}
	}

	if ($s == 'delete_class') {
		$id = __secure($_POST['id']);
		if ($db->where('id', $id)->delete('classes')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Class Deleted Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Class delete failed!!'
			);
		}
	}

	// code...
}
