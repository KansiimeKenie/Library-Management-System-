<?php
if ($file == "borrow") {
	if ($action == "add_new_borrow_request") {
		$book_id = secure_data($_POST['book_id']);
		$reason = secure_data($_POST['reason']);
		$request_date = getCurrentDateOnly();
		$return_date = secure_data($_POST['return_date']);
		$student_id = $global_var['user']['id'];
		$no_of_copies = secure_data($_POST['no_of_copies']);
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
		$request_id = secure_data($_POST['id']);
		$book_id = secure_data($_POST['book_id']);
		$reason = secure_data($_POST['reason']);
		$return_date = secure_data($_POST['return_date']);
		$student_id = $global_var['user']['id'];
		$no_of_copies = secure_data($_POST['no_of_copies']);
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
		$request_id = secure_data($_POST['id']);
		$student_id = secure_data($_POST['student_id']);
		$book_id = secure_data($_POST['book_id']);
		$book_copy_id = secure_data($_POST['book_copy_id']);
		$assign_comment = secure_data($_POST['assign_comment']);
		$new_data = [
			"aprove_status" => 1,
			"date_approved" => getCurrentDate()
		];
		$borrowed_copies = [
			"requesition_id" => $request_id,
			"student_id" => $student_id,
			"book_copy_id" => $book_copy_id,
			"assign_comment" => $assign_comment,
			"date_created" => getCurrentDate(),
		];
		$book_copies_data = [
			"status" => "borrowed",
		];

		if ((update_data("borrow_requests", $new_data, "WHERE id = '" . $request_id . "'"))
			&& (save_data("borrowed_copies", $borrowed_copies))
			&& (update_data("book_copies", $book_copies_data, "WHERE id = '" . $book_copy_id . "'"))
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
		$req_id = secure_data($_POST['id']);
		$rejection_reason = secure_data($_POST['rejection_reason']);
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
		$req_id = secure_data($_POST['id']);
		$book_id = secure_data($_POST['book_id']);
		$student_id = secure_data($_POST['student_id']);
		$return_date = secure_data($_POST['return_date']);
		$comment = secure_data($_POST['comment']);
		$damage_status = isset($_POST['damaged']) ? secure_data($_POST['damaged']) : null;

		$new_data = [
			"return_status" => 1,
			"returned_on" => $return_date,
			"comment" => $comment
		];
		$current_books_data = [
			"status" => "available",
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
			&& (update_data("book_copies", $current_books_data, "WHERE book_id = '" . $book_id . "'"))
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
	if ($action == "mark_as_lost") {
		$req_id = secure_data($_POST['id']);
		$book_id = secure_data($_POST['book_id']);
		$student_id = secure_data($_POST['student_id']);
		$no_of_copies = secure_data($_POST['return_copies']);

		$comment = secure_data($_POST['comment']);

		$lost_book_data = [
			"requistion_id" => $req_id,
			"book_id" => $book_id,
			"student_id" => $student_id,
			"no_of_copies" => $no_of_copies,
			"comment" => $comment,
			"date_created" => getCurrentDate(),
			"created_by" => $global_var['user']['id']
		];
		$new_data = [
			"return_status" => 3,
		];

		if (empty($comment)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please add a comment!'
			);
		} else
	    if ((save_data("lost_books", $lost_book_data)) && update_data("borrow_requests", $new_data, "WHERE id = '" . $req_id . "'")) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Lost Book recorded successfully!',
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
		$id = secure_data($_POST['id']);
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
