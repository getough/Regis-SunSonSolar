<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<h2>Power Your Future with Solar</h2>
<p class="subtitle">Solar panels, inverters, batteries, racking and mounting, wires, and complete installation services in Pasig City.</p>
<a class="register-btn btn-link" href="<?= site_url('login') ?>">Log in</a>
<p class="login-link">New customer? <a href="<?= site_url('register') ?>">Create an account</a></p>
<p class="subtitle small">The full homepage (services, testimonials, FAQ, quote request) is still to be built.</p>
<?= $this->endSection() ?>
