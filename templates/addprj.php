<div class="content">
	<section class="content__side">
		<h2 class="content__side-heading">Проекты</h2>

		<nav class="main-navigation">
			<ul class="main-navigation__list">

				<?php foreach ($categories as $key => $cat): ?>
					<li class="main-navigation__list-item">
						<a class="main-navigation__list-item-link" href="<?= $cat['id']; ?>"><?= $cat['name']; ?></a>
						<span class="main-navigation__list-item-count"><?= numberTasks($count_tasks, $cat['name']); ?></span>

					</li>
				<?php endforeach ?>

			</ul>
		</nav>

		<a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
	</section>

	<main class="content__main">
		<h2 class="content__main-heading">Добавление проекта</h2>

		<form class="form" action="addprj.php" method="post" autocomplete="off">


			<div class="form__row">
				<label class="form__label" for="project_name">Название <sup>*</sup></label>
				<?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
				<input class="form__input <?= $classname; ?>" type="text" name="name" id="project_name"
							 value="<?= getPostVal('name'); ?>" placeholder="Введите название проекта">
			</div>

			<?php if (isset($errors['name'])): ?>
				<p class="form__message"><?= $errors['name'] ?? ""; ?></p>
			<?php endif; ?>

			<div class="form__row form__row--controls">
				<input class="button" type="submit" name="" value="Добавить">
			</div>
		</form>
	</main>
</div>