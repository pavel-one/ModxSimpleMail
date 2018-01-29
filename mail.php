<?php
define('MODX_API_MODE', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
$modx = new modX();
$modx->initialize('web');
$method = $_SERVER['REQUEST_METHOD'];
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
  	$modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
}
$pdo = $modx->getService('pdoTools');

//exit(print_r($_POST));


$project_name = $modx->getOption('site_name');
$form_subject = trim($_POST["form_subject"]);
$from = $modx->getOption('emailsender');
$to = $modx->getOption('ms2_email_manager');
$to = str_replace(' ', '', $to);
$to = explode(',',$to);
$name = htmlspecialchars(trim($_POST["Имя"]));
$phone = htmlspecialchars(trim($_POST["Телефон"]));
$props = array(
    'name' => $name,
    'phone' => $phone,
    'createdon' => date('Y-m-d H:i:s'),
    'manager' => 1,
    'status' => 1,
);
if ($_POST['form_subject']) {
	$props['data']['Форма'] = $_POST['form_subject'];
}
if ($_POST['upload_file']) {
	$props['data']['Прикрепленный файл'] = '<a href="'.$_POST['upload_file'].'" target="_blank">ссылка на файл</a>';
}

/***
	TO-DO
-$pdo->getChunk() - передавать массив
-foreach (array_keys($_POST) as $key => $val), потом if (strpos($key, 'address') !== false) - перебирать пост таким образом
***/
if ($name) {
	$msg['Имя'] = $name;
}
if ($phone) {
	$msg['Телефон'] = $phone;
}
foreach ($_POST as $key => $value) {
	if (strpos($key, 'rec_') !== false) {
		$key = str_replace('rec_', '', $key);
		$msg[$key] = htmlspecialchars(trim($value));
		$props['data'][$key] = htmlspecialchars(trim($value));
	}
}
addAdmin($props);

$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->set(modMail::MAIL_FROM, $from);
$modx->mail->set(modMail::MAIL_FROM_NAME, $project_name);
foreach ($to as $item) {
  $modx->mail->address('to', $item);
}
$modx->mail->set(modMail::MAIL_SUBJECT, $form_subject);
if ($_POST['upload_file']) {
	$modx->mail->attach($_SESSION['form_file_path']);
}
$modx->mail->set(modMail::MAIL_BODY, $pdo->getChunk('FormMailSent', array(
	'data' => $msg
)));
$modx->mail->setHTML(true);
if (!$modx->mail->send()) {
    $modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$modx->mail->mailer->ErrorInfo);
}
$modx->mail->reset();

function addAdmin($props) {
	global $modx;
	if (!is_array($props)) {
		$modx->log(1, 'Не получены параметры');
		return ;
	}
	if (!$callBack = $modx->getService('callback', 'callBack', $modx->getOption('callback_core_path', null,
	        $modx->getOption('core_path') . 'components/callback/') . 'model/callback/')) {
		$modx->log(1, 'Не загружена модель CallBack');
		return ;
	}

	$new = $modx->newObject('callBackItem', $props);
	$new->save();

}