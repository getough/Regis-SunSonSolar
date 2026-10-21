<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2>Create Customer Account</h2>
<p class="subtitle">Please fill in the details to register. Staff accounts are created by HR or an administrator.</p>

<form action="<?= site_url('register') ?>" method="post" novalidate>
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" maxlength="50" autocomplete="given-name"
               value="<?= esc(old('first_name')) ?>" required>
    </div>

    <div class="form-group">
        <label for="middle_name">Middle Name (optional):</label>
        <input type="text" id="middle_name" name="middle_name" maxlength="50" autocomplete="additional-name"
               value="<?= esc(old('middle_name')) ?>">
    </div>

    <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" maxlength="50" autocomplete="family-name"
               value="<?= esc(old('last_name')) ?>" required>
    </div>

    <div class="form-group">
        <label for="gender">Gender (optional):</label>
        <select id="gender" name="gender">
            <option value="">Select gender</option>
            <?php foreach (['Male', 'Female', 'Other', 'Prefer not to say'] as $g): ?>
                <option value="<?= esc($g) ?>" <?= old('gender') === $g ? 'selected' : '' ?>><?= esc($g) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" autocomplete="bday" data-max-today
               value="<?= esc(old('birthdate')) ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="100" autocomplete="email"
               value="<?= esc(old('email')) ?>" required>
    </div>

    <div class="form-group">
        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" inputmode="numeric" maxlength="11" data-digits
               autocomplete="tel" placeholder="09XXXXXXXXX" value="<?= esc(old('phone_number')) ?>" required>
    </div>

    <div class="form-group">
        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="3" autocomplete="street-address"
                  placeholder="Enter your complete address" required><?= esc(old('address')) ?></textarea>
    </div>

    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" minlength="3" maxlength="50" autocomplete="username"
               value="<?= esc(old('username')) ?>" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="72" autocomplete="new-password"
               placeholder="At least 8 characters, with a letter and a number" required>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" maxlength="72"
               autocomplete="new-password" required>
    </div>

    <div class="form-group">
        <label class="checkbox-label"><input type="checkbox" data-toggle-password> Show passwords</label>
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="privacy_consent" value="1" required>
            I agree to the <a href="<?= site_url('privacy') ?>" target="_blank" rel="noopener">Privacy Notice</a>
            and consent to Sun Son Solar collecting my personal information.
        </label>
    </div>

    <button type="submit" class="register-btn" data-submit>Register</button>
    <p class="login-link">Already have an account? <a href="<?= site_url('login') ?>">Log in</a></p>
</form>
<?= $this->endSection() ?>
