<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';
$pdo = db();
$stats = [
    'New Registrations' => $pdo->query("SELECT COUNT(*) c FROM class_registrations WHERE status='new'")->fetch()['c'],
    'New Enquiries' => $pdo->query("SELECT COUNT(*) c FROM contact_enquiries WHERE status='new'")->fetch()['c'],
    'Active Courses' => $pdo->query("SELECT COUNT(*) c FROM courses WHERE active=1")->fetch()['c'],
    'Portfolio Items' => $pdo->query("SELECT COUNT(*) c FROM portfolio_items")->fetch()['c'],
];
?>
<h1>Dashboard</h1>
<div class="admin-cards">
  <?php foreach ($stats as $label => $val): ?>
    <div class="admin-card"><b><?= (int)$val ?></b><?= e($label) ?></div>
  <?php endforeach; ?>
</div>
<p><a href="/admin/registrations.php">View class registrations →</a></p>
<p><a href="/admin/enquiries.php">View contact enquiries →</a></p>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
