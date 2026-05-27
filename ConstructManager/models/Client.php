<?php
class Client {
    private $conn;
    private $table_name = "Client";

    public $Client_id;
    public $First_name;
    public $Last_name;
    public $Address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT c.*, COUNT(p.Project_id) as project_count 
                  FROM " . $this->table_name . " c
                  LEFT JOIN Project p ON c.Client_id = p.Client_id
                  GROUP BY c.Client_id
                  ORDER BY c.Client_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Client_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Client_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->First_name = $row['First_name'];
            $this->Last_name = $row['Last_name'];
            $this->Address = $row['Address'];
            return true;
        }
        return false;
    }

    public function create() {
        // Get next ID manually since no auto_increment
        $query = "SELECT MAX(Client_id) as max_id FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->Client_id = ($row['max_id'] ?? 0) + 1;
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (Client_id, First_name, Last_name, Address) 
                  VALUES (:Client_id, :First_name, :Last_name, :Address)";
        $stmt = $this->conn->prepare($query);
        
        $this->First_name = htmlspecialchars(strip_tags($this->First_name));
        $this->Last_name = htmlspecialchars(strip_tags($this->Last_name));
        $this->Address = htmlspecialchars(strip_tags($this->Address));
        
        $stmt->bindParam(":Client_id", $this->Client_id);
        $stmt->bindParam(":First_name", $this->First_name);
        $stmt->bindParam(":Last_name", $this->Last_name);
        $stmt->bindParam(":Address", $this->Address);
        
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET First_name=:First_name, Last_name=:Last_name, Address=:Address 
                  WHERE Client_id=:Client_id";
        $stmt = $this->conn->prepare($query);
        
        $this->First_name = htmlspecialchars(strip_tags($this->First_name));
        $this->Last_name = htmlspecialchars(strip_tags($this->Last_name));
        $this->Address = htmlspecialchars(strip_tags($this->Address));
        $this->Client_id = htmlspecialchars(strip_tags($this->Client_id));
        
        $stmt->bindParam(":First_name", $this->First_name);
        $stmt->bindParam(":Last_name", $this->Last_name);
        $stmt->bindParam(":Address", $this->Address);
        $stmt->bindParam(":Client_id", $this->Client_id);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE Client_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->Client_id = htmlspecialchars(strip_tags($this->Client_id));
        $stmt->bindParam(1, $this->Client_id);
        return $stmt->execute();
    }

    public function hasProjects() {
        $query = "SELECT COUNT(*) as count FROM Project WHERE Client_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Client_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    public function getClientProjects() {
        $query = "SELECT p.* FROM Project p WHERE p.Client_id = ? ORDER BY p.Project_start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Client_id);
        $stmt->execute();
        return $stmt;
    }

public function getAllClients() {
        $query = "SELECT Client_id, First_name, Last_name FROM " . $this->table_name . " ORDER BY Last_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function search($search = '') {
        $query = "SELECT c.*, COUNT(p.Project_id) as project_count 
                  FROM " . $this->table_name . " c
                  LEFT JOIN Project p ON c.Client_id = p.Client_id
                  WHERE c.First_name LIKE ? OR c.Last_name LIKE ? OR c.Address LIKE ?
                  GROUP BY c.Client_id
                  ORDER BY c.Client_id";
        $search_param = "%{$search}%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$search_param, $search_param, $search_param]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>