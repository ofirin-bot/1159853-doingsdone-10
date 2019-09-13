<?php
require_once 'helpers.php';
require_once 'init.php';

$sql = 'SELECT id, name  FROM users 
    WHERE user_id = 1';
$result = mysqli_query($link, $sql);

if ($result) {
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$sql = 'SELECT name FROM users WHERE id = 1';

if ($ress = mysqli_query($link, $sql)) {
	$user = mysqli_fetch_assoc($ress);
} else {
	$content = include_template('error.php', ['error' => mysqli_error($link)]);
}

$rg_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$reg_form = $_POST;
	$reg_fields = ['email', 'password', 'name'];
	$errors = [];
    
   
    foreach ($reg_fields as $fil) {
		if (empty($_POST[$fil])) {
			$errors[$fil] = 'Не заполнено поле ' . $fil;
		}
	}     

     foreach ($_POST as $key => $value) {
    if ($key == "email") {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $errors[$key] = 'Email должен быть корректным';  
        }        
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
			$errors[] = 'Пользователь с этим email уже зарегистрирован';
		} 
        else {

			$password = password_hash($reg_form['password'], PASSWORD_DEFAULT);

			$sql = 'INSERT INTO users (dt_add, email, name, password) VALUES (NOW(), ?, ?, ?)';
			$email = $reg_form['email'];
            
			$name = $reg_form['name'];
            

			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_bind_param($stmt, 'sss', $email, $name, $password);

			$res = mysqli_stmt_execute($stmt);
			
		}

		if ($res && empty($errors)) {           

			header("location: /");  //переадресовка на страницу входа
			
		}
	}

	$rg_data['errors'] = $errors;
	$rg_data['values'] = $reg_form;

}
    $page_content = include_template('register.php', $rg_data);


$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => [],
	'title' => 'Дела в порядке | Регистрация',
	'user' => $user
]);

print($layout_content);                   
          
  
 