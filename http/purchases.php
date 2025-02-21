<?php
if ($file == "purchases") {
	// INVENTORY PURCHASES / INVENTORY UPDATING 
	if ($action == "update_inventory") {
		$book_id = secure_data($_POST['book_id']);
		$no_of_copies = secure_data($_POST['no_of_copies']);
		if (empty($book_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book'
			);
		} elseif (empty($no_of_copies)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert No. of Book Copies Acquired / Purchased'
			);
		} else {
			//update inventory
			$book_data = [
				"book_id" => $book_id,
				"no_of_copies" => $no_of_copies,
				"date_created" => getCurrentDate(),
				"created_by" => $global_var['user']['id'],
			];

			if ((save_data("purchases", $book_data))) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Book Inventory Updated successfully!',
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
	if ($action == "edit_book_purchase") {
		$purchase_id = secure_data($_POST['id']);
		$initial_copies = secure_data($_POST['initial_copies']);
		$book_id = secure_data($_POST['book_id']);
		$no_of_copies = secure_data($_POST['no_of_copies']);
		if (empty($book_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book'
			);
		} elseif (empty($no_of_copies)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert No. of Book Copies Acquired / Purchased'
			);
		} else {
			//update inventory
			$existing_inventory = $db->where("id", $book_id)->getOne("current_books");
			$existing_inventory_copies = (empty($existing_inventory) ? 0 : $existing_inventory->no_of_copies);
			$updated_no_copies =  ($existing_inventory_copies - $initial_copies) + $no_of_copies;
			$book_data = [
				"no_of_copies" => $no_of_copies,
				"date_updated" => getCurrentDate(),
				"updated_by" => 1,
			];
			$current_book_data = [
				"book_id" => $book_id,
				"no_of_copies" => $updated_no_copies,
			];
			if ((update_data("purchases", $book_data, "WHERE id = '" . $purchase_id . "'")) && (update_data("current_books", $current_book_data, "WHERE book_id = '" . $book_id . "'"))) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Book Inventory Updated successfully!',
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
	if ($action == 'delete_purchase') {
		$purchase_id = secure_data($_POST['id']);
		$book_id = secure_data($_POST['book_id']);
		$purchased_copies = secure_data($_POST['no_of_copies']);
		$current_books_details = $db->where("book_id", $book_id)->getOne("current_books");
		$updated_no_copies = (empty($current_books_details) ? 0 : $current_books_details->no_of_copies) - $purchased_copies;
		$current_book_data = [
			"book_id" => $book_id,
			"no_of_copies" => $updated_no_copies,
		];
		if (($db->where('id', $purchase_id)->delete('purchases')) && (update_data("current_books", $current_book_data, "WHERE book_id = '" . $book_id . "'"))) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Purchase Deleted Successfully'
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
