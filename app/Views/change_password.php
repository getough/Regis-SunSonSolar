<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2>Change Password</h2>
<p class="subtitle">Choose a new password. At least 8 characters, with a letter and a number.</p>

<form action="<?= site_url('change-password') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="current_password">Current (or temporary) password:</label>
        <input type="password" id="current_password" name="current_password" maxlength="72" autocomplete="current-password" required>
    </div>
    <div class="form-group">
        <label for="new_password">New password:</label>
        <input type="password" id="new_password" name="new_password" minlength="8" maxlength="72" autocomplete="new-password" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm new password:</label>
        <input type="password" id="confirm_password" name="confirm_password" maxlength="72" autocomplete="new-password" required>
    </div>
    <div class="form-group">
        <label class="checkbox-label"><input type="checkbox" data-toggle-password> Show passwords</label>
    </div>
    <button type="submit" class="register-btn" data-submit>Change password</button>
</form>
<form action="<?= site_url('logout') ?>" method="post" class="inline-form">
    <?= csrf_field() ?>
    <button type="submit" class="link-btn">Log out</button>
</form>
<?= $this->endSection() ?>
