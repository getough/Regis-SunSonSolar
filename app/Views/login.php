<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2>Log in</h2>
<p class="subtitle">Customers and employees log in here.</p>

<form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" maxlength="50" autocomplete="username"
               value="<?= esc(old('username')) ?>" required autofocus>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" maxlength="72" autocomplete="current-password" required>
    </div>
    <div class="form-group">
        <label class="checkbox-label"><input type="checkbox" data-toggle-password> Show password</label>
    </div>
    <button type="submit" class="register-btn" data-submit>Log in</button>
    <p class="login-link">New customer? <a href="<?= site_url('register') ?>">Create an account</a></p>
</form>
<?= $this->endSection() ?>
