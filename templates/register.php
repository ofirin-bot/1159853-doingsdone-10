

      <div class="content">
        <section class="content__side">
          <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

          <a class="button button--transparent content__side-button" href="">Войти</a>
        </section> 

        <main class="content__main">
          <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="register.php" method="post" enctype="multipart/form-data" autocomplete="off">
              
            <div class="form__row">               
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input" type="text" name="email" id="email" value="<?=($value['email']) ?? '' ?>" placeholder="Введите e-mail">
                
                <?php if (isset($errors['email'])):  ?>
              <p class="form__message"><?= $errors['email'] ?? ""; ?></p>
                <?php endif ?>
            </div>

            <div class="form__row">                
              <label class="form__label" for="password">Пароль <sup>*</sup></label>
              <input class="form__input" type="password" name="password" id="password" value="<?=($value['password']) ?? '' ?>" placeholder="Введите пароль">
                
                 <?php if (isset($errors['password'])):  ?>
              <p class="form__message"><?= $errors['password']; ?></p>
                <?php endif; ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>            
                
              <input class="form__input" type="text" name="name" id="name" value="<?=($value['name']) ?? '' ?>" placeholder="Введите имя">
                
                 <?php if (isset($errors['name'])):  ?>
              <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
                <?php endif; ?>
                
            </div>

            <div class="form__row form__row--controls">
               <?php if (isset($errors)): ?>
              <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
                <?php endif; ?>
              <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
          </form>
        </main>

      </div>