<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';
$pdo = db();
$stats = [
    'New Registrations' => $pdo->query("SELECT COUNT(*) c FROM class_registrations WHERE status='new'")->fetch()['c'],
    'New Enquiries' => $pdo->query("SELECT COUNT(*) c FROM contact_enquiries WHERE status='new'")->fetch()['c'],
    'Active Courses' => $pdo->query("SELECT COUNT(*) c FROM courses WHERE active=1")->fetch()['c'],
    'Portfolio Items' => $pdo->query("SELECT COUNT(*) c FROM portfolio_items")->fetch()['c'],
    'Gallery Items' => $pdo->query("SELECT COUNT(*) c FROM gallery_items")->fetch()['c'],
    'Services' => $pdo->query("SELECT COUNT(*) c FROM services")->fetch()['c'],
];
?>
<h1>Dashboard</h1>
<div class="admin-cards">
  <?php foreach ($stats as $label => $val): ?>
    <div class="admin-card"><b><?= (int)$val ?></b><?= e($label) ?></div>
  <?php endforeach; ?>
</div>
<div class="admin-cards">
  <a class="btn primary" href="/admin/registrations.php">Class Registrations →</a>
  <a class="btn primary" href="/admin/enquiries.php">Contact Enquiries →</a>
  <a class="btn ghost" href="/admin/content.php?table=portfolio_items">Manage Portfolio →</a>
  <a class="btn ghost" href="/admin/content.php?table=courses">Manage Courses →</a>
</div>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
