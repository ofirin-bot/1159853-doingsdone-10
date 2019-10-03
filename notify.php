<?php
require_once 'vendor/autoload.php';
require_once 'init.php';
require_once 'helpers.php';

// параметры доступа к  почтовому серверу)
$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');                                // имя ползователя
$transport->setPassword('htmlacademy');                                    //пароль

$mailer = new Swift_Mailer($transport);

$sql = 'SELECT t.id, t.user_id,  status, title, dt_complet, email, name  FROM tasks t '
	. 'JOIN users u ON t.user_id = u.id '
	. ' WHERE dt_complet = CURDATE() AND status = 0';

$res = mysqli_query($link, $sql);

if ($res && mysqli_num_rows($res)) {
	$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
	$users_all = array_column($tasks, 'user_id');
	$users_id = array_unique($users_all);
}

foreach ($tasks as $task) {
	$tasksUser = '';

	foreach ($users_id as $user) {

		if ($task['user_id'] == $user) {

			$tasksUser .= $task['title'];
		}
	}
}
//Формирование сообщения
$message = new Swift_Message("Уведомление от сервиса «Дела в порядке»"); //тема
$message->setTo([$task['email']], $task['name']);                        //получатель
$message->setBody('Уважаемый, ' . $task['name'] . 'У вас запланирована задача ' . $tasksUser . ' на  ' . $task['dt_complet'], 'text/plain');                                   //текст
$message->setFrom("keks@phpdemo.ru");                                    //отправитель

$result = $mailer->send($message);                                       //передаем сообщение
if ($result) {
	print("Рассылка успешно отправлена");
} else {
	print("Не удалось отправить рассылку");
}
    
  
    
    

     