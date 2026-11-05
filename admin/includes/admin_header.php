<?php
require_once __DIR__ . '/../../config.php';
require_admin();
$u = current_user();
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — Admin' : 'Admin' ?></title>
<link rel="stylesheet" href="/assets/personal.css">
<style>body{background:#f5f1ea;font-family:'DM Sans',Arial,sans-serif}</style>
</head>
<body>
<div class="admin-bar">
  <div><strong>Sandesh Personal — Admin</strong></div>
  <nav>
    <a href="/admin/dashboard.php">Dashboard</a>
    <a href="/admin/registrations.php">Registrations</a>
    <a href="/admin/enquiries.php">Enquiries</a>
    <a href="/admin/content.php?table=courses">Courses</a>
    <a href="/admin/content.php?table=portfolio_items">Portfolio</a>
    <a href="/admin/content.php?table=gallery_items">Gallery</a>
    <a href="/admin/content.php?table=services">Services</a>
    <a href="/admin/content.php?table=school_projects">School Projects</a>
    <a href="/admin/content.php?table=centres">Centres</a>
    <a href="/admin/logout.php">Logout (<?= e($u['name'] ?? '') ?>)</a>
  </nav>
</div>
<div class="admin-wrap">
