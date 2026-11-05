<?php
/**
 * One-time: ready-made sample content so the front end isn't empty.
 * Visit once, confirm output, then this file deletes itself.
 */
require __DIR__ . '/config.php';
header('Content-Type: text/plain');
$pdo = db();

$img1 = 'https://artarenastudio.com/assets/three-sectors-artwork.png';
$img2 = 'https://artarenastudio.com/assets/three-sectors-artwork-v2.png';
$img3 = 'https://artarenastudio.com/assets/three-sectors-artwork-v3.png';

function seedIfEmpty(PDO $pdo, string $table, string $insertSql, array $rows): void {
    $count = $pdo->query("SELECT COUNT(*) c FROM `$table`")->fetch()['c'];
    if ($count > 0) {
        echo "$table: already has data, skipped.\n";
        return;
    }
    $stmt = $pdo->prepare($insertSql);
    foreach ($rows as $r) {
        $stmt->execute($r);
    }
    echo "$table: " . count($rows) . " rows added.\n";
}

seedIfEmpty($pdo, 'services',
    "INSERT INTO services (title, description, price, image, sort_order) VALUES (?, ?, ?, ?, ?)",
    [
        ['Custom Portrait Commission', 'Hand-painted portraits in oil or acrylic, from reference photos — a single subject or a full family piece.', 'From ₹6,000', $img1, 1],
        ['Mural Painting', 'Wall murals for homes, cafes and offices — concept sketch, colour approval, then on-site painting.', 'Quoted per wall', $img2, 2],
        ['Illustration & Book Covers', 'Illustration work for books, magazines and brand storytelling.', 'From ₹3,500', $img3, 3],
    ]
);

seedIfEmpty($pdo, 'school_projects',
    "INSERT INTO school_projects (title, description, image, sort_order) VALUES (?, ?, ?, ?)",
    [
        ['Campus Mural Programme — Zilla Parishad School', 'Designed and painted a learning-themed mural across 3 classroom corridors with student volunteers.', $img2, 1],
        ['Art Curriculum Redesign', 'Built a term-wise visual-arts curriculum for grades 3–8, with teacher training sessions.', $img3, 2],
    ]
);

seedIfEmpty($pdo, 'portfolio_items',
    "INSERT INTO portfolio_items (title, category, description, image, sort_order) VALUES (?, ?, ?, ?, ?)",
    [
        ['Monsoon Series — Watercolour Study', 'Painting', 'A 5-piece watercolour series exploring Pune\'s monsoon light.', $img1, 1],
        ['Family Portrait Commission', 'Portrait', 'Oil on canvas, commissioned family portrait, 24x30 inches.', $img2, 2],
        ['Zilla Parishad School Mural', 'Mural', 'Corridor mural painted with student volunteers over 2 weeks.', $img3, 3],
        ['Brand Illustration Set', 'Illustration', 'A set of character illustrations for a children\'s book.', $img1, 4],
    ]
);

seedIfEmpty($pdo, 'gallery_items',
    "INSERT INTO gallery_items (title, item_type, url, album, sort_order) VALUES (?, ?, ?, ?, ?)",
    [
        ['Studio workspace', 'photo', $img1, 'Studio', 1],
        ['Weekend batch in session', 'photo', $img2, 'Classes', 2],
        ['Finished mural — handover day', 'photo', $img3, 'School Projects', 3],
        ['Watercolour demo', 'photo', $img1, 'Studio', 4],
    ]
);

$count = $pdo->query("SELECT COUNT(*) c FROM centres")->fetch()['c'];
if ($count <= 1) {
    $pdo->exec("INSERT INTO centres (name, address, city, phone, sort_order) VALUES
      ('Sandesh Art Arena — Kothrud Centre', 'Add exact address here', 'Pune', '+91-00000-00001', 2)");
    echo "centres: 1 more row added.\n";
} else {
    echo "centres: already has multiple rows, skipped.\n";
}

echo "\nDone.\n";

// Self-delete after running once.
@unlink(__FILE__);
echo "seed2.php deleted itself.\n";
