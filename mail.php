<?php
define('MODX_API_MODE', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
$modx = new modX();
$modx->initialize('web');
$method = $_SERVER['REQUEST_METHOD'];
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
  	$modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
}

$c = true;
$project_name = $modx->getOption('site_name');
$form_subject = trim($_POST["form_subject"]);

$props = array(
    'name' => trim($_POST["name"],
    'phone' => trim($_POST["phone"],
    'createdon' => date('Y-m-d H:i:s'),
    'manager' => 1,
    'status' => 1,
);
foreach ( $_POST as $key => $value ) {
	if ( $value != "" && $key != "form_subject" && $key != "null") {
		$message .= "
		" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
			<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
			<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
		</tr>
		";

		if ($key != 'phone' && $key != 'name') {
			if ($key == 'form_subject') {
				$props['data']['Форма'] = $value;
			} else {
				$props['data'][$key] = $value;
			}
		}
	}
}

$message = "<table style='width: 100%;'>$message</table>";
addAdmin($props);

$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->set(modMail::MAIL_FROM, $modx->getOption('emailsender'));
$modx->mail->set(modMail::MAIL_FROM_NAME, $project_name);
$adresss = $modx->getOption('ms2_email_manager');
$adresss = str_replace(' ', '', $adresss);
$adresss = explode(',',$adresss);
foreach ($adresss as $item) {
  $modx->mail->address('to', $item);
}
$modx->mail->set(modMail::MAIL_SUBJECT, $form_subject);
$modx->mail->set(modMail::MAIL_BODY, $message);
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