<?php
require_once('helpers.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$info_of_tasks = [
    [
        "task" => "Собеседование в IT комапании", 
        "dateCompleted" => "01.12.2019",
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
function number_tasks($arr, $catg){
    $sum = 0;
    foreach ($arr as $tsk){        
        if(isset($tsk['category'])) {
            
            if($tsk['category'] == $catg){
                ++$sum ;
            }              
        }
    } 
    
    return $sum;
}
$userName = "Константин";
$pageTitle = "Дела в порядке";


$pageContent = include_template('main.php', [
    'projects' => $projects,
    'infoOfTasks' => $info_of_tasks,
    'completeTask' => $show_complete_tasks = rand(0, 1)
]);
//var_dump($pageContent);
//die;
print include_template('layout.php', [
'content' => $pageContent,
'userName' => $userName,
'title' => $pageTitle
]);

?>
