<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом , иначе false
 */
function is_date_valid(string $name) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $name);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
    
  
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

   /*($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    } */

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 'null';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

       /*if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }*/
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


function number_tasks($arr, $catg){
     $sum = 0;
    foreach ($arr as $tsk){ 
       
        if(isset($tsk['name'])) {
            
            if($tsk['name'] == $catg){
                ++$sum ;               
            }             
        }        
    }     
    return $sum;
}

function check_completed($cdk) {
    $ts = time();
    $end_ts = strtotime($cdk);              
    $ts_diff = $end_ts - $ts;
    
    if($ts_diff <= 86400) {
        
         return true;        
    }    
  }
        

function date_convert($date) {
    if($date !== NULL) {
      $dt =  date(date("d.m.y", strtotime($date)));
    }
    else {
        $dt = '';
    }
    return $dt;
}

function get_url($active) {
    
    $params['id'] = $active; 
    $scriptname = 'index.php';
    $query = http_build_query($params);
    $rl = "/" . $scriptname . "?" . $query;

     return $rl;  
}
               
function getPostVal($name) {
    return $_POST[$name] ?? "";
}     

function validateCategory($name) {
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }    
    
    return null;
}   

function validateProject($name, $allowed_list) {
    $id = $_POST[$name];
    
    if (!in_array($id, $allowed_list)) {
        return "Указан несуществующий проект";
    }    
    
    return null;
}   

function validateLength($name, $min, $max) {
    $len = strlen($_POST[$name]);
    
    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }    
    
    return null;
} 

function validateEmail($name) {
        
    if (!filter_input(INPUT_POST,$name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }    
    
    return null;
}   
    
function check_date($name) {
   // $check_time = strtotime("yesterday midnight");
   
    if (strtotime($name) < strtotime(date('Y.m.d'))) {
        return "Врема исполнения истекло";
    
    }     
    else {
        return null;
    }
}