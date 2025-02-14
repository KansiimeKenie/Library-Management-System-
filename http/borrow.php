<?php
if ($f == "borrow") {
	if ($s == "add_new_borrow_request") {
		$book_id = __secure($_POST['book_id']);
		$reason = __secure($_POST['reason']);
		$return_date = __secure($_POST['return_date']);
		$student_id = $the_ken['user']['id'];
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
		} elseif (exists("borrow_requests", "WHERE book_id = '" . $book_id . "' AND student_id = '" . $student_id . "'")) {
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

	if ($s == "approve_borrow_request") {
		$req_id = __secure($_POST['id']);
		$book_id = __secure($_POST['book_id']);
		$no_of_copies = __secure($_POST['no_of_copies']);
		$current_book_details = $db->where('book_id', $book_id)->getOne('current_books');
		$new_copies = $current_book_details->no_of_copies - $no_of_copies;
		$new_data = [
			"aprove_status" => 1,
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
	if ($s == "confirm_return") {
		$req_id = __secure($_POST['id']);
		$book_id = __secure($_POST['book_id']);
		$no_of_copies = __secure($_POST['return_copies']);
		$return_date = __secure($_POST['return_date']);
		$comment = __secure($_POST['comment']);
		$current_book_details = $db->where('book_id', $book_id)->getOne('current_books');
		// var_dump($_POST);
		// echo $current_book_details->no_of_copies;
		// return;
		$new_copies = $current_book_details->no_of_copies + $no_of_copies;
		$new_data = [
			"return_status" => 1,
			"returned_on" => $return_date
		];
		$current_books_data = [
			"no_of_copies" => $new_copies,
		];

		if ((update_data("borrow_requests", $new_data, "WHERE id = '" . $req_id . "'"))
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
}
