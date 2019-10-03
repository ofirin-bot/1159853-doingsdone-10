<?php
require_once('helpers.php');
require_once 'init.php';

if (!$link) {
	$error = mysqli_connect_error();
	$content = include_template('error.php', ['error' => $error]);
} else

	if (isset($_SESSION['user'])) {

		$user_id = $_SESSION['user']['id'];
		//Запрос на получение списка категорий для текущего польователя
		$sql = 'SELECT id, user_id, name  FROM categories
    WHERE user_id = ' . $user_id;
		// Выполняем запрос и получаем результат
		$result = mysqli_query($link, $sql);

		if ($result) {
			//Получаем все категории
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
			$countTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
		} else {
			$content = include_template('error.php', ['error' => mysqli_error($link)]);
		}

		if (!isset($_GET['cat_id']) && (!isset($_GET['tab']))) {
			$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. 'WHERE t.user_id =' . $user_id;

			$result = mysqli_query($link, $sql);

			if ($result) {
				$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

				$content = include_template('main.php', ['infoOfTasks' => $tasks, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}

		if (isset($_GET['cat_id']) && (!isset($_GET['tab']))) {
			$category_id = $_GET['cat_id'];
			$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. 'WHERE category_id =' . $category_id . ' AND t.user_id =' . $user_id;


			if ($res = mysqli_query($link, $sql)) {
				$tasksCat = mysqli_fetch_all($res, MYSQLI_ASSOC);
				$content = include_template('main.php', ['infoOfTasks' => $tasksCat, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}

		if (isset($_GET['cat_id'])) {
			$cat_id = $_GET['cat_id'];
		}

		if (isset($_GET['tab']) && $_GET['tab'] == 'all') {

			$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. 'WHERE t.category_id =' . $cat_id . ' AND t.user_id =' . $user_id;


			if ($res = mysqli_query($link, $sql)) {
				$tasksAll = mysqli_fetch_all($res, MYSQLI_ASSOC);
				$content = include_template('main.php', ['infoOfTasks' => $tasksAll, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}

		if (isset($_GET['tab']) && $_GET['tab'] == 'today') {
			$dt_complet = 'CURDATE()';
			$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. 'WHERE dt_complet= ' . $dt_complet . ' AND category_id =' . $cat_id . ' AND t.user_id =' . $user_id;

			if ($res = mysqli_query($link, $sql)) {
				$tasksTdCat = mysqli_fetch_all($res, MYSQLI_ASSOC);
				$content = include_template('main.php', ['infoOfTasks' => $tasksTdCat, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}

		if (isset($_GET['tab']) && $_GET['tab'] == 'tomorrow') {
			$dt_complet = 'DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
			$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. 'WHERE dt_complet= ' . $dt_complet . ' AND category_id =' . $cat_id . ' AND t.user_id =' . $user_id;

			if ($res = mysqli_query($link, $sql)) {
				$tasksTmCat = mysqli_fetch_all($res, MYSQLI_ASSOC);
				$content = include_template('main.php', ['infoOfTasks' => $tasksTmCat, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}

		if (isset($_GET['cat_id']) && (isset($_GET['tab']) && $_GET['tab'] == 'missed')) {
			$dt_complet = 'DATE_SUB(NOW(), INTERVAL 1 DAY)';
			$sql = 'SELECT t.id, t.user_id, category_id, status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. 'WHERE status = 0 AND dt_complet< ' . $dt_complet . ' AND category_id =' . $cat_id . '  AND t.user_id =' . $user_id;

			if ($res = mysqli_query($link, $sql)) {
				$tasksMsCat = mysqli_fetch_all($res, MYSQLI_ASSOC);
				$content = include_template('main.php', ['infoOfTasks' => $tasksMsCat, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}

		$tasksUser = [];
		$search = ($_GET['q']) ?? '';

		if ($search) {
			$sql = "SELECT t.id, user_id, category_id, status, title, path, dt_complet, name FROM tasks t "
				. "JOIN users u ON t.user_id = u.id "
				. "WHERE MATCH(title) AGAINST(?)";
			$stmt = db_get_prepare_stmt($link, $sql, [$search]);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$tasksUser = mysqli_fetch_all($result, MYSQLI_ASSOC);

			if (count($tasksUser)) {
				$content = include_template('main.php', ['infoOfTasks' => $tasksUser, 'categories' => $categories, 'countTasks' => $countTasks]);
			} else {
				$content = "По вашему запросу ничего не найдено";
			}
		}


		if (isset($_GET['task_id'])) {
			$task_id = $_GET['task_id'];


			$sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
				. 'JOIN categories c ON t.category_id = c.id '
				. ' WHERE t.id =' . $task_id . ' AND t.user_id =' . $user_id;

			if ($res = mysqli_query($link, $sql)) {
				$completedTasks = mysqli_fetch_assoc($res);

				$sql = 'UPDATE tasks SET status = CASE status WHEN 0 THEN 1 WHEN 1 THEN 0 END  WHERE id =' . $task_id . ' AND user_id =' . $user_id;

				if ($res = mysqli_query($link, $sql)) {
					header("location: /");

				} else {
					$content = include_template('error.php', ['error' => mysqli_error($link)]);
				}
			}
		}
	} else {
		$content = include_template('guest.php', []);
	}

print(include_template('layout.php', [
	'content' => $content,
	'categories' => $categories,
	'title' => 'Дела в порядке'
]));
    
   
    
          
        
 