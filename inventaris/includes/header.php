<?php /* $page_title harus di-set sebelum include */ ?>
<!DOCTYPE html>
<html lang="<?= e($_SESSION['lang']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title ?? t('app_name')) ?> - <?= e(t('app_name')) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php"><?= e(t('app_name')) ?></a>
    <nav>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="index.php"><?= e(t('nav_list')) ?></a>
            <a href="create.php"><?= e(t('nav_add')) ?></a>
        <?php endif; ?>
        <span class="lang">
            <a href="set_lang.php?lang=id" class="<?= $_SESSION['lang'] === 'id' ? 'active' : '' ?>">ID</a> |
            <a href="set_lang.php?lang=en" class="<?= $_SESSION['lang'] === 'en' ? 'active' : '' ?>">EN</a>
        </span>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <span class="user"><?= e($_SESSION['user_name']) ?></span>
            <a href="logout.php" class="btn btn-sm btn-gray"><?= e(t('nav_logout')) ?></a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
<?php if ($flash = get_flash()): ?>
    <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>
