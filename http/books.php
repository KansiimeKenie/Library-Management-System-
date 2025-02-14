<?php
if ($f == "books") {
	if ($s == "new_book") {
		$title = __secure($_POST['title']);
		$category_id = __secure($_POST['category_id']);
		$isbn = __secure($_POST['isbn']);
		$aurthor = __secure($_POST['aurthor']);
		$no_of_copies = __secure($_POST['no_of_copies']);
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
		} elseif (empty($aurthor)) {
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
				"author" => $aurthor,
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
	if ($s == 'delete') {
		$id = __secure($_POST['id']);
		if ($db->where('id', $id)->delete('books') && $db->where('book_id', $id)->delete('current_books')) {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Book Deleted Successfully'
			);
		} else {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Book delete failed!!'
			);
		}
	}
	if ($s == "edit_book") {
		$book_id = __secure($_POST['book_id']);
		$title = __secure($_POST['title']);
		$category_id = __secure($_POST['category_id']);
		$isbn = __secure($_POST['isbn']);
		$aurthor = __secure($_POST['aurthor']);

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
		} elseif (empty($aurthor)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book Aurthor'
			);
		} elseif (empty($isbn)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please insert Book ISBN'
			);
		} elseif (exists("books", "WHERE isbn = '" . $isbn . "' AND id != '" . $book_id . "'")) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'This Book is Already registered'
			);
		} else {
			//edit book 
			$book_data = [
				"category_id" => $category_id,
				"title" => $title,
				"isbn" => $isbn,
				"author" => $aurthor,
				"date_updated" => getCurrentDate(),
				"updated_by" => 1,
			];
			if (update_data("books", $book_data, "WHERE id = '" . $book_id . "'")) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Book registered successfully!',

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
	if ($s == "new_category") {
		$name = __secure($_POST['name']);
		$description = __secure($_POST['description']);
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
	if ($s == "edit_category") {
		$id = __secure($_POST['id']);
		$name = __secure($_POST['name']);
		$description = __secure($_POST['description']);
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
	if ($s == 'delete_category') {
		$id = __secure($_POST['id']);
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
	if ($s == 'filter_by_category') {
		$id = __secure($_POST['id']);
		if (empty($id)) {
			$data = array(
				'status'	=>	201,
				'message'	=>	'Please select Category'
			);
		} else {
			$data = array(
				'status'	=>	200,
				'message'	=>	'Books Filterd, Redirecting .....',
				'url' => 'index.php?page=all_books&id=' . $id
			);
		}
	}
}
