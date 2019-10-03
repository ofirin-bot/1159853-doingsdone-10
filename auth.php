<?php
require_once 'helpers.php';
require_once 'init.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$auth_form = $_POST;
	$auth_fields = ['email', 'password'];
	$errors = [];

	foreach ($_POST as $key => $value) {
		if ($key == "email") {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$errors[$key] = 'Email должен быть корректным';
			}
		}
	}

	foreach ($auth_fields as $field) {
		if (empty($auth_form[$field])) {
			$errors[$field] = 'Это поле должно быть заполнено';
		}
	}


	$email = mysqli_real_escape_string($link, $auth_form['email']);
	$sql = "SELECT * FROM users WHERE email = '$email'";
	$res = mysqli_query($link, $sql);

	$user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

	if (!count($errors) && $user) {

		if (password_verify($auth_form['password'], $user['password'])) {

			$_SESSION['user'] = $user;

		} else {
			$errors['password'] = 'Неверный пароль';
		}
	} else {
		$errors['email'] = 'Такой пользователь не найден';
	}

	if (count($errors)) {
		$page_content = include_template('auth.php', ['form' => $auth_form, 'errors' => $errors]);
	} else {
		header("Location: /index.php");
		exit();
	}
} else {
	$page_content = include_template('auth.php', []);

	if (isset($_SESSION['user'])) {
		header("Location: /index.php");
		exit();
	}
}

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => [],
	'title' => 'Дела в порядке'
]);

print($layout_content);


?>