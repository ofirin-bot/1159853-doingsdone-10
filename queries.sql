INSERT INTO users (email, name, password) VALUES
('konstyan.ss@gmail.com', 'Kostya',  'milk'), 
('john.kerry@mail.ru', 'John',  'acdc')
;

INSERT INTO categories (user_id, name)VALUES
(1, 'Входящие'),
(1, 'Учеба'),
(1, 'Работа'),
(2, 'Работа'),
(1, 'Домашние дела'),
(2, 'Домашние дела')
;

INSERT INTO tasks (category_id, user_id, status, title, path, dt_complet) VALUES
(1, 1, '0', 'Встреча с другом', 'konstyan.ss@gmail.com', '2019.12.22'),
(2, 1, '0', 'Сделать задание первого раздела', 'konstyan.ss@gmail.com', '2019.12.21'),
(3, 1, '0', 'Собеседование в IT комапании', 'konstyan.ss@gmail.com', '2019.08.24'),
(3, 2, '0', 'Выполнить тестовое задание', 'john.kerry@mail.ru', '2019.12.25'),
(4, 1, '0', 'Купить корм для кота', 'konstyan.ss@gmail.com', NULL),
(4, 2, '0', 'Заказать пиццу', 'john.kerry@mail.ru', NULL)
;



/*получить список из всех проектов для одного пользователя*/
SELECT name FROM categories WHERE user_id = 1;

/*получить список из всех задач для одного проекта*/
SELECT title FROM tasks WHERE category_id = 4;

/*пометить задачу как выполненную*/
UPDATE tasks SET status = 1
WHERE title = 'Сделать задание первого раздела';


/*обновить название задачи по её идентификатору*/
UPDATE tasks SET title = 'Заказать пиццу Пепперони'
WHERE id = 12;

