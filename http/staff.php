<?php
if ($f == 'staff') {
	if ($s == 'new') {
		$url = '';
		$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
		if (!empty($_FILES['image'])) {
			if (in_array($_FILES['image']['type'], $allowTypes)) {
				$url = share_file('image', 'uploads/staff/', true, 550, 550);
			}
		}


		$insert = array(
			'firstname'	=>	__secure($_POST['firstname']),
			'lastname'	=>	__secure($_POST['lastname']),
			'email'	=>	__secure($_POST['email']),
			'slug' => slug(__secure($_POST['email'])),
			'tel'	=>	__secure($_POST['contact']),
			'position'	=>	__secure($_POST['role']),
			'description'	=>	mysqli_real_escape_string($sqlConnect, $_POST['description']),
			'image'	=>	__secure($url),
			'facebook'	=>	__secure($_POST['facebook']),
			'instagram'	=>	__secure($_POST['instagram']),
			'twitter'	=>	__secure($_POST['twitter']),
		);
		if (save_data('staff', $insert)) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Staff Member added successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Something went wrong'
			);
		}
	}

	if ($s == 'edit') {
		$id = __secure($_POST['id']);
		$staff = $db->where('id', $id)->getOne('staff');

		$url = __secure($_POST['uimage']);
		$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
		if (!empty($_FILES['image'])) {
			if (in_array($_FILES['image']['type'], $allowTypes)) {
				$url = share_file('image', 'uploads/staff/', true, 364, 364);
				unlink(__secure($_POST['uimage']));
			}
		}

		$insert = array(
			'firstname'	=>	__secure($_POST['firstname']),
			'lastname'	=>	__secure($_POST['lastname']),
			'email'	=>	__secure($_POST['email']),
			'tel'	=>	__secure($_POST['contact']),
			'position'	=>	__secure($_POST['role']),
			'description'	=>	mysqli_real_escape_string($sqlConnect, $_POST['description']),
			'image'	=>	__secure($url),
			'facebook'	=>	__secure($_POST['facebook']),
			'instagram'	=>	__secure($_POST['instagram']),
			'twitter'	=>	__secure($_POST['twitter']),
			'instagram'	=>	__secure($_POST['linkedin']),
		);
		if (update_data('staff', $insert, 'WHERE id = "' . $id . '"')) {
			$data = array(
				'status' => 200,
				'message'	=>	'Staff Updated Successfully',
			);
		} else {
			$data = array(
				'status' => 201,
				'message'	=>	'Something went wrong'
			);
		}
	}

	if ($s == 'remove') {
		$id = __secure($_POST['id']);
		if ($db->where('id', $id)->delete('staff')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Staff Member deleted Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Something went wrong'
			);
		}
	}
}
