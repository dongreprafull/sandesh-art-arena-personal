<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Portfolio';
$cat = $_GET['category'] ?? '';
$pdo = db();
if ($cat !== '') {
    $stmt = $pdo->prepare("SELECT * FROM portfolio_items WHERE category = ? ORDER BY sort_order DESC");
    $stmt->execute([$cat]);
    $items = $stmt->fetchAll();
} else {
    $items = $pdo->query("SELECT * FROM portfolio_items ORDER BY sort_order DESC")->fetchAll();
}
$cats = $pdo->query("SELECT DISTINCT category FROM portfolio_items WHERE category IS NOT NULL AND category <> '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">Work</p>
  <h1>Portfolio & <em>Projects</em></h1>
  <?php if ($cats): ?>
  <p style="margin-top:1rem">
    <a href="/portfolio.php" style="margin-right:1rem;<?= $cat==='' ? 'font-weight:700' : '' ?>">All</a>
    <?php foreach ($cats as $c): ?>
      <a href="/portfolio.php?category=<?= urlencode($c) ?>" style="margin-right:1rem;<?= $cat===$c ? 'font-weight:700' : '' ?>"><?= e($c) ?></a>
    <?php endforeach; ?>
  </p>
  <?php endif; ?>
</section>
<section class="section-pad">
  <?php if ($items): ?>
  <div class="grid-cards">
    <?php foreach ($items as $p): ?>
      <div class="tile">
        <?php if ($p['image']): ?><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>"><?php endif; ?>
        <div class="tile-body">
          <?php if ($p['category']): ?><span class="tag"><?= e($p['category']) ?></span><?php endif; ?>
          <h3><?= e($p['title']) ?></h3>
          <p><?= nl2br(e($p['description'] ?? '')) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>No portfolio items yet — add some from the admin panel.</p>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
