<?php
class DatabaseConnector
{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $connection;

    public function __construct(
        $databaseName,
        $serverAddress = 'localhost',
        $userName = 'root',
        $userPassword = '',
        $serverPort = 4000 // Порт для підключення
    ) {
        $this->server = $serverAddress;
        $this->user = $userName;
        $this->password = $userPassword;
        $this->database = $databaseName;
        $this->port = $serverPort;
    }
    
    public function connect(): bool
    {
        $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        return !$this->connection->connect_error;
    }
    
    public function disconnect(): bool
    {
        if ($this->connection) {
            $this->connection->close();
            return true;
        }
        return false;
    }
    
    public function read(string $query): ?array
    {
        $result = $this->connection->query($query);
        if ($result) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function readAll(string $query): ?array
    {
        $result = $this->connection->query($query);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return null;
    }
    
    public function readOne(string $query, array $params = []): ?array
    {
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        }
        $stmt->close();
        return null;
    }
    
    public function insert(string $table, string $columns, string $values): bool
    {
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->connection->query($query);
    }
    
    public function delete(string $table, string $where): bool
    {
        $query = "DELETE FROM $table WHERE $where";
        return $this->connection->query($query);
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function change(string $table, array $data, string $condition): bool
    {
        if (empty($data)) {
            return false;
        }
        $setData = array_map(function ($column, $value) {
            return "$column = '" . $this->connection->real_escape_string($value) . "'";
        }, array_keys($data), array_values($data));
        $setData = implode(", ", $setData);
        $query = "UPDATE $table SET $setData WHERE $condition";
        return $this->connection->query($query);
    }
}
?>
