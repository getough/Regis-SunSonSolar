<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2>Welcome, <?= esc($user['name']) ?></h2>
<p class="subtitle">
    <span class="badge"><?= esc(ucfirst($user['role'])) ?></span>
    <?php if (! empty($user['department'])): ?> &middot; <?= esc($user['department']) ?><?php endif; ?>
</p>

<?php if (in_array($user['role'], ['admin', 'hr'], true)): ?>
    <p><a class="register-btn btn-link" href="<?= site_url('staff') ?>">User management</a></p>
<?php endif; ?>

<p class="subtitle small">
    <?php if ($user['role'] === 'customer'): ?>
        Coming next: bookings, notifications, service history, payments and support.
    <?php elseif ($user['role'] === 'technician'): ?>
        Coming next: assigned jobs, the one-button site check-in/check-out, and problem reports.
    <?php elseif ($user['role'] === 'dispatcher'): ?>
        Coming next: schedule calendar and technician assignment.
    <?php else: ?>
        Coming next: reports, settings and announcements.
    <?php endif; ?>
</p>

<a class="link-btn" href="<?= site_url('change-password') ?>">Change password</a>
<form action="<?= site_url('logout') ?>" method="post" class="inline-form">
    <?= csrf_field() ?>
    <button type="submit" class="link-btn">Log out</button>
</form>
<?= $this->endSection() ?>
