<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sun Son Solar') ?> - Sun Son Solar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/registration.css') ?>">
</head>
<body>
<main class="container">
    <div class="register-card <?= esc($cardClass ?? '') ?>">

        <header class="brand-header">
            <!-- Put the logo at public/images/logo.png; it hides itself if missing. -->
            <img class="brand-logo" src="<?= base_url('images/logo.png') ?>" alt="Sun Son Solar logo"
                 onerror="this.style.display='none'">
        </header>

        <?php if ($msg = session()->getFlashdata('success')): ?>
            <div class="alert success" role="status"><?= esc($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = session()->getFlashdata('error')): ?>
            <div class="alert error" role="alert"><?= esc($msg) ?></div>
        <?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert error" role="alert">
                <ul>
                    <?php foreach ((array) $errors as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</main>
<script src="<?= base_url('js/auth.js') ?>"></script>
</body>
</html>
