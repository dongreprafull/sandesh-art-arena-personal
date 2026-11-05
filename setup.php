<?php
/**
 * One-time setup: creates tables and an admin login.
 * Visit this file once in the browser, confirm "Setup complete", then DELETE this file from the server.
 */
require __DIR__ . '/config.php';

header('Content-Type: text/plain');

$sql = file_get_contents(__DIR__ . '/schema.sql');
$pdo = db();

try {
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        $pdo->exec($stmt);
    }
    echo "Tables created/verified.\n";

    // Seed one admin user if none exists yet.
    $count = $pdo->query("SELECT COUNT(*) c FROM users WHERE is_admin = 1")->fetch()['c'];
    if ($count == 0) {
        $email = 'admin@artarenastudio.com';
        $password = 'ArtArena@2026';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (name, email, password_hash, role, is_admin) VALUES (?, ?, ?, 'admin', 1)")
            ->execute(['Site Admin', $email, $hash]);
        echo "Admin user created:\n  email: $email\n  password: $password\n  (change this password after first login)\n";
    } else {
        echo "Admin user already exists, skipped.\n";
    }

    // Seed a couple of sample courses/centre so pages aren't empty on first view.
    $count = $pdo->query("SELECT COUNT(*) c FROM courses")->fetch()['c'];
    if ($count == 0) {
        $pdo->exec("INSERT INTO courses (title, level, duration, fee, batch_timing, description, sort_order) VALUES
          ('Foundation Drawing & Sketching', 'Beginner', '8 weeks', '₹3,500', 'Sat & Sun, 10am–12pm', 'Line, form and shading fundamentals for complete beginners.', 1),
          ('Watercolour Painting', 'Intermediate', '10 weeks', '₹4,500', 'Tue & Thu, 5pm–7pm', 'Wash techniques, colour mixing and composition in watercolour.', 2)");
        echo "Sample courses added.\n";
    }

    $count = $pdo->query("SELECT COUNT(*) c FROM centres")->fetch()['c'];
    if ($count == 0) {
        $pdo->exec("INSERT INTO centres (name, address, city, phone, sort_order) VALUES
          ('Sandesh Art Arena — Main Studio', 'Add your studio address here', 'Pune', '+91-00000-00000', 1)");
        echo "Sample centre added.\n";
    }

    echo "\nSetup complete. Delete setup.php now.\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "Setup failed: " . $e->getMessage() . "\n";
}
