<?php
class Document {
    private $conn;
    private $table_name = "Document";

    public $Document_id;
    public $Project_id;
    public $Document_type;
    public $Document_url;
    public $Upload_date;
    public $Authorization_level;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT d.*, p.Project_name 
                  FROM " . $this->table_name . " d
                  LEFT JOIN Project p ON d.Project_id = p.Project_id
                  ORDER BY d.Upload_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Document_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Document_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->Project_id = $row['Project_id'];
            $this->Document_type = $row['Document_type'];
            $this->Document_url = $row['Document_url'];
            $this->Upload_date = $row['Upload_date'];
            $this->Authorization_level = $row['Authorization_level'];
            return true;
        }
        return false;
    }

    public function create($file = null) {
        $query = "SELECT MAX(Document_id) as max_id FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->Document_id = ($row['max_id'] ?? 0) + 1;
        
        $url = $this->Document_url;
        if($file && $file['error'] == 0) {
            $target_dir = "uploads/";
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $filename;
            
            if(move_uploaded_file($file['tmp_name'], $target_file)) {
                $url = $target_file;
            }
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (Document_id, Project_id, Document_type, Document_url, Upload_date, Authorization_level) 
                  VALUES (:Document_id, :Project_id, :Document_type, :Document_url, :Upload_date, :Authorization_level)";
        $stmt = $this->conn->prepare($query);
        
        $this->Project_id = htmlspecialchars(strip_tags($this->Project_id));
        $this->Document_type = htmlspecialchars(strip_tags($this->Document_type));
        $this->Authorization_level = htmlspecialchars(strip_tags($this->Authorization_level));
        $this->Upload_date = date('Y-m-d');
        
        $stmt->bindParam(":Document_id", $this->Document_id);
        $stmt->bindParam(":Project_id", $this->Project_id);
        $stmt->bindParam(":Document_type", $this->Document_type);
        $stmt->bindParam(":Document_url", $url);
        $stmt->bindParam(":Upload_date", $this->Upload_date);
        $stmt->bindParam(":Authorization_level", $this->Authorization_level);
        
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET Project_id=:Project_id, Document_type=:Document_type, 
                      Document_url=:Document_url, Authorization_level=:Authorization_level 
                  WHERE Document_id=:Document_id";
        $stmt = $this->conn->prepare($query);
        
        $this->Project_id = htmlspecialchars(strip_tags($this->Project_id));
        $this->Document_type = htmlspecialchars(strip_tags($this->Document_type));
        $this->Document_url = htmlspecialchars(strip_tags($this->Document_url));
        $this->Authorization_level = htmlspecialchars(strip_tags($this->Authorization_level));
        $this->Document_id = htmlspecialchars(strip_tags($this->Document_id));
        
        $stmt->bindParam(":Project_id", $this->Project_id);
        $stmt->bindParam(":Document_type", $this->Document_type);
        $stmt->bindParam(":Document_url", $this->Document_url);
        $stmt->bindParam(":Authorization_level", $this->Authorization_level);
        $stmt->bindParam(":Document_id", $this->Document_id);
        
        return $stmt->execute();
    }

    public function delete() {
        $this->readOne();
        if($this->Document_url && file_exists($this->Document_url)) {
            unlink($this->Document_url);
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE Document_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->Document_id = htmlspecialchars(strip_tags($this->Document_id));
        $stmt->bindParam(1, $this->Document_id);
        return $stmt->execute();
    }

    public function getDocumentsByProject() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE Project_id = ? 
                  ORDER BY Upload_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Project_id);
        $stmt->execute();
        return $stmt;
    }

    public function getDocumentStats() {
        $query = "SELECT Document_type, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  GROUP BY Document_type";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>