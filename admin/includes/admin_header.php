<?php
require_once __DIR__ . '/../../config.php';
require_admin();
$u = current_user();
$current = basename($_SERVER['PHP_SELF']);
$table = $_GET['table'] ?? '';

$navGroups = [
    'Overview' => ['dashboard.php' => 'Dashboard'],
    'Enquiries' => [
        'registrations.php' => 'Class Registrations',
        'enquiries.php' => 'Contact Enquiries',
    ],
    'Content' => [
        'content.php?table=courses' => 'Courses & Classes',
        'content.php?table=portfolio_items' => 'Portfolio',
        'content.php?table=gallery_items' => 'Gallery',
        'content.php?table=services' => 'Studio & Services',
        'content.php?table=school_projects' => 'School Projects',
        'content.php?table=centres' => 'Learning Centres',
    ],
];
function nav_is_active(string $href, string $current, string $table): bool {
    $hrefFile = strtok($href, '?');
    if ($hrefFile !== $current) return false;
    if (strpos($href, 'table=') !== false) {
        parse_str((string)parse_url($href, PHP_URL_QUERY), $q);
        return ($q['table'] ?? '') === $table;
    }
    return true;
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — Admin' : 'Admin' ?></title>
<link rel="stylesheet" href="https://artarenastudio.com/assets/style.css">
<link rel="stylesheet" href="https://artarenastudio.com/assets/artful-theme.css">
<link rel="stylesheet" href="/assets/personal.css">
</head>
<body class="admin-body">
<header class="admin-topbar">
  <a class="brand" href="/admin/dashboard.php">
    <img src="https://artarenastudio.com/assets/sandesh-art-arena-logo.jpg" alt="Sandesh Art Arena">
    <i>Personal Studio · Admin</i>
  </a>
  <div class="admin-topbar-right">
    <a class="btn ghost" href="/" target="_blank" rel="noopener">View Site ↗</a>
    <a class="btn primary" href="/admin/logout.php">Logout (<?= e($u['name'] ?? '') ?>)</a>
  </div>
</header>
<div class="admin-shell">
  <nav class="admin-side">
    <?php foreach ($navGroups as $group => $links): ?>
      <p class="admin-side-group"><?= e($group) ?></p>
      <?php foreach ($links as $href => $label): ?>
        <a href="/admin/<?= $href ?>" class="<?= nav_is_active($href, $current, $table) ? 'active' : '' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </nav>
  <div class="admin-wrap">
