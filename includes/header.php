<?php
require_once __DIR__ . '/../config.php';
$current = basename($_SERVER['PHP_SELF']);
$nav = [
    'index.php' => 'Home',
    'about.php' => 'About',
    'services.php' => 'Studio & Services',
    'school-development.php' => 'School Development',
    'portfolio.php' => 'Portfolio',
    'gallery.php' => 'Gallery',
    'centres.php' => 'Learning Centres',
    'classes.php' => 'Courses & Classes',
];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — Sandesh Suradkar' : 'Sandesh Suradkar — Art Studio & Classes' ?></title>
<meta name="description" content="<?= e($pageDescription ?? 'Sandesh Suradkar — artist, art studio, classes and school development projects. Part of Sandesh Art Arena.') ?>">
<link rel="stylesheet" href="https://artarenastudio.com/assets/style.css">
<link rel="stylesheet" href="https://artarenastudio.com/assets/artful-theme.css">
<link rel="stylesheet" href="/assets/personal.css">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="header">
  <a class="brand" href="/index.php">
    <img src="https://artarenastudio.com/assets/sandesh-art-arena-logo.jpg" alt="Sandesh Art Arena">
    <i>Sandesh Suradkar</i>
  </a>
  <nav id="siteNav">
    <?php foreach ($nav as $file => $label): ?>
      <a href="/<?= $file ?>" class="<?= $current === $file ? 'active' : '' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="/contact.php" class="nav-cta">Contact</a>
  </nav>
  <button class="menu-button" id="menuBtn" aria-label="Menu">Menu</button>
</header>
<main id="main">
