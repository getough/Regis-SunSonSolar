<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2>Create Employee Account</h2>
<p class="subtitle">A temporary password is generated and shown once after saving. The employee must change it at first login.</p>

<form action="<?= site_url('staff') ?>" method="post" novalidate>
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Select role</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= esc($r) ?>" <?= old('role') === $r ? 'selected' : '' ?>><?= esc(ucfirst($r)) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="department_id">Department:</label>
        <select id="department_id" name="department_id" required>
            <option value="">Select department</option>
            <?php foreach ($departments as $d): ?>
                <option value="<?= (int) $d['department_id'] ?>" <?= (string) old('department_id') === (string) $d['department_id'] ? 'selected' : '' ?>>
                    <?= esc($d['department_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" maxlength="50" value="<?= esc(old('first_name')) ?>" required>
    </div>
    <div class="form-group">
        <label for="middle_name">Middle Name (optional):</label>
        <input type="text" id="middle_name" name="middle_name" maxlength="50" value="<?= esc(old('middle_name')) ?>">
    </div>
    <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" maxlength="50" value="<?= esc(old('last_name')) ?>" required>
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
        <input type="date" id="birthdate" name="birthdate" data-max-today value="<?= esc(old('birthdate')) ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="100" value="<?= esc(old('email')) ?>" required>
    </div>
    <div class="form-group">
        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" inputmode="numeric" maxlength="11" data-digits
               placeholder="09XXXXXXXXX" value="<?= esc(old('phone_number')) ?>" required>
    </div>
    <div class="form-group">
        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="3" required><?= esc(old('address')) ?></textarea>
    </div>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" minlength="3" maxlength="50" value="<?= esc(old('username')) ?>" required>
    </div>

    <button type="submit" class="register-btn" data-submit>Create account</button>
    <p class="login-link"><a href="<?= site_url('staff') ?>">&larr; Back to user management</a></p>
</form>
<?= $this->endSection() ?>
