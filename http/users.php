<?php
if ($file == "users") {

	if ($action == "add_new_librarian") {
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
	if ($action == "update_profile") {
		$firstname = __secure($_POST['firstname']);
		$lastname = __secure($_POST['lastname']);
		$othername = __secure($_POST['othername']);
		$email = __secure($_POST['email']);
		$tel = __secure($_POST['tel']);


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
		} else {

			$user_data = [
				"firstname" => $firstname,
				"lastname" => $lastname,
				"othername" => $othername,
				"email" => $email,
				"tel" => $tel,
				"date_updated" => getCurrentDate(),
				"updated_by" => $global_var['user']['id'],
			];

			if (update_data("users", $user_data, "WHERE id = '" . $global_var['user']['id'] . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Profile Updated successfully!',
					'url' => 'index.php?page=user_profile'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'User profile Update failed!'
				);
			}
		}
	}

	if ($action == 'delete_class') {
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
	if ($action == 'update_profile_pic') {
		$image_url = '';
		$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
		$upload_dir = "uploads/profile_pics/";

		// Ensure upload directory exists
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		if (!empty($_FILES['profile_picture'])) {
			if (in_array($_FILES['profile_picture']['type'], $allowTypes)) {
				$image_url = share_file('profile_picture', $upload_dir, true, 1080, 1920);
				unlink($global_var['user']['photo']);
			}
		}
		$user_data = [
			"photo" => $image_url,
		];

		if (update_data("users", $user_data, "WHERE id = '" . $global_var['user']['id'] . "' ")) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Profile Pic Uploaded Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Profile Pic Upload failed!!'
			);
		}
	}

	// code...
}
