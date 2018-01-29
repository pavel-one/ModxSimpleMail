<?php
define('MODX_API_MODE', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
$modx = new modX();
$modx->initialize('web');
$method = $_SERVER['REQUEST_METHOD'];
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
  	$modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
}
$out = array(
	'success' => false,
	'message' => 'Что-то пошло не так'
);
//$pdo = $modx->getService('pdoTools');
//print_r($_FILES);
$dir = MODX_ASSETS_PATH.'frontFile/';
$user_id = $_REQUEST['PHPSESSID'];
$out = array(
	'success' => false,
	'message' => 'Что-то пошло не так'
);
//echo $dir;

foreach ($_FILES as $item) {
	$type = explode('.', $item['name']);
	$type = array_pop($type);
	if ($item['size'] > 1024*10*1024) {
		$out['message'] = 'Размер файла превышает 10мб';
		exit(json_encode($out));
	}
	if ($item['type'] == 'application/x-php') {
		$out['message'] = 'За такое админ пизды даст!';
		exit(json_encode($out));
	}
   // Проверяем загружен ли файл
   if(is_uploaded_file($item['tmp_name'])) {
		if (move_uploaded_file($item['tmp_name'], $dir.$user_id.'.'.$type)) {
			$out = array(
				'success' => true,
				'message' => 'Файл успешно загружен',
				'file' => MODX_ASSETS_URL.'frontFile/'.$user_id.'.'.$type,
			);
			$_SESSION['form_file_path'] = $dir.$user_id.'.'.$type;
			exit(json_encode($out));
		};
   } else {
		$out['message'] = 'Ошибка загрузки файла';
		exit(json_encode($out));
   }
}

exit(json_encode($out));