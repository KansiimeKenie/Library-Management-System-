<?php
if ($f == "damages") {

	if ($s == "add_new_damage") {
		$book_id = __secure($_POST['book_id']);
		$description = __secure($_POST['description']);
		$no_of_copies = __secure($_POST['no_of_copies']);
		if (empty($book_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book'
			);
		} elseif (empty($no_of_copies) || !is_numeric($no_of_copies) || $no_of_copies <= 0) {
			$data = array(
				'status'    => 201,
				'message'   => 'Please insert a valid number of book copies damaged (must be a positive number)'
			);
		} else {
			//update inventory
			$updated_no_copies = $db->where("id", $book_id)->getOne("current_books")->no_of_copies - $no_of_copies;
			$book_data = [
				"book_id" => $book_id,
				"no_of_copies" => $no_of_copies,
				"description" => $description,
				"date_created" => getCurrentDate(),
				"created_by" => 1,
			];
			$current_book_data = [
				"book_id" => $book_id,
				"no_of_copies" => $updated_no_copies,
			];
			if ((save_data("damaged_books", $book_data)) && (update_data("current_books", $current_book_data, "WHERE book_id = '" . $book_id . "'"))) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Damaged Books registered successfully!',
					'url' => 'index.php?page=damaged_books'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Book registration failed!'
				);
			}
		}
	}
	if ($s == "edit_damaged_book") {
		$id = __secure($_POST['id']);
		$initial_copies = __secure($_POST['initial_copies']);
		$book_id = __secure($_POST['book_id']);
		$description = __secure($_POST['description']);
		$no_of_copies = __secure($_POST['no_of_copies']);
		if (empty($no_of_copies)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert No. of Book Copies Damaged'
			);
		} elseif (empty($no_of_copies) || !is_numeric($no_of_copies) || $no_of_copies <= 0) {
			$data = array(
				'status'    => 201,
				'message'   => 'Please insert a valid number of book copies damaged (must be a positive number)'
			);
		} else {
			//update inventory
			$existing_inventory = $db->where("id", $book_id)->getOne("current_books");
			$existing_inventory_copies = (empty($existing_inventory) ? 0 : $existing_inventory->no_of_copies);
			$updated_no_copies =  ($existing_inventory_copies + $initial_copies) - $no_of_copies;
			$book_data = [
				"no_of_copies" => $no_of_copies,
				"description" => $description,
				"date_updated" => getCurrentDate(),
				"updated_by" => 1,
			];
			$current_book_data = [
				"book_id" => $book_id,
				"no_of_copies" => $updated_no_copies,
			];
			if ((update_data("damaged_books", $book_data, "WHERE id = '" . $id . "'")) && (update_data("current_books", $current_book_data, "WHERE book_id = '" . $book_id . "'"))) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Book Damage Updated successfully!',
					'url' => 'index.php?page=inventory_update'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Book registration failed!'
				);
			}
		}
	}
	if ($s == 'delete_damage') {
		$id = __secure($_POST['id']);
		$book_id = __secure($_POST['book_id']);
		$damaged_copies = __secure($_POST['no_of_copies']);
		$current_books_details = $db->where("book_id", $book_id)->getOne("current_books");
		$updated_no_copies = (empty($current_books_details) ? 0 : $current_books_details->no_of_copies) + $damaged_copies;
		$current_book_data = [
			"book_id" => $book_id,
			"no_of_copies" => $updated_no_copies,
		];
		if (($db->where('id', $id)->delete('damaged_books')) && (update_data("current_books", $current_book_data, "WHERE book_id = '" . $book_id . "'"))) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Damaged Book Deleted Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book delete failed!!'
			);
		}
	}

	// code...
}
