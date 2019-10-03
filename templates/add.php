<div class="content">
	<section class="content__side">
		<h2 class="content__side-heading">Проекты</h2>
		<nav class="main-navigation">
			<ul class="main-navigation__list">

				<?php foreach ($categories as $key => $cat): ?>
					<li class="main-navigation__list-item">
						<a class="main-navigation__list-item-link" href="<?= $cat['id']; ?>"><?= $cat['name']; ?></a>
						<span class="main-navigation__list-item-count"><?= number_tasks($countTasks, $cat['name']); ?> </span>

					</li>
				<?php endforeach ?>

			</ul>
		</nav>
		<a class="button button--transparent button--plus content__side-button" href="addprj.php">Добавить проект</a>
	</section>

	<main class="content__main">
		<h2 class="content__main-heading">Добавление задачи</h2>

		<form class="form" action="add.php" method="post" enctype="multipart/form-data" autocomplete="off">

			<div class="form__row">

				<label class="form__label" for="title">Название <sup>*</sup></label>
				<?php $classname = isset($errors['title']) ? "form__input--error" : ""; ?>

				<input class="form__input <?= $classname; ?>" type="text" name="title" id="name"
							 value="<?= getPostVal('title'); ?>" placeholder="Введите название">

				<?php if (isset($errors['title'])): ?>
					<p class="form__message"><?= $errors['title'] ?? ""; ?></p>
				<?php endif; ?>
			</div>

			<div class="form__row">
				<label class="form__label" for="category_id">Проект <sup>*</sup></label>
				<?php $classname = isset($errors['category_id']) ? "form__input--error" : ""; ?>
				<select class="form__input form__input--select <?= $classname; ?>" name="category_id" id="category">

					<?php foreach ($categories as $cat): ?>

						<option value="<?= $cat['id'] ?>"

										<?php if (isset($task['category_id']) && ($cat['id'] == $task['category_id'])): ?>selected<?php endif; ?>>

							<?= $cat['name']; ?></option>
					<?php endforeach; ?>

				</select>
				<?php if (isset($errors['category_id'])): ?>
					<p class="form__message"><?= $errors['category_id'] ?? ""; ?></p>
				<?php endif; ?>
			</div>

			<div class="form__row">
				<label class="form__label" for="dt_complet">Дата выполнения</label>
				<?php $classname = isset($errors['dt_complet']) ? "form__input--error" : ""; ?>

				<input class="form__input form__input--date <?= $classname; ?>" type="text" name="dt_complet" id="date"
							 value="<?= getPostVal('dt_complet'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">

				<?php if (isset($errors['dt_complet'])): ?>
					<p class="form__message"><?= $errors['dt_complet'] ?? ""; ?></p>
				<?php endif; ?>
			</div>

			<div class="form__row">
				<label class="form__label" for="file">Файл</label>
				<?php $classname = isset($errors['file']) ? "form__input--error" : ""; ?>
				<div class="form__input-file">
					<input class="visually-hidden <?= $classname; ?>" type="file" name="file" id="file"
								 value="<?= getPostVal('file'); ?>">
					<?php if (isset($errors['file'])): ?>
						<p class="form__message"><?= $errors['file'] ?? ""; ?></p>
					<?php endif; ?>

					<label class="button button--transparent" for="file">
						<span>Выберите файл</span>
					</label>
				</div>
			</div>


			<div class="form__row form__row--controls">
				<input class="button" type="submit" name="" value="Добавить">
			</div>
		</form>
	</main>
</div>
          