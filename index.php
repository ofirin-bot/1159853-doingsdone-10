<?php
require_once('helpers.php');
require_once 'init.php';


if(!$link) {
	$error = mysqli_connect_error();
	$content = include_template('error.php', ['error' => $error]);
}
else {

	$sql = 'SELECT id, user_id, name  FROM categories';

	$result = mysqli_query($link, $sql);

	if($result) {
		$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
	} else {
		$error = mysqli_error($link);
		$content = include_template('error.php', ['error' => $error]);
	}
}

if(isset($_SESSION['user'])) {
    
	$user_id = $_SESSION['user']['id'];

	$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
		. 'JOIN categories c ON t.category_id = c.id '
		. 'WHERE t.user_id =' . $user_id;

	if($res = mysqli_query($link, $sql)) {
		$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
        
		$content = include_template('main.php', ['infoOfTasks' => $tasks, 'taskCount' => $tasks, 'categories' => $categories]);
	} 
    else {
		$content = include_template('error.php', ['error' => mysqli_error($link)]);
	}

	if(isset($_GET['id'])) {

		$sort_field = $_GET['id'];

		$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
			. 'JOIN categories c ON t.category_id = c.id '
			. 'WHERE t.category_id  = ' . $sort_field . ' AND t.user_id =' . $user_id;


		if($result = mysqli_query($link, $sql)) {
			

			$tasksUser = mysqli_fetch_all($result, MYSQLI_ASSOC);

			if(count($tasksUser) === 0) {
				http_response_code(404);
				exit;
			}         
                
                
                $content = include_template('main.php', ['infoOfTasks' => $tasksUser, 'categories' => $categories, 'taskCount' => $tasks]);
                      
        }             
        else {
            $content = include_template('error.php', ['error' => mysqli_error($link)]);
        }
    }                

            
   $tasksUser = [];

    $search = ($_GET['q']) ?? '';             

        if($search) {
            $sql = "SELECT t.id, user_id, category_id, status, title, path, dt_complet, name FROM tasks t "
              . "JOIN users u ON t.user_id = u.id "
              . "WHERE MATCH(title) AGAINST(?)";    
            $stmt = db_get_prepare_stmt($link, $sql, [$search]);

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $tasksUser = mysqli_fetch_all($result, MYSQLI_ASSOC);   

            if (count($tasksUser)) {                     

            $content = include_template('main.php', ['infoOfTasks' => $tasksUser, 'categories' => $categories, 'taskCount' => $tasks]);

            }     

            else{
               $content = "По вашему запросу ничего не найдено";  
            }                  
        }     
 }                    
else {
                     //если не залогинен пользователь
	$content = include_template('guest.php', []);	
}

print(include_template('layout.php', [
	'content' => $content,
	'categories' => $categories,
	'title' => 'Дела в порядке'
]));