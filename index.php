<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Home';
$pdo = db();
$courses = $pdo->query("SELECT * FROM courses WHERE active = 1 ORDER BY sort_order LIMIT 3")->fetchAll();
$portfolio = $pdo->query("SELECT * FROM portfolio_items ORDER BY sort_order DESC LIMIT 3")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">Sandesh Art Arena · Personal Studio</p>
  <h1>Art, taught and made <em>by hand.</em></h1>
  <p>Sandesh Suradkar's personal studio — paintings, school art programmes, and classes for every age, in Pune.</p>
  <p style="margin-top:1.5rem">
    <a class="button" href="/classes.php">Explore Classes <b>→</b></a>
  </p>
</section>

<section class="section-pad">
  <h2 style="margin-bottom:1rem">Recent Work</h2>
  <?php if ($portfolio): ?>
  <div class="grid-cards">
    <?php foreach ($portfolio as $p): ?>
      <div class="tile">
        <img src="<?= e($p['image'] ?: 'https://artarenastudio.com/assets/three-sectors-artwork.png') ?>" alt="<?= e($p['title']) ?>">
        <div class="tile-body">
          <?php if ($p['category']): ?><span class="tag"><?= e($p['category']) ?></span><?php endif; ?>
          <h3><?= e($p['title']) ?></h3>
          <p><?= e(mb_strimwidth($p['description'] ?? '', 0, 90, '…')) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>Portfolio pieces will appear here once added from the admin panel.</p>
  <?php endif; ?>
  <p style="margin-top:1.2rem"><a href="/portfolio.php">View full portfolio →</a></p>
</section>

<section class="section-pad">
  <h2 style="margin-bottom:1rem">Current Courses</h2>
  <?php if ($courses): ?>
  <div class="grid-cards">
    <?php foreach ($courses as $c): ?>
      <div class="course-card">
        <h3><?= e($c['title']) ?></h3>
        <div class="meta">
          <span><?= e($c['level']) ?></span>
          <span><?= e($c['duration']) ?></span>
        </div>
        <p><?= e(mb_strimwidth($c['description'] ?? '', 0, 100, '…')) ?></p>
        <div class="fee"><?= e($c['fee']) ?></div>
        <a class="button" href="/register.php?course=<?= (int)$c['id'] ?>">Register <b>→</b></a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>Course listings will appear here once added from the admin panel.</p>
  <?php endif; ?>
  <p style="margin-top:1.2rem"><a href="/classes.php">See all courses →</a></p>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
