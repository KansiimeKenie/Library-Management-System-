<?php
if ($file == "books") {
	if ($action == "new_book") {
		$title = secure_data($_POST['title']);
		$category_id = secure_data($_POST['category_id']);
		$isbn = secure_data($_POST['isbn']);
		$author_id = secure_data($_POST['author_id']);
		$no_of_copies = secure_data($_POST['no_of_copies']);
		$register_author_error = false;
		$register_author_value = '';
		if (isset($_POST['new_author'])) {
			$author_id = '';
			$author_names = secure_data($_POST['author_names']);
			if (empty($author_names)) {
				$register_author_error = true;
				$register_author_value = 'Please insert Author Names';
			} else if (exists("book_authors", "WHERE names = '" . $author_names . "'")) {
				$author_id = $db->where('names', $author_names)->getOne('book_authors')->id;
			} else {
				//save author to the database
				$author_data = [
					"names" => $author_names,
					"date_created" => getCurrentDate(),
					"created_by" => $global_var['user']['id']
				];
				if (save_data("book_authors", $author_data)) {
					$author_id = $db->where('names', $author_names)->getOne('book_authors')->id;
				} else {
					$register_author_value = 'Author Registration Failed';
				}
			}
		}


		if (empty($title)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book Title'
			);
		} elseif (empty($category_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book Category'
			);
		} elseif ($register_author_error) {
			$data = array(
				'status'	=>	201,
				'message'	=>	$register_author_value
			);
		} elseif (empty($author_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book Aurthor'
			);
		} elseif (empty($isbn)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book ISBN'
			);
		} elseif (empty($no_of_copies)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert No. of Book Copies Currently available'
			);
		} elseif (exists("books", "WHERE isbn = '" . $isbn . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'This Book is Already registered'
			);
		} else {
			//save book 
			$book_data = [
				"category_id" => $category_id,
				"title" => $title,
				"isbn" => $isbn,
				"author_id" => $author_id,
				"date_created" => getCurrentDate(),
				"created_by" => 1,
			];
			if (save_data("books", $book_data)) {
				$book_id = $db->where("isbn", $isbn)->getOne("books")->id;
				//save to current books table 
				$current_book_data = [
					"book_id" => $book_id,
					"no_of_copies" => $no_of_copies,
				];
				if (save_data("current_books", $current_book_data)) {
					$data = array(
						'status'	=>	200,
						'message'	=>	'Book registered successfully!',
						'url' => 'index.php?page=all_books'
					);
				} else {
					$data = array(
						'status'	=>	201,
						'message'	=>	'Book registration failed!'
					);
				}
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Book registration failed!'
				);
			}
		}
	}
	if ($action == 'delete') {
		$id = secure_data($_POST['id']);
		if ($db->where('id', $id)->delete('books') && $db->where('book_id', $id)->delete('current_books')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Book Deleted Successfully',
				'url' => 'index.php?page=all_books'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book delete failed!!'
			);
		}
	}
	if ($action == "edit_book") {
		$book_id = secure_data($_POST['book_id']);
		$title = secure_data($_POST['title']);
		$category_id = secure_data($_POST['category_id']);
		$publication_year = secure_data($_POST['publication_year']);

		$isbn = secure_data($_POST['isbn']);
		$author_id = secure_data($_POST['author_id']);
		$no_of_copies = secure_data($_POST['no_of_copies']);
		$register_author_error = false;
		$register_author_value = '';
		if (isset($_POST['new_author'])) {
			$author_id = '';
			$author_names = secure_data($_POST['author_names']);
			if (empty($author_names)) {
				$register_author_error = true;
				$register_author_value = 'Please insert Author Names';
			} else if (exists("book_authors", "WHERE names = '" . $author_names . "'")) {
				$register_author_error = true;
				$register_author_value = 'Author Already registered please Select Author from the list';
			} else {
				//save author to the database
				$author_data = [
					"names" => $author_names,
					"date_created" => getCurrentDate(),
					"created_by" => $global_var['user']['id']
				];
				if (save_data("book_authors", $author_data)) {
					$author_id = $db->where('names', $author_names)->getOne('book_authors')->id;
				} else {
					$register_author_value = 'Author Registration Failed';
				}
			}
		}


		if (empty($title)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book Title'
			);
		} elseif (empty($category_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please Select Book Category'
			);
		} elseif ($register_author_error) {
			$data = array(
				'status'	=>	201,
				'message'	=>	$register_author_value
			);
		} elseif (empty($author_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book Aurthor'
			);
		} elseif (empty($isbn)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book ISBN'
			);
		} elseif (empty($no_of_copies)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert No. of Book Copies Currently available'
			);
		} elseif (exists("books", "WHERE isbn = '" . $isbn . "' AND id != '" . $book_id . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'This Book is Already registered'
			);
		} else {
			//save book 
			$book_data = [
				"category_id" => $category_id,
				"title" => $title,
				"isbn" => $isbn,
				"author_id" => $author_id,
				"publication_year" => $publication_year,
				"date_updated" => getCurrentDate(),
				"updated_by" => $global_var['user']['id'],
			];
			if (update_data("books", $book_data, "WHERE id = '" . $book_id . "'")) {

				$data = array(
					'status'	=>	200,
					'message'	=>	'Book Edited successfully!',
					'url' => 'index.php?page=all_books'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Book registration failed!'
				);
			}
		}
	}

	//Book Categories 
	if ($action == "new_category") {
		$name = secure_data($_POST['name']);
		$description = secure_data($_POST['description']);
		if (empty($name)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert category Name'
			);
		} elseif (empty($description)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert description'
			);
		} elseif (exists("categories", "WHERE name = '" . $name . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Category ' . $name . ' is Already registered'
			);
		} else {
			//save categories
			$cat_data = [
				"name" => $name,
				"type" => "books",
				"description" => $description,
				"date_created" => getCurrentDate(),
				"created_by" => 1,
			];
			if (save_data("categories", $cat_data)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Category registered successfully!'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Category registration failed!'
				);
			}
		}
	}
	if ($action == "edit_category") {
		$id = secure_data($_POST['id']);
		$name = secure_data($_POST['name']);
		$description = secure_data($_POST['description']);
		if (empty($name)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert category Name'
			);
		} elseif (empty($description)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert description'
			);
		} elseif (exists("categories", "WHERE name = '" . $name . "' AND id != '" . $id . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Category ' . $name . ' is Already registered'
			);
		} else {
			//save categories
			$cat_data = [
				"name" => $name,
				"type" => "books",
				"description" => $description,
				"date_updated" => getCurrentDate(),
				"updated_by" => 1,
			];
			if (update_data("categories", $cat_data, "WHERE id = '" . $id . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Category Updated successfully!'
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Category Update failed!'
				);
			}
		}
	}
	if ($action == 'delete_category') {
		$id = secure_data($_POST['id']);
		if ($db->where('id', $id)->delete('categories')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Category Deleted Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book delete failed!!'
			);
		}
	}
	if ($action == 'filter_by_category') {
		$category_id = empty($_POST['category_id']) ? '' : secure_data($_POST['category_id']);
		$author_id = empty($_POST['author_id']) ? '' : secure_data($_POST['author_id']);
		$url_page = secure_data($_POST['page']);
		if (empty($category_id) && empty($author_id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please select Catory or Author to proceed'
			);
		} else {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Books Filterd, Redirecting .....',
				'url' => 'index.php?page=' . $url_page . '&category_id=' . $category_id . '&author_id=' . $author_id
			);
		}
	}
	if ($action == 'filter_by_dates') {
		$start_date = secure_data($_POST['start_date']);
		$end_date = secure_data($_POST['end_date']);
		$url_page = secure_data($_POST['page']);
		if (empty($start_date)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please select Start Date'
			);
		} elseif (empty($end_date)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please select End Date'
			);
		} else {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Filterd, Redirecting .....',
				'url' => 'index.php?page=' . $url_page . '&start_date=' . $start_date . '&end_date=' . $end_date
			);
		}
	}
	//book copies adding , editing and deleting
	if ($action == "add_new_book_copy") {
		$book_id = secure_data($_POST['book_id']);
		$copy_number = secure_data($_POST['copy_number']);
		$comment = secure_data($_POST['comment']);
		if (empty($copy_number)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book copy Number'
			);
		} elseif (empty($comment)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please comment'
			);
		} elseif (exists("book_copies", "WHERE book_copy_no = '" . $copy_number . "' AND book_id = '" . $book_id . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book Copy ' . $copy_number . ' is Already registered'
			);
		} else {
			//save book copy
			$book_copy_data = [
				"book_copy_no" => $copy_number,
				"book_id" => $book_id,
				"comment" => $comment,
				"status" => "available",
				"date_created" => getCurrentDate(),
				"created_by" => $global_var['user']['id'],
			];
			if (save_data("book_copies", $book_copy_data)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Book Copy registered successfully!',
					'url' => 'index.php?page=book_copies&book_id=' . $book_id
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Category registration failed!'
				);
			}
		}
	}
	if ($action == 'delete_book_copy') {
		$id = secure_data($_POST['id']);
		if ($db->where('id', $id)->delete('book_copies')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Book Copy Deleted Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book Copy delete failed!!'
			);
		}
	}
	if ($action == "edit_book_copy") {
		$book_id = secure_data($_POST['book_id']);
		$copy_number = secure_data($_POST['copy_number']);
		$comment = secure_data($_POST['comment']);
		$book_copy_id = secure_data($_POST['id']);
		if (empty($copy_number)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book copy Number'
			);
		} elseif (empty($comment)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please comment'
			);
		} elseif (exists("book_copies", "WHERE book_copy_no = '" . $copy_number . "' AND book_id = '" . $book_id . "' AND id != '" . $book_copy_id . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book Copy ' . $copy_number . ' is Already registered'
			);
		} else {
			//save book copy
			$book_copy_data = [
				"book_copy_no" => $copy_number,
				"book_id" => $book_id,
				"comment" => $comment,

				"date_updated" => getCurrentDate(),
				"updated_by" => $global_var['user']['id'],
			];
			if (update_data("book_copies", $book_copy_data, "WHERE id = '" . $book_copy_id . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Book Copy updated successfully!',
					'url' => 'index.php?page=book_copies&book_id=' . $book_id
				);
			} else {
				$data = array(
					'status'	=>	201,
					'message'	=>	'Category Update failed!'
				);
			}
		}
	}
}
