<?php
if ($file == "attendance") {

    if ($action == "add_student_attendance") {
        $student_id = secure_data($_POST['student_id']);
        $class_id = empty($student_id) ? '' : $db->where("id", $student_id)->getOne("users")->class_id;
        $today = date('Y-m-d H:i:s');

        $db->where('student_id', $student_id);
        $db->where('date', date('Y-m-d'));
        $db->where('check_out', NULL, 'IS');
        $already_checked_in = $db->getOne('library_attendance');



        if (empty($student_id)) {
            $data = array(
                'status'    =>    201,
                'message'    =>    'Please Select Student '
            );
        } elseif (exists("library_attendance", "WHERE student_id = '" . $student_id . "'")) {
            $data = array(
                'status'    =>    201,
                'message'    =>    'Student Already Checked in!'
            );
        } else {

            $attendance_data = [
                'student_id' => $student_id,
                'class_id' => $class_id,
                'check_in' => $today,
                'date' => date('Y-m-d')
            ];

            if (save_data('library_attendance', $attendance_data)) {
                $data = array(
                    'status'    =>    200,
                    'message'    =>    'Student Check-in successful!',
                    'url' => 'index.php?page=students_attendance'
                );
            } else {
                $data = array(
                    'status'    =>    201,
                    'message'    =>    'Class registration failed!'
                );
            }
        }
    }
    if ($action == "student_check_out") {
        $student_id = secure_data($_POST['student_id']);
        $id = secure_data($_POST['id']);
        $today = date('Y-m-d H:i:s');

        $attendance_data = [
            'check_out' => $today,
        ];

        if (update_data("library_attendance", $attendance_data, "WHERE id = '" . $id . "'")) {
            $data = array(
                'status'    =>    200,
                'message'    =>    'Student Check-out successful!',

            );
        } else {
            $data = array(
                'status'    =>    201,
                'message'    =>    'Check Out failed!'
            );
        }
    }

    if ($action == 'delete_class') {
        $id = secure_data($_POST['id']);
        if ($db->where('id', $id)->delete('classes')) {
            $data = array(
                'status'    =>    200,
                'message'    =>    'Class Deleted Successfully'
            );
        } else {
            $data = array(
                'status'    =>    201,
                'message'    =>    'Class delete failed!!'
            );
        }
    }

    // code...
}
