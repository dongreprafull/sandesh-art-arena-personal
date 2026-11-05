<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Studio & Services';
$services = db()->query("SELECT * FROM services ORDER BY sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">Studio</p>
  <h1>Art Studio & <em>Services</em></h1>
  <p>Commissioned paintings, murals, illustration and studio services.</p>
</section>
<section class="section-pad">
  <?php if ($services): ?>
  <div class="grid-cards">
    <?php foreach ($services as $s): ?>
      <div class="tile">
        <?php if ($s['image']): ?><img src="<?= e(img_url($s['image'])) ?>" alt="<?= e($s['title']) ?>"><?php endif; ?>
        <div class="tile-body">
          <h3><?= e($s['title']) ?></h3>
          <p><?= nl2br(e($s['description'] ?? '')) ?></p>
          <?php if ($s['price']): ?><p style="margin-top:.6rem;font-weight:700;color:var(--coral)"><?= e($s['price']) ?></p><?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>Services will appear here once added from the admin panel.</p>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
