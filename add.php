<?php

require_once 'helpers.php';
require_once 'init.php';

$id = $_SESSION['user']['id'];

$sql = 'SELECT id, user_id, name  FROM categories WHERE user_id = ' . $id;

$result = mysqli_query($link, $sql);

$cats_ids = [];

if ($result) {
	$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
	//получили простой массив  id из списка категорий
	$cats_ids = array_column($categories, 'id');
} else {
	$content = include_template('error.php', ['error' => mysqli_error($link)]);
}

$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
	. 'JOIN categories c ON t.category_id = c.id '
	. 'WHERE t.user_id =' . $id;

$result = mysqli_query($link, $sql);

if ($result) {
	$count_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
	$content = include_template('error.php', ['error' => mysqli_error($link)]);
}

//проверяем что форма была отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$task = $_POST;
	//определим список обязательных полей
	$required = ['title', 'category_id'];
	//определим массив, в кот. будем записывать ошибки
	$errors = [];
	//функции, кот применяются к полям
	$rules = [
		'title' => function () {
			return validateLength('title', 5, 80);
            
		},
		'category_id' => function () use ($cats_ids) {
			return validateCategory('category_id', $cats_ids);
		},
		'dt_complet' => function () {
            
			return check_date('dt_complet');
		}
	];
//применяем ф-ии к полям
	foreach ($_POST as $key => $value) {
		if (isset($rules[$key])) {
			$rule = $rules[$key];
			$errors[$key] = $rule();
		}
	}

	$errors = array_filter($errors);
    
//проверяем все ли обязательные поля заполнены
	foreach ($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле надо заполнить';
		}
	}

	if (isset($_FILES['file']['name'])) {
		//получаем путь где сохранен файл
		$tmp_name = $_FILES['file']['tmp_name'];

		$arr = explode('.', $_FILES['file']['name']);

		$expansion = end($arr);

		$path = $_FILES['file']['name'];

		$filename = uniqid() . ".$expansion";
		//переносим файл с новым именем в папку загрузок
		move_uploaded_file($tmp_name, 'uploads/' . $filename);
		$task['path'] = $filename;

	}    //проверям есть ли в массиве ошибки
	if (count($errors)) {
		//при наличии ошибок подключить снова шаблон и передать данные
		$page_content = include_template('add.php', ['task' => $task, 'errors' => $errors, 'categories' => $categories, 'count_tasks' => $count_tasks]);
	} else {
		//если не было ошибок
		$sql = 'INSERT INTO tasks (category_id, user_id, dt_add, status, title,  path, dt_complet) VALUES (?, ?, NOW(), 0, ?, ?, ?)';

		$category_id = $_POST['category_id'];
		$user_id = $_SESSION['user']['id'];
		$title = $_POST['title'];
		$dt_complet = $_POST['dt_complet'];

		$stmt = mysqli_prepare($link, $sql);
        //в стр.41 происходит валидация всех необходимых полей
        $full_path = 'uploads/' . $filename;
		mysqli_stmt_bind_param($stmt, 'iisss', $category_id, $user_id, $title, $full_path, $dt_complet);

		$res = mysqli_stmt_execute($stmt);

		if ($res) {

			$file_url = 'uploads/' . $filename;

			header("Location: /");
		}
	}
} else {
	$page_content = include_template('add.php', ['categories' => $categories, 'count_tasks' => $count_tasks]);
}

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => [],
	'categories' => [],
	'title' => 'Дела в порядке - Добавление задачи'

]);

print($layout_content);
     
    
        
        
        
        
        
  
