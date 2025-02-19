<?php
if ($file == "classes") {

	if ($action == "add_new_class") {
		$name = secure_data($_POST['name']);
		$abbrev = secure_data($_POST['abbrev']);

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
				"date_created" => getCurrentDate(),
				"created_by" => 1,
			];

			if (save_data("classes", $class_data)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Class registered successfully!',
					'url' => 'index.php?page=all_classes'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Class registration failed!'
				);
			}
		}
	}
	if ($action == "edit_class") {
		$id = secure_data($_POST['id']);
		$name = secure_data($_POST['name']);
		$abbrev = secure_data($_POST['abbrev']);

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

	if ($action == 'delete_class') {
		$id = secure_data($_POST['id']);
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
