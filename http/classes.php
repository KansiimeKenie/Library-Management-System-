<?php
if ($f == "classes") {

	if ($s == "add_new_class") {
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
