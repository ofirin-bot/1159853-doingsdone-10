<?php
require_once('helpers.php');
require_once 'init.php';


if(!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}

else {
        
    $sql = 'SELECT id, user_id, name  FROM categories  WHERE user_id = 1';
      
    $result = mysqli_query($link, $sql);
  
    if ($result) {        
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
         $records_count = mysqli_num_rows($result);
    }    
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
    
    $sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
        .  'JOIN categories c ON t.category_id = c.id '
        .  'WHERE t.user_id = 1';
       
    
    if($res = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC); 
        $content = include_template('main.php', ['infoOfTasks' =>$tasks, 'categories' => $categories]);
    }
    else {      
        $content = include_template('error.php', ['error' =>  mysqli_error($link)]);    
    }
    
    $sql = 'SELECT id, email, name FROM users'; 
    
    if($ress = mysqli_query($link, $sql)) {
        $row = mysqli_fetch_assoc($ress);
        
    }
    else {      
        $content = include_template('error.php', ['error' =>  mysqli_error($link)]);    
    }
    
}
//var_dump($users);
//die;
print(include_template('layout.php', ['content' => $content, 'categories' => $categories, 'users' => $row]));

// показывать или нет выполненные задачи
/*$show_complete_tasks = rand(0, 1);
$categories = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$info_of_tasks = [
    [
        "task" => "Собеседование в IT комапании", 
        "dateCompleted" => "24.08.2019",
        "category" => "Работа",
        "done" => false  
    ],
    [
       "task" => "Выполнить тестовое задание", 
        "dateCompleted" => "25.12.2019",
        "category" => "Работа",
        "done"  => false   
    ],
    [
        "task" => "Сделать задание первого раздела", 
        "dateCompleted" => "21.12.2019",
        "category" => "Учеба",
        "done"  => true     
    ],
    [
        "task" => "Встреча с другом", 
        "dateCompleted" => "22.12.2019",
        "category" => "Входящие",
        "done"  => false    
    ],
    [
        "task" => "Купить корм для кота", 
        "dateCompleted" => "",
        "category" => "Домашние дела",
        "done"  => false    
    ],
    [
        "task" => "Заказать пиццу", 
        "dateCompleted" => "",
        "category" => "Домашние дела",
        "done"  => false    
    ]
];



$pageTitle = "Дела в порядке";
$userName = "Константин";


$pageContent = include_template('main.php', [
    'categories' => $categories,
    'infoOfTasks' => $info_of_tasks,
    'completeTask' => $show_complete_tasks 
]);

print include_template('layout.php', [
'content' => $pageContent,
'userName' => $userName,
'title' => $pageTitle
]);*/



