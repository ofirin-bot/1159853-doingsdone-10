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
function is_date_valid(string $name) : bool
{
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
function db_get_prepare_stmt($link, $sql, $data = [])
{
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
			} else if (is_string($value)) {
				$type = 's';
			} else if (is_double($value)) {
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
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
	$number = (int)$number;
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
function include_template($name, array $data = [])
{
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

/**
 * Подcчитывает количество задач, относящихся к каждому проекту 
 * @param array $tasks Ассоциативный массив с задачами
 * @param string $category Наименование проекта
 * @return int $sum Итоговое количетсво
 */

function numberTasks($tasks, $catеgory)
{
	$sum = 0;
	foreach ($tasks as $task) {

		if (isset($task['name'])) {

			if ($task['name'] == $catеgory) {
				++$sum;
			}
		}
	}
	return $sum;
}

/**
 * Проверяет срок испольнения задачи до завершения которой меньше 24 часов  
 * @param date $date Передаваемая дата
 * @return bool true при выполнении условия 
 */

function checkCompleted($date)
{
	$ts = time();
	$end_ts = strtotime($date);
	$ts_diff = $end_ts - $ts;

	if ($ts_diff <= 86400) {

		return true;
	}
}

/**
 * Конвертирует переданную дату в формат 'ДД-ММ-ГГГГ'
 * @param date $date передаваемая дата 
 * @return date $dt результат испольнения  
 */   

function dateConvert($date)
{
	if ($date !== NULL) {
		$dt = date(date("d.m.y", strtotime($date)));
	} else {
		$dt = '';
	}
	return $dt;
}


/**
 * Получение введенных пользователем данных
 * @param string $name Имя поля
 * @return string $_POST[$name] полученно
 */

function getPostVal($name)
{
	return $_POST[$name] ?? "";
}


/**
 * Проверяет заполненность обязательных полей 
 * @param string $name имя поля
 * @return string текст о необходимости заполнения поля, иначе null
 */

function validateCategory($name)
{
	if (empty($_POST[$name])) {
		return "Это поле должно быть заполнено";
	}

	return null;
}

/**
 * Проверяет заполненеие поля наименованием проекта из существуюещего списка
 * @param string $name имя проекта
 * @param array $tasks Простой массив списка проектов
 * @return string текст о несуществующем проекте, иначе null
 */

function validateProject($name, $allowed_list)
{
	$id = $_POST[$name];

	if (!in_array($id, $allowed_list)) {
		return "Указан несуществующий проект";
	}

	return null;
}

/**
 * Проверяет на соответсствие длины названия  заданным параметрам
 * @param string $name проверяемое название
 * @param int $min минимальное количество символов
 * @param int $max максимальное количество символов
 * @return string текст c ограничением параметров, иначе null
 */

function validateLength($name, $min, $max)
{
	$len = strlen($_POST[$name]);

	if ($len < $min or $len > $max) {
		return "Значение должно быть от $min до $max символов";
	}

	return null;
}
/**
 * Проверяет на корректность введенный пользователем E-mail
 * @param string $name переданный E-mail
 * @return string текст о необходимости корректности E-mail , иначе null
 */

function validateEmail($name)
{

	if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
		return "Введите корректный email";
	}

	return null;
}

/**
 * Проверяет введенную пользователем дату на корректность даты исполнения
 * @param string $name переданная дата
 * @return string текст о некорректной дате , иначе null
 */

function check_date($name)   
{
       
    $date_completed = strtotime($_POST[$name]);
    $date_completed;   
    $today = strtotime('now');
   
	if (($date_completed - $today) < 0) {
       
		return "Время исполнения истекло";
	} 
    
    return null;	
}