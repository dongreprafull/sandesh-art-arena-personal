<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Art Learning Centres';
$centres = db()->query("SELECT * FROM centres ORDER BY sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">Locations</p>
  <h1>Art Learning <em>Centres</em></h1>
</section>
<section class="section-pad">
  <?php if ($centres): ?>
  <div class="grid-cards">
    <?php foreach ($centres as $c): ?>
      <div class="centre-card">
        <h3><?= e($c['name']) ?></h3>
        <?php if ($c['address']): ?><p><?= e($c['address']) ?></p><?php endif; ?>
        <?php if ($c['city']): ?><p><?= e($c['city']) ?></p><?php endif; ?>
        <?php if ($c['phone']): ?><p><?= e($c['phone']) ?></p><?php endif; ?>
        <?php if ($c['map_url']): ?><p><a href="<?= e($c['map_url']) ?>" target="_blank" rel="noopener">View on map →</a></p><?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>Centre locations will appear here once added from the admin panel.</p>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
