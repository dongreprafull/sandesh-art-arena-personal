<?php
$pageTitle = 'Content';
require_once __DIR__ . '/includes/admin_header.php';
$pdo = db();

// Whitelisted tables and their editable fields.
$tables = [
    'services' => [
        'label' => 'Studio & Services',
        'fields' => [
            'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
            'description' => ['label' => 'Description', 'type' => 'textarea'],
            'price' => ['label' => 'Price', 'type' => 'text'],
            'image' => ['label' => 'Image', 'type' => 'image'],
            'sort_order' => ['label' => 'Sort Order', 'type' => 'number'],
        ],
    ],
    'school_projects' => [
        'label' => 'School Development Projects',
        'fields' => [
            'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
            'description' => ['label' => 'Description', 'type' => 'textarea'],
            'image' => ['label' => 'Image', 'type' => 'image'],
            'sort_order' => ['label' => 'Sort Order', 'type' => 'number'],
        ],
    ],
    'portfolio_items' => [
        'label' => 'Portfolio / Projects',
        'fields' => [
            'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
            'category' => ['label' => 'Category', 'type' => 'text'],
            'description' => ['label' => 'Description', 'type' => 'textarea'],
            'image' => ['label' => 'Image', 'type' => 'image'],
            'sort_order' => ['label' => 'Sort Order', 'type' => 'number'],
        ],
    ],
    'gallery_items' => [
        'label' => 'Photo & Video Gallery',
        'fields' => [
            'title' => ['label' => 'Title', 'type' => 'text'],
            'item_type' => ['label' => 'Type', 'type' => 'select', 'options' => ['photo' => 'Photo', 'video' => 'Video']],
            'url' => ['label' => 'Photo / Video File', 'type' => 'media'],
            'album' => ['label' => 'Album', 'type' => 'text'],
            'sort_order' => ['label' => 'Sort Order', 'type' => 'number'],
        ],
    ],
    'centres' => [
        'label' => 'Art Learning Centres',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text', 'required' => true],
            'address' => ['label' => 'Address', 'type' => 'text'],
            'city' => ['label' => 'City', 'type' => 'text'],
            'phone' => ['label' => 'Phone', 'type' => 'text'],
            'map_url' => ['label' => 'Map URL', 'type' => 'text'],
            'sort_order' => ['label' => 'Sort Order', 'type' => 'number'],
        ],
    ],
    'courses' => [
        'label' => 'Courses & Classes',
        'fields' => [
            'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
            'level' => ['label' => 'Level', 'type' => 'text'],
            'duration' => ['label' => 'Duration', 'type' => 'text'],
            'fee' => ['label' => 'Fee', 'type' => 'text'],
            'batch_timing' => ['label' => 'Batch Timing', 'type' => 'text'],
            'description' => ['label' => 'Description', 'type' => 'textarea'],
            'active' => ['label' => 'Active', 'type' => 'checkbox'],
            'sort_order' => ['label' => 'Sort Order', 'type' => 'number'],
        ],
    ],
];

$table = $_GET['table'] ?? '';
if (!isset($tables[$table])) {
    $table = 'courses';
}
$conf = $tables[$table];
$fields = $conf['fields'];

// ---- Handle write actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete' && !empty($_POST['id'])) {
        $pdo->prepare("DELETE FROM `$table` WHERE id = ?")->execute([(int)$_POST['id']]);
        header('Location: /admin/content.php?table=' . urlencode($table));
        exit;
    }

    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $cols = [];
        $vals = [];
        $existingRow = null;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
            $stmt->execute([$id]);
            $existingRow = $stmt->fetch();
        }
        foreach ($fields as $name => $f) {
            if ($f['type'] === 'checkbox') {
                $cols[] = $name;
                $vals[] = isset($_POST[$name]) ? 1 : 0;
            } elseif ($f['type'] === 'image') {
                $cols[] = $name;
                $vals[] = handle_upload($name, $existingRow[$name] ?? null, 'uploads/images');
            } elseif ($f['type'] === 'media') {
                $cols[] = $name;
                $vals[] = handle_upload($name, $existingRow[$name] ?? null, 'uploads/gallery', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'webm', 'mov']);
            } else {
                $v = trim($_POST[$name] ?? '');
                $cols[] = $name;
                $vals[] = $v === '' ? null : $v;
            }
        }
        if ($id > 0) {
            $set = implode(', ', array_map(fn($c) => "`$c` = ?", $cols));
            $pdo->prepare("UPDATE `$table` SET $set WHERE id = ?")->execute([...$vals, $id]);
        } else {
            $colList = implode(', ', array_map(fn($c) => "`$c`", $cols));
            $ph = implode(', ', array_fill(0, count($cols), '?'));
            $pdo->prepare("INSERT INTO `$table` ($colList) VALUES ($ph)")->execute($vals);
        }
        header('Location: /admin/content.php?table=' . urlencode($table));
        exit;
    }
}

