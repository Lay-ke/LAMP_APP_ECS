<?php
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the table 'records' exists, if not, create it
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'records'")->rowCount();
    if ($tableCheck == 0) {
        // Create the table if it doesn't exist
        $sql = "
            CREATE TABLE IF NOT EXISTS records (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                date_created DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $pdo->exec($sql);
        // echo "Table 'records' created successfully.\n";
    }
} catch(PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
}
?>