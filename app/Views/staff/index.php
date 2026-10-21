<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2>User management</h2>

<?php if ($new = session()->getFlashdata('new_account')): ?>
    <div class="alert success" role="status">
        <strong>Account created.</strong> Give these to the employee privately. This password is shown only once
        and must be changed at first login.<br>
        Username: <code><?= esc($new['username']) ?></code> &nbsp; Temporary password: <code><?= esc($new['password']) ?></code>
    </div>
<?php endif; ?>

<p><a class="register-btn btn-link" href="<?= site_url('staff/create') ?>">+ Create employee account</a></p>

<div class="table-wrap">
<table>
    <thead>
        <tr><th>Name</th><th>Username</th><th>Role</th><th>Department</th><th>Email</th><th>Status</th><th></th></tr>
    </thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= esc(full_name($u)) ?></td>
            <td><?= esc($u['username']) ?></td>
            <td><?= esc($u['role_name']) ?></td>
            <td><?= esc($u['department_name'] ?? '-') ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= (int) $u['is_active'] === 1 ? 'Active' : 'Deactivated' ?></td>
            <td>
                <?php if ((int) $u['user_id'] !== $selfId): ?>
                    <form action="<?= site_url('staff/toggle/' . (int) $u['user_id']) ?>" method="post" class="inline-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="link-btn"><?= (int) $u['is_active'] === 1 ? 'Deactivate' : 'Reactivate' ?></button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<p class="login-link"><a href="<?= site_url('dashboard') ?>">&larr; Back to dashboard</a></p>
<?= $this->endSection() ?>
