<?php
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'kaya_tene';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Connection
{
    private $host = "localhost";
    private $db_name = "mydb";
    private $username
    = "";
    private $password
    = "root";
    private $database = "";
    private $port = 0;
    private $socket = null;
    public function __construct($host, $db_name, $username, $password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
    }
    public function connect($host, $db_name, $username, $password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
        try {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
            return null;
        }
    }
}
?>