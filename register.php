<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Class Registration';
$pdo = db();
$courses = $pdo->query("SELECT * FROM courses WHERE active = 1 ORDER BY sort_order")->fetchAll();
$selectedCourse = (int)($_GET['course'] ?? 0);
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $courseId = (int)($_POST['course_id'] ?? 0) ?: null;
    $batch = trim($_POST['preferred_batch'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $phone === '') {
        $error = 'Name and phone are required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO class_registrations (name, phone, email, course_id, preferred_batch, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email ?: null, $courseId, $batch ?: null, $message ?: null]);
        $success = true;
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">Register</p>
  <h1>Class <em>Registration</em></h1>
  <p>Fill this in and we'll confirm your batch over phone or WhatsApp.</p>
</section>
<section class="section-pad">
  <?php if ($success): ?>
    <div class="flash ok">Thanks — your registration is in. We'll contact you shortly to confirm the batch.</div>
  <?php else: ?>
    <?php if ($error): ?><div class="flash err"><?= e($error) ?></div><?php endif; ?>
    <form class="site-form" method="post">
      <div>
        <label for="name">Full Name</label>
        <input id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
      </div>
      <div>
        <label for="phone">Phone</label>
        <input id="phone" name="phone" required value="<?= e($_POST['phone'] ?? '') ?>">
      </div>
      <div>
        <label for="email">Email (optional)</label>
        <input id="email" name="email" type="email" value="<?= e($_POST['email'] ?? '') ?>">
      </div>
      <div>
        <label for="course_id">Course</label>
        <select id="course_id" name="course_id">
          <option value="">— Select a course —</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $selectedCourse === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['title']) ?> (<?= e($c['fee']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="preferred_batch">Preferred Batch / Timing</label>
        <input id="preferred_batch" name="preferred_batch" placeholder="e.g. Weekend mornings">
      </div>
      <div>
        <label for="message">Message (optional)</label>
        <textarea id="message" name="message"></textarea>
      </div>
      <button class="button" type="submit">Submit Registration <b>→</b></button>
    </form>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
