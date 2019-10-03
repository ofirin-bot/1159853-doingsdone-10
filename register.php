<?php
require_once 'helpers.php';
require_once 'init.php';

$rg_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$reg_form = $_POST;
	$reg_fields = ['email', 'password', 'name'];
	$errors = [];

	foreach ($_POST as $key => $value) {
		if ($key == "email") {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$errors[$key] = 'Email должен быть корректным';
			}
		}
	}
	foreach ($reg_fields as $fil) {
		if (empty($_POST[$fil])) {
			$errors[$fil] = 'Не заполнено поле ' . $fil;
		}
	}

	$errors = array_filter($errors);

	if (count($errors)) {

		$page_content = include_template('register.php', ['errors' => $errors]);
	}

	if (empty($errors)) {
		$email = mysqli_real_escape_string($link, $reg_form['email']);
		$sql = "SELECT id FROM users WHERE email = '$email'";
		$res = mysqli_query($link, $sql);

		if (mysqli_num_rows($res) > 0) {
			$errors['email'] = 'Пользователь с этим email уже зарегистрирован';
		} else {

			$password = password_hash($reg_form['password'], PASSWORD_DEFAULT);

			$sql = 'INSERT INTO users (dt_add, email, name, password) VALUES (NOW(), ?, ?, ?)';
			$email = $reg_form['email'];

			$name = $reg_form['name'];


			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_bind_param($stmt, 'sss', $email, $name, $password);

			$res = mysqli_stmt_execute($stmt);

		}

		if ($res && empty($errors)) {

			header("location: /auth.php");
			exit();
		}
	}

	$rg_data['errors'] = $errors;
	$rg_data['values'] = $reg_form;

}
$page_content = include_template('register.php', $rg_data);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => [],
	'title' => 'Дела в порядке | Регистрация'
]);

print($layout_content);                   

  
 