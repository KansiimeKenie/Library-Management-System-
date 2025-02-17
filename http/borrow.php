<?php
if ($file == "borrow") {
	if ($action == "add_new_borrow_request") {
		$book_id = __secure($_POST['book_id']);
		$reason = __secure($_POST['reason']);
		$request_date = getCurrentDateOnly();
		$return_date = __secure($_POST['return_date']);
		$student_id = $global_var['user']['id'];
		$no_of_copies = __secure($_POST['no_of_copies']);
		$base_limit = 1;
		if (empty($book_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book'
			);
		} elseif (empty($reason)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert reason'
			);
		} elseif (empty($no_of_copies) || !is_numeric($no_of_copies) || $no_of_copies <= 0) {
			$data = array(
				'status'    => 201,
				'message'   => 'Please insert a valid number of book copies (must be a positive number)'
			);
		} elseif ($no_of_copies > $base_limit) {
			$data = array(
				'status'    => 201,
				'message'   => 'You are only allowed to borrow One Book Copy!'
			);
		} elseif (exists("borrow_requests", "WHERE book_id = '" . $book_id . "' AND student_id = '" . $student_id . "' AND return_status != 1")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'This Book is Already Requested'
			);
		} else {
			//save book request
			$borrow_data = [
				"book_id" => $book_id,
				"reason" => $reason,
				"no_of_copies" => $no_of_copies,
				"return_date" => $return_date,
				"request_date" => $request_date,
				"student_id" => $student_id,
			];
			if (save_data("borrow_requests", $borrow_data)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Borrow Requistion registered successfully!'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Borrow Requistion registration failed!'
				);
			}
		}
	}
	if ($action == "edit_request") {
		$request_id = __secure($_POST['id']);
		$book_id = __secure($_POST['book_id']);
		$reason = __secure($_POST['reason']);
		$return_date = __secure($_POST['return_date']);
		$student_id = $global_var['user']['id'];
		$no_of_copies = __secure($_POST['no_of_copies']);
		$base_limit = 1;
		if (empty($book_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book'
			);
		} elseif (empty($reason)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert reason'
			);
		} elseif (empty($no_of_copies) || !is_numeric($no_of_copies) || $no_of_copies <= 0) {
			$data = array(
				'status'    => 201,
				'message'   => 'Please insert a valid number of book copies (must be a positive number)'
			);
		} elseif ($no_of_copies > $base_limit) {
			$data = array(
				'status'    => 201,
				'message'   => 'You are only allowed to borrow One Book Copy!'
			);
		} else {
			//save book request
			$borrow_data = [
				"book_id" => $book_id,
				"reason" => $reason,
				"no_of_copies" => $no_of_copies,
				"return_date" => $return_date,
				"student_id" => $student_id,
			];
			if (update_data("borrow_requests", $borrow_data, "WHERE id = '" . $request_id . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Borrow Requistion Updated successfully!'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Borrow Requistion update failed!'
				);
			}
		}
	}

	if ($action == "approve_borrow_request") {
		$req_id = __secure($_POST['id']);
		$book_id = __secure($_POST['book_id']);
		$no_of_copies = __secure($_POST['no_of_copies']);
		$current_book_details = $db->where('book_id', $book_id)->getOne('current_books');
		$new_copies = $current_book_details->no_of_copies - $no_of_copies;
		$new_data = [
			"aprove_status" => 1,
			"date_approved" => getCurrentDate()
		];
		$current_books_data = [
			"no_of_copies" => $new_copies,
		];

		if ((update_data("borrow_requests", $new_data, "WHERE id = '" . $req_id . "'"))
			&& (update_data("current_books", $current_books_data, "WHERE book_id = '" . $book_id . "'"))
		) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Borrow Request Approved successfully!',
				'url' => 'index.php?page=borrowed_books'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Borrow Request Approve failed!'
			);
		}
	}
	if ($action == "reject_borrow_request") {
		$req_id = __secure($_POST['id']);
		$rejection_reason = __secure($_POST['rejection_reason']);
		$new_data = [
			"aprove_status" => 2,
			"rejection_reason" => $rejection_reason,
			"date_approved" => getCurrentDate()
		];
		if (empty($rejection_reason)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert reason for rejecting!'
			);
		} elseif (update_data("borrow_requests", $new_data, "WHERE id = '" . $req_id . "'")) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Borrow Request Rejected !',
				'url' => 'index.php?page=requistions'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Borrow Request Approve failed!'
			);
		}
	}
	if ($action == "confirm_return") {
		$req_id = __secure($_POST['id']);
		$book_id = __secure($_POST['book_id']);
		$student_id = __secure($_POST['student_id']);
		$no_of_copies = __secure($_POST['return_copies']);
		$return_date = __secure($_POST['return_date']);
		$comment = __secure($_POST['comment']);
		$damage_status = isset($_POST['damaged']) ? __secure($_POST['damaged']) : null;
		$current_book_details = $db->where('book_id', $book_id)->getOne('current_books');
		// var_dump($_POST);
		// echo $current_book_details->no_of_copies;
		// return;
		$new_copies = $current_book_details->no_of_copies + $no_of_copies;
		$new_data = [
			"return_status" => 1,
			"returned_on" => $return_date,
			"comment" => $comment
		];
		$current_books_data = [
			"no_of_copies" => $new_copies,
		];
		if (empty($return_date)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Insert return date!'
			);
		} else
		if (!empty($damage_status)) {
			$damage_data = [
				"book_id" => $book_id,
				"student_id" => $student_id,
				"no_of_copies" => $no_of_copies,
				"description" => $comment,
				"date_created" => getCurrentDate(),
				"created_by" => $global_var['user']['id'],
			];
			if ((update_data("borrow_requests", $new_data, "WHERE id = '" . $req_id . "'")) && (save_data("damaged_books", $damage_data))) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Return Confirmed successfully!',
					'url' => 'index.php?page=borrowed_books'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Borrow Request Approve failed!'
				);
			}
		} else if ((update_data("borrow_requests", $new_data, "WHERE id = '" . $req_id . "'"))
			&& (update_data("current_books", $current_books_data, "WHERE book_id = '" . $book_id . "'"))
		) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Return Confirmed successfully!',
				'url' => 'index.php?page=borrowed_books'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Borrow Request Approve failed!'
			);
		}
	}
	if ($action == 'delete_borrow_request') {
		$id = __secure($_POST['id']);
		if ($db->where('id', $id)->delete('borrow_requests')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Request Deleted Successfully',
				'url' => 'index.php?page=requistions'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Request delete failed!!'
			);
		}
	}
}
