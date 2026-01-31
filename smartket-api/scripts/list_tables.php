<?php
// Usage: php scripts/list_tables.php <host> <port> <database> <user> <password>

if ($argc < 6) {
    fwrite(STDERR, "Usage: php scripts/list_tables.php <host> <port> <database> <user> <password>\n");
    exit(1);
}

$host = $argv[1];
$port = (int)$argv[2];
$db   = $argv[3];
$user = $argv[4];
$pass = $argv[5];

$dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $host, $port, $db);

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = "SELECT table_schema, table_name
            FROM information_schema.tables
            WHERE table_type = 'BASE TABLE'
              AND table_schema NOT IN ('pg_catalog','information_schema')
            ORDER BY table_schema, table_name";

    $stmt = $pdo->query($sql);
    foreach ($stmt as $row) {
        echo $row['table_schema'] . '|' . $row['table_name'] . PHP_EOL;
    }
} catch (PDOException $e) {
    fwrite(STDERR, 'Connection/Query error: ' . $e->getMessage() . "\n");
    exit(1);
}

