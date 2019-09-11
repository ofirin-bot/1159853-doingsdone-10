<?php
require_once 'helpers.php';
require_once 'init.php';
 
    $sql = 'SELECT id, name  FROM categories 
    WHERE user_id = 1';      
    $result = mysqli_query($link, $sql);

    $cats_ids = [];
  
    if ($result) {        
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);  
        $cats_ids = array_column($categories, 'id'); 
   }  
$sql = 'SELECT id, name FROM users WHERE id = 1'; 
    
    if($ress = mysqli_query($link, $sql)) {
        $user = mysqli_fetch_assoc($ress);
   
       
    }
    else {      
        $content = include_template('error.php', ['error' =>  mysqli_error($link)]);    
    }
  
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          
          $task = $_POST;
          
          $required = ['title', 'category_id'];
          $errors = [];
          
    $rules = [
        'title' => function() {
            return validateLength('title', 10, 200);             
        },
        
        'category_id' => function() use ($cats_ids) {
            return validateCategory('category_id', $cats_ids);            
        },        
        
        'dt_complet' => function() {
            return check_date('dt_complet');             
        }         
    ];

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule();
    }
}

$errors =  array_filter($errors);

foreach ($required as $key)  {
    if (empty($_POST[$key])) {
        $errors[$key] = 'Это поле надо заполнить';
    }
}  
         
    
   
          
 if(isset($_FILES['file']['name'])) {
     $tmp_name = $_FILES['file']['tmp_name'];
     $arr = explode('.', $_FILES['file']['name']);  
     
     $expansion = end($arr); 
    
     $path = $_FILES['file']['name'];
   
     $filename = uniqid() . ".$expansion";     

   
         move_uploaded_file($tmp_name, 'uploads/' . $filename);
         $task['path'] = $filename;  
     
    } 
    else {
        $errors['file'] = "Вы не загрузили файл";
    }
 
        
    if (count($errors)) {
        
        $page_content = include_template('add.php', ['task' => $task, 'errors' => $errors, 'categories' => $categories]);
    }       
         
     else {
            
         $sql = 'INSERT INTO tasks (category_id, user_id, dt_add, status, title,  path, dt_complet) VALUES (?, 1, NOW(), 0, ?,  "uploads/' . $filename .'", ?)';
         
       
         $category_id = $_POST['category_id'];
         $title = $_POST['title'];
         $dt_complet = $_POST['dt_complet'];
        
         $stmt = mysqli_prepare($link, $sql);
         mysqli_stmt_bind_param($stmt, 'iss', $category_id, $title, $dt_complet);
         
         $res = mysqli_stmt_execute($stmt);
        
         if ($res) { 
             $file_url = 'uploads/' . $filename;
            
            
            header("Location: /" ); 
             
            
        }        
     }
          
 }
else {
    $page_content = include_template('add.php', ['categories' => $categories, 'user' => $user]);
}
 


$layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => [],
        'title' => 'Дела в порядке - Добавление задачи',
        'user' => $user
]);

print($layout_content);
     
    
        
        
        
        
        
  
