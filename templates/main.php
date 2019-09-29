
<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

     <nav class="main-navigation">
        <ul class="main-navigation__list">
         <?php foreach($categories as $cat): ?>                           
                                              
            <li class="main-navigation__list-item <?php ($cat['id'] == $_GET['cat_id'])? print 'main-navigation__list-item--active' : ''; ?> "> 
                                                        
            <a class="main-navigation__list-item-link" href="index.php?cat_id=<?= $cat['id']; ?>"><?=$cat['name']; ?>
           </a>                        
                         
            <span class="main-navigation__list-item-count">
                
              <?= number_tasks($countTasks, $cat['name']); ?>                
                              
            </span>   
            
             </li>                        
             <?php endforeach; ?>                        
         </ul>
    </nav>
     <a class="button button--transparent button--plus content__side-button"
        href="addprj.php" target="/">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        
    <input class="search-form__input" type="search" name="q" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">              
                 
       
    </form>

    <div class="tasks-controls">
     <nav class="tasks-switch">
         
        <a href="/?cat_id=<?=$_GET['cat_id']; ?>&tab=all" class="tasks-switch__item <?php (isset($_GET['tab']) && $_GET['tab'] == 'all')? print 'tasks-switch__item--active' : ''; ?>">Все задачи</a>
         
        <a href="/?cat_id=<?=$_GET['cat_id']; ?>&tab=today" class="tasks-switch__item <?php (isset($_GET['tab']) && $_GET['tab'] == 'today')? print 'tasks-switch__item--active' : ''; ?>">Повестка дня</a>
         
        <a href="/?cat_id=<?=$_GET['cat_id']; ?>&tab=tomorrow" class="tasks-switch__item <?php (isset($_GET['tab']) && $_GET['tab'] == 'tomorrow')? print 'tasks-switch__item--active' : ''; ?>">Завтра</a>   
         
        <a href="/?cat_id=<?=$_GET['cat_id']; ?>&tab=missed" class="tasks-switch__item <?php (isset($_GET['tab']) && $_GET['tab'] == 'missed')? print 'tasks-switch__item--active' : ''; ?>">Просроченные</a>
      </nav>
   
        
        
        
    <label class="checkbox">
        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->                 
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php (isset($_GET['show_completed']) && $_GET['show_completed'] == 1) ? print 'checked' : ''; ?> >
             
        
         <span class="checkbox__text">Показывать выполненные</span>
     </label>
    </div>

    
  <table class="tasks">
    <?php foreach($infoOfTasks as $task): ?>  
      
       
     <?php if(($task['status'] == 1) && (isset($_GET['show_completed']) && $_GET['show_completed']  == 0)) {
        
         continue;
    } ?> 
      
          
      
      <tr class="tasks__item task <?= ($task['status'] == 1)?  'task--completed' : '' ?>         
     <?= check_completed($task['dt_complet'])?  'task--important' : '' ?> ">             
                        
         <td class="task__select">
             <label class="checkbox task__checkbox">
                 
                 <?php $show = (($task['status'] == 1) && (isset($_GET['show_completed']) && $_GET['show_completed']  == 1)); ?>
                 <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"  value="1" <?= ($show)? 'checked': '';  ?>>
                 
                <span class="checkbox__text">                            
                  <?= htmlspecialchars($task['title']); ?>
                </span>
                 
            </label>
        </td>
          
          <td class="task__file">                            
            <a class="download-link" href="<?= $task['path']; ?>"><?= $task['path']; ?></a>
          </td> 
          <td class="task__date">
            <?= date_convert($task['dt_complet']);  ?>
                            
         </td>
          
      </tr>
      <?//php endif; ?>
        <?php endforeach; ?>    
    
      
        <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице--> 
        
      <?//php if(isset($_GET['show_completed']) &&  $_GET['show_completed'] ==1): ?>     
      
         <?//php foreach($getCheck as $get): ?>
        <!-- <tr class="tasks__item task task--completed">
            <td class="task__select">
                  <label class="checkbox task__checkbox">
                      <input class="checkbox__input visually-hidden" type="checkbox" checked>
                     <span class="checkbox__text"><?//= $get['title']; ?>Записаться на интенсив "Базовый PHP"</span>
                   </label>
             </td>
             <td class="task__date"><?//= date_convert($get['dt_complet']);  ?> 10.10.2019</td>
             
             <td class="task__controls"></td>
        </tr>
       <?//php endforeach; ?>
      <?//php endif; ?>  -->  
      
    </table>  
        
        
     
</main>