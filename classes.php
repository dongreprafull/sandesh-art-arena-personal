<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Courses & Classes';
$courses = db()->query("SELECT * FROM courses WHERE active = 1 ORDER BY sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">Learn</p>
  <h1>Courses & <em>Classes</em></h1>
  <p>Pick a course and register below — batches are confirmed over phone/WhatsApp after you register.</p>
</section>
<section class="section-pad">
  <?php if ($courses): ?>
  <div class="grid-cards">
    <?php foreach ($courses as $c): ?>
      <div class="course-card">
        <h3><?= e($c['title']) ?></h3>
        <div class="meta">
          <span><?= e($c['level']) ?></span>
          <span><?= e($c['duration']) ?></span>
          <span><?= e($c['batch_timing']) ?></span>
        </div>
        <p><?= nl2br(e($c['description'] ?? '')) ?></p>
        <div class="fee"><?= e($c['fee']) ?></div>
        <a class="button" href="/register.php?course=<?= (int)$c['id'] ?>">Register <b>→</b></a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>Courses will appear here once added from the admin panel.</p>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
