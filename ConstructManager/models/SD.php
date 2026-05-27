<?php
class SD {
    private $conn;
    private $table_name = "SD";

    public $SD_id;
    public $date_creation;
    public $Version;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all SDs with linked project info
    public function read() {
        $query = "SELECT s.*, p.Project_id, p.Project_name,
                         (SELECT COUNT(*) FROM Work_P wp WHERE wp.SD_id = s.SD_id) as work_count
                  FROM " . $this->table_name . " s
                  LEFT JOIN Project p ON p.SD_id = s.SD_id
                  ORDER BY s.SD_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read one SD
    public function readOne() {
        $query = "SELECT s.*, p.Project_id, p.Project_name, p.Budget, p.Client_id,
                         c.First_name, c.Last_name,
                         (SELECT COUNT(*) FROM Work_P wp WHERE wp.SD_id = s.SD_id) as work_count
                  FROM " . $this->table_name . " s
                  LEFT JOIN Project p ON p.SD_id = s.SD_id
                  LEFT JOIN Client c ON c.Client_id = p.Client_id
                  WHERE s.SD_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->SD_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->date_creation = $row['date_creation'];
            $this->Version       = $row['Version'];
        }
        return $row;
    }

    // Get work packages for this SD
    public function getWorkPackages() {
        $query = "SELECT wp.*,
                         COALESCE(MAX(a.Achieved_quantity), 0) as total_achieved,
                         CASE WHEN wp.Expected_quantity > 0
                              THEN ROUND(COALESCE(MAX(a.Achieved_quantity), 0) / wp.Expected_quantity * 100, 1)
                              ELSE 0 END as completion_rate
                  FROM Work_P wp
                  LEFT JOIN Achieve a ON wp.WorkP_id = a.WorkP_id
                  WHERE wp.SD_id = ?
                  GROUP BY wp.WorkP_id
                  ORDER BY wp.WorkP_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->SD_id);
        $stmt->execute();
        return $stmt;
    }

    // Get total contract value for this SD
    public function getContractValue() {
        $query = "SELECT COALESCE(SUM(Expected_quantity * Unit_price), 0) as total
                  FROM Work_P WHERE SD_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->SD_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Create new SD (standalone, not tied to project creation)
    public function create() {
        $query = "SELECT MAX(SD_id) as max_id FROM " . $this->table_name;
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->SD_id = ($row['max_id'] ?? 0) + 1;

        $query = "INSERT INTO " . $this->table_name . " (SD_id, date_creation, Version)
                  VALUES (:SD_id, :date_creation, :Version)";
        $stmt = $this->conn->prepare($query);
        $this->Version       = htmlspecialchars(strip_tags($this->Version));
        $this->date_creation = htmlspecialchars(strip_tags($this->date_creation));
        $stmt->bindParam(":SD_id",         $this->SD_id);
        $stmt->bindParam(":date_creation", $this->date_creation);
        $stmt->bindParam(":Version",       $this->Version);
        return $stmt->execute();
    }

    // Update SD version and date
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET date_creation=:date_creation, Version=:Version
                  WHERE SD_id=:SD_id";
        $stmt = $this->conn->prepare($query);
        $this->Version       = htmlspecialchars(strip_tags($this->Version));
        $this->date_creation = htmlspecialchars(strip_tags($this->date_creation));
        $this->SD_id         = htmlspecialchars(strip_tags($this->SD_id));
        $stmt->bindParam(":date_creation", $this->date_creation);
        $stmt->bindParam(":Version",       $this->Version);
        $stmt->bindParam(":SD_id",         $this->SD_id);
        return $stmt->execute();
    }

    // Delete SD (and its work packages if not linked to a project)
    public function delete() {
        try {
            $this->conn->beginTransaction();
            // Remove achieve records for this SD's work packages
            $query = "DELETE a FROM Achieve a
                      JOIN Work_P wp ON a.WorkP_id = wp.WorkP_id
                      WHERE wp.SD_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->SD_id);
            $stmt->execute();
            // Nullify project reference
            $query = "UPDATE Project SET SD_id = NULL WHERE SD_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->SD_id);
            $stmt->execute();
            // Delete work packages
            $query = "DELETE FROM Work_P WHERE SD_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->SD_id);
            $stmt->execute();
            // Delete SD
            $query = "DELETE FROM " . $this->table_name . " WHERE SD_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->SD_id);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // All SDs that have no project yet (for assignment dropdowns)
    public function getUnassigned() {
        $query = "SELECT s.* FROM " . $this->table_name . " s
                  LEFT JOIN Project p ON p.SD_id = s.SD_id
                  WHERE p.Project_id IS NULL
                  ORDER BY s.SD_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // All SDs for dropdowns
    public function getAllForDropdown() {
        $query = "SELECT s.SD_id, s.Version, s.date_creation,
                         p.Project_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN Project p ON p.SD_id = s.SD_id
                  ORDER BY s.SD_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
