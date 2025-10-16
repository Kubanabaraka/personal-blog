<?php
// connection.php
// Handles the database connection for the InspireHub Blog application using mysqli.

// Enable detailed mysqli error reporting for easier debugging during development.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database credentials. Update these constants if your local setup differs.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_db');

/**
 * Creates and returns a mysqli connection.
 *
 * @return mysqli
 */
function get_db_connection(): mysqli
{
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $connection->set_charset('utf8mb4');

    return $connection;
}
?>
