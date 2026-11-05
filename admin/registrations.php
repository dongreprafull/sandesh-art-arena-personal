<?php
$pageTitle = 'Class Registrations';
require_once __DIR__ . '/includes/admin_header.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $allowed = ['new', 'contacted', 'confirmed', 'enrolled'];
    if (in_array($_POST['status'], $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE class_registrations SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], (int)$_POST['id']]);
    }
    header('Location: /admin/registrations.php');
    exit;
}

$rows = $pdo->query("SELECT r.*, c.title AS course_title FROM class_registrations r LEFT JOIN courses c ON c.id = r.course_id ORDER BY r.created_at DESC")->fetchAll();
?>
<h1>Class Registrations</h1>
<table class="admin-table">
  <tr><th>Date</th><th>Name</th><th>Phone</th><th>Email</th><th>Course</th><th>Preferred Batch</th><th>Status</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><?= e(date('d M Y, H:i', strtotime($r['created_at']))) ?></td>
    <td><?= e($r['name']) ?></td>
    <td><?= e($r['phone']) ?></td>
    <td><?= e($r['email']) ?></td>
    <td><?= e($r['course_title']) ?></td>
    <td><?= e($r['preferred_batch']) ?></td>
    <td>
      <form method="post" style="display:flex;gap:.4rem;align-items:center">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <select name="status" onchange="this.form.submit()">
          <?php foreach (['new','contacted','confirmed','enrolled'] as $st): ?>
            <option value="<?= $st ?>" <?= $r['status']===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="7">No registrations yet.</td></tr><?php endif; ?>
</table>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
