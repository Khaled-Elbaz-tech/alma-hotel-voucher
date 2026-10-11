<?php
// backup_sqlite.php
$pdo = new PDO('sqlite:database/database.sqlite');
$tables = $pdo->query(
    "SELECT name FROM sqlite_master WHERE type='table' 
     AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'migrations'"
)->fetchAll(PDO::FETCH_COLUMN);

$output = '';
foreach ($tables as $table) {
    $create = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='$table'")->fetchColumn();
    $create = preg_replace('/INTEGER PRIMARY KEY AUTOINCREMENT/', 'INT AUTO_INCREMENT PRIMARY KEY', $create);
    $create = str_replace('"', '`', $create);
    $output .= $create . ";\n";

    $rows = $pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $values = [];
        foreach ($row as $val) {
            if ($val === null) $values[] = 'NULL';
            else $values[] = "'" . addslashes($val) . "'";
        }
        $output .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
    }
}

file_put_contents('mysql_backup.sql', $output);
echo "Backup created: mysql_backup.sql\n";