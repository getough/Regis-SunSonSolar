<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<h2>Privacy Notice</h2>
<p class="subtitle"><strong>DRAFT.</strong> This text is a placeholder and must be reviewed and approved by Sun Son Solar (and ideally a lawyer) before launch.</p>

<div class="legal">
    <p><strong>What we collect.</strong> Name, birthdate, gender (optional), email, phone number, address, username, and a securely hashed password. For technicians, the time and GPS coordinates recorded when the check-in/check-out button is pressed.</p>
    <p><strong>Why.</strong> To create and manage your account, schedule and deliver solar services, send service notifications, issue receipts, and keep attendance records.</p>
    <p><strong>Who can see it.</strong> Authorized Sun Son Solar staff, only as needed for their role. Technicians see only the customer details for jobs assigned to them.</p>
    <p><strong>How long we keep it.</strong> [To be decided by the company.]</p>
    <p><strong>Your rights.</strong> You may ask to access, correct, or request deletion of your personal information, as provided under the Philippine Data Privacy Act of 2012 (RA 10173).</p>
    <p><strong>Contact.</strong> [Company data protection contact to be added.]</p>
</div>
<p class="login-link"><a href="<?= site_url('register') ?>">&larr; Back to registration</a></p>
<?= $this->endSection() ?>
