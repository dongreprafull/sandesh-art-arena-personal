<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'School Development';
$projects = db()->query("SELECT * FROM school_projects ORDER BY sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
  <p class="eyebrow">School Programmes</p>
  <h1>School <em>Development</em></h1>
  <p>Art curriculum design, mural and campus-art projects, and teacher training delivered to schools.</p>
</section>
<section class="section-pad">
  <?php if ($projects): ?>
  <div class="grid-cards">
    <?php foreach ($projects as $p): ?>
      <div class="tile">
        <?php if ($p['image']): ?><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>"><?php endif; ?>
        <div class="tile-body">
          <h3><?= e($p['title']) ?></h3>
          <p><?= nl2br(e($p['description'] ?? '')) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>School project case studies will appear here once added from the admin panel.</p>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