// ---- Load list + optional edit row ----
$editId = (int)($_GET['edit'] ?? 0);
$editRow = null;
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->execute([$editId]);
    $editRow = $stmt->fetch();
}
$rows = $pdo->query("SELECT * FROM `$table` ORDER BY " . (isset($fields['sort_order']) ? 'sort_order' : 'id') . "")->fetchAll();
?>
<h1><?= e($conf['label']) ?></h1>

<h3><?= $editRow ? 'Edit' : 'Add New' ?></h3>
<form class="admin-form" method="post" enctype="multipart/form-data">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="id" value="<?= $editRow['id'] ?? 0 ?>">
  <?php foreach ($fields as $name => $f): $val = $editRow[$name] ?? ''; ?>
    <label>
      <?= e($f['label']) ?><?= !empty($f['required']) ? ' *' : '' ?><br>
      <?php if ($f['type'] === 'image' || $f['type'] === 'media'): ?>
        <?php if (!empty($val)): ?>
          <div style="margin:.3rem 0">
            <?php if ($f['type'] === 'media' && preg_match('/\.(mp4|webm|mov)$/i', $val)): ?>
              <video src="<?= e(img_url($val)) ?>" style="max-width:200px;border-radius:6px" controls></video>
            <?php else: ?>
              <img src="<?= e(img_url($val)) ?>" alt="" style="max-width:140px;border-radius:6px;display:block">
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <input type="file" name="<?= $name ?>" accept="<?= $f['type'] === 'media' ? 'image/*,video/*' : 'image/*' ?>">
        <p class="muted" style="margin-top:.25rem;font-size:.8rem"><?= $val ? 'Choose a new file to replace it, or leave blank to keep the current one.' : 'Upload a file.' ?></p>
      <?php elseif ($f['type'] === 'textarea'): ?>
        <textarea name="<?= $name ?>" rows="3"><?= e($val) ?></textarea>
      <?php elseif ($f['type'] === 'select'): ?>
        <select name="<?= $name ?>">
          <?php foreach ($f['options'] as $ov => $ol): ?>
            <option value="<?= e($ov) ?>" <?= $val === $ov ? 'selected' : '' ?>><?= e($ol) ?></option>
          <?php endforeach; ?>
        </select>
      <?php elseif ($f['type'] === 'checkbox'): ?>
        <input type="checkbox" name="<?= $name ?>" <?= $val ? 'checked' : ($editRow ? '' : 'checked') ?>>
      <?php else: ?>
        <input type="<?= $f['type'] === 'number' ? 'number' : 'text' ?>" name="<?= $name ?>" value="<?= e((string)$val) ?>" <?= !empty($f['required']) ? 'required' : '' ?>>
      <?php endif; ?>
    </label>
  <?php endforeach; ?>
  <div>
    <button class="btn primary" type="submit"><?= $editRow ? 'Save Changes' : 'Add' ?></button>
    <?php if ($editRow): ?><a class="btn ghost" href="/admin/content.php?table=<?= urlencode($table) ?>">Cancel</a><?php endif; ?>
  </div>
</form>

<div style="display:flex;justify-content:space-between;align-items:center;margin-top:2.2rem">
  <h3>All Entries (<?= count($rows) ?>)</h3>
</div>
<table class="admin-table">
  <tr>
    <?php foreach ($fields as $name => $f): ?><th><?= e($f['label']) ?></th><?php endforeach; ?>
    <th style="text-align:right">Actions</th>
  </tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <?php foreach ($fields as $name => $f): ?>
      <td>
        <?php if ($f['type'] === 'checkbox'): ?>
          <?= $r[$name] ? 'Yes' : 'No' ?>
        <?php elseif (($f['type'] === 'image' || $f['type'] === 'media') && !empty($r[$name])): ?>
          <?php if ($f['type'] === 'media' && preg_match('/\.(mp4|webm|mov)$/i', (string) $r[$name])): ?>
            <a href="<?= e(img_url($r[$name])) ?>" target="_blank">Video ↗</a>
          <?php else: ?>
            <img src="<?= e(img_url($r[$name])) ?>" alt="" style="width:44px;height:44px;object-fit:cover;border-radius:5px">
          <?php endif; ?>
        <?php else: ?>
          <?= e(mb_strimwidth((string)$r[$name], 0, 60, '…')) ?>
        <?php endif; ?>
      </td>
    <?php endforeach; ?>
    <td class="admin-actions" style="text-align:right;white-space:nowrap">
      <a class="btn ghost" style="padding:.3rem .7rem;font-size:.75rem" href="/admin/content.php?table=<?= urlencode($table) ?>&edit=<?= $r['id'] ?>">Edit</a>
      <form method="post" style="display:inline" onsubmit="return confirm('Delete this entry?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <button class="btn danger" type="submit" style="padding:.3rem .7rem;font-size:.75rem">Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="<?= count($fields) + 1 ?>">No entries yet.</td></tr><?php endif; ?>
</table>
<?php require __DIR__ . '/includes/admin_footer.php'; ?>
