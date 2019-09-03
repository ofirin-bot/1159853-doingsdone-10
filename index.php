<?php
require_once('helpers.php');
require_once 'init.php';


if(!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}

else {
        
    $sql = 'SELECT id, user_id, name  FROM categories 
    WHERE user_id = 1';
      
    $result = mysqli_query($link, $sql);
  
    if ($result) {        
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);         
    }    
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
}

if (isset($_GET['id'])) {
     
      $sort_field = $_GET['id'];      
          
          
    
       
  $sql = 'SELECT t.id, t.user_id, category_id,  status, title, path, dt_complet, name FROM tasks t '
        .  'JOIN categories c ON t.category_id = c.id '        
       .  'WHERE t.category_id  = ' .  $sort_field  . ' AND t.user_id = 1 ';

   
  
     if($result = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC); 
        $content = include_template('main.php', ['infoOfTasks' =>$tasks, 'categories' => $categories]);
    }
    else {      
        $content = include_template('error.php', ['error' =>  mysqli_error($link)]);    
    }
  } 
else {
    
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

    
}
    
    
    $sql = 'SELECT id, email, name FROM users'; 
    
    if($ress = mysqli_query($link, $sql)) {
        $row = mysqli_fetch_assoc($ress);
        
    }
    else {      
        $content = include_template('error.php', ['error' =>  mysqli_error($link)]);    
    }
     
  
    if (count($tasks) === 0) {
        http_response_code(404);
        exit;
    }


print(include_template('layout.php', ['content' => $content, 'categories' => $categories, 'users' => $row]));


    





