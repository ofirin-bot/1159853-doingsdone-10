<?php
require_once 'helpers.php';
require_once 'init.php';

$user_id = $_SESSION['user']['id'];

$sql = 'SELECT id, user_id, name  FROM categories
    WHERE  user_id = ' . $user_id;
$result = mysqli_query($link, $sql);

if ($result) {
	$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($link);
	$content = include_template('error.php', ['error' => $error]);
}

$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
	. 'JOIN categories c ON t.category_id = c.id '
	. 'WHERE t.user_id =' . $user_id;

$result = mysqli_query($link, $sql);

if ($result) {
	$count_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
	$content = include_template('error.php', ['error' => mysqli_error($link)]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$proj = $_POST;
	$errors = [];

	if (empty($proj['name'])) {
		$errors['name'] = 'Это поле должно быть заполнено';
	}

	if (count($errors)) {

		$page_content = include_template('addprj.php', ['errors' => $errors, 'categories' => $categories]);
	}

	if (empty($errors)) {
		$name = mysqli_real_escape_string($link, $proj['name']);
		$sql = "SELECT id FROM categories WHERE name = 'name' 
        AND user_id =" . $user_id;
		$res = mysqli_query($link, $sql);

		if (mysqli_num_rows($res) > 0) {
			$errors[] = 'Такая категория уже существует ';
		} else {
			$sql = 'INSERT INTO categories (user_id, name) VALUES (?, ?)';
			$name = $_POST['name'];
			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_bind_param($stmt, 'is', $user_id, $name);
			$res = mysqli_stmt_execute($stmt);

			if ($res) {
				header("Location: /");
				exit();
			}
		}
	}
} else {
	$page_content = include_template('addprj.php', ['categories' => $categories, 'count_tasks' => $count_tasks]);
}

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => [],
	'title' => 'Дела в порядке - Добавление проекта'
]);

print($layout_content);
     
    
        
        
        
        
        
  
