<?php
if ($file == 'web-setup') {
	$data = array();
	$targetDir = "uploads/system/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	if ($action == 'update') {
		$business_settings = array();


		foreach ($_POST['types'] as $key => $type) {

			if (gettype($_POST[$type]) == 'array') {
				$d = array();
				foreach ($_POST[$type] as $key => $value) {
					array_push($d, secure_data($value));
				}
				$business_settings['value'] =  json_encode($d);
			} else {
				$business_settings['value'] = secure_data($_POST[$type]);
			}

			$db->where('name', $type)->update('config', $business_settings);
		}

		$data = array(
			'status'	=>	200,
			'message'	=>	'Settings updated successfully !!',
		);
	}
}
