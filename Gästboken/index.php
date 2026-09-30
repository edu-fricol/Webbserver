<?php
$file = __DIR__ . '/gästbok.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($name !== '' && $comment !== '') {
       
        $comment = str_replace(["\r\n", "\n", "\r"], ' <br> ', $comment);
        $time    = date('Y-m-d H:i');
        $row = $time . '|' . str_replace('|', '', $name) . '|' . str_replace('|', '', $comment) . "\n";
        file_put_contents($file, $row, FILE_APPEND | LOCK_EX);
    }

    header('Location: index.php');
    exit;
}

$rows = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$rows = array_reverse($rows);
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <title>Gästbok</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
  <h1>Gästbok</h1>

  <form method="post">
    <label>Namn
      <input type="text" name="name" maxlength="60" required>
    </label>
    <label>Inlägg
      <textarea name="comment" rows="4" maxlength="500" required></textarea>
    </label>
    <button type="submit">Skicka</button>
  </form>