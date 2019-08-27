INSERT INTO users (dt_add, email, name password) VALUES
('N0W()', 'konstyan.ss@gmail.com', 'Kostya',  'milk'), 
('N0W()', 'john.kerry@mail.ru', 'John',  'acdc')
;

INSERT INTO categories (user_id, name)VALUES
('1', 'Входящие'),
('1', 'Учеба'),
('1', 'Работа'),
('2', 'Работа'),
('1', 'Домашние дела'),
('2', 'Домашние дела')
;

INSERT INTO categories 
SET name = 'Авто';

INSERT INTO tasks (category_id user_id, dt_add, status, title, path, dt_complet) VALUES
('1', '1','N0W()','0', 'Встреча с другом', 'konstyan.ss@gmail.com', '2019.12.22'),
('2', '1','N0W()','0', 'Сделать задание первого раздела', 'konstyan.ss@gmail.com', '2019.12.21'),
('3', '1','N0W()','0', 'Собеседование в IT комапании', 'konstyan.ss@gmail.com', '2019.08.24'),
('3', '2','N0W()','0', 'Выполнить тестовое задание', 'john.kerry@mail.ru', '2019.12.25'),
('4', '1','N0W()','0', 'Купить корм для кота', 'konstyan.ss@gmail.com', ''),
('4', '2','N0W()','0', 'Заказать пиццу', 'john.kerry@mail.ru', '2019.12.22'),
;



/*получить список из всех проектов для одного пользователя*/
SELECT name FROM categories WHERE user_id = '1';

/*получить список из всех задач для одного проекта*/
SELECT title FROM tasks WHERE category_id = '4';

/*пометить задачу как выполненную*/
UPDATE tasks set status = '1'
WHERE tasks = 'Сделать задание первого раздела';


/*обновить название задачи по её идентификатору*/
UPDATE SET set title = 'id'
WHERE tasks = 'Заказать пиццу';
