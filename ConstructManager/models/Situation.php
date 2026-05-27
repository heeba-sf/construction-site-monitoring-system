<?php
class Situation {
    private $conn;
    private $table_name = "Situation";

    public $Situation_id;
    public $Project_id;
    public $Comments;
    public $Start_date;
    public $End_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT s.*, p.Project_name 
                  FROM " . $this->table_name . " s
                  LEFT JOIN Project p ON s.Project_id = p.Project_id
                  ORDER BY s.Start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Situation_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Situation_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->Project_id = $row['Project_id'];
            $this->Comments = $row['Comments'];
            $this->Start_date = $row['Start_date'];
            $this->End_date = $row['End_date'];
            return true;
        }
        return false;
    }

    public function create($achievements = []) {
        $query = "SELECT MAX(Situation_id) as max_id FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->Situation_id = ($row['max_id'] ?? 0) + 1;
        
        $this->conn->beginTransaction();
        
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (Situation_id, Project_id, Comments, Start_date, End_date) 
                      VALUES (:Situation_id, :Project_id, :Comments, :Start_date, :End_date)";
            $stmt = $this->conn->prepare($query);
            
            $this->Project_id = htmlspecialchars(strip_tags($this->Project_id));
            $this->Comments = htmlspecialchars(strip_tags($this->Comments));
            
            $stmt->bindParam(":Situation_id", $this->Situation_id);
            $stmt->bindParam(":Project_id", $this->Project_id);
            $stmt->bindParam(":Comments", $this->Comments);
            $stmt->bindParam(":Start_date", $this->Start_date);
            $stmt->bindParam(":End_date", $this->End_date);
            $stmt->execute();
            
            // Insert achievements
            if(!empty($achievements)) {
                foreach($achievements as $work_id => $quantity) {
                    if($quantity > 0) {
                        $query = "INSERT INTO Achieve (Situation_id, WorkP_id, Achieved_quantity) 
                                  VALUES (:Situation_id, :WorkP_id, :Achieved_quantity)";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bindParam(":Situation_id", $this->Situation_id);
                        $stmt->bindParam(":WorkP_id", $work_id);
                        $stmt->bindParam(":Achieved_quantity", $quantity);
                        $stmt->execute();
                    }
                }
            }
            
            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET Project_id=:Project_id, Comments=:Comments, 
                      Start_date=:Start_date, End_date=:End_date 
                  WHERE Situation_id=:Situation_id";
        $stmt = $this->conn->prepare($query);
        
        $this->Project_id = htmlspecialchars(strip_tags($this->Project_id));
        $this->Comments = htmlspecialchars(strip_tags($this->Comments));
        $this->Situation_id = htmlspecialchars(strip_tags($this->Situation_id));
        
        $stmt->bindParam(":Project_id", $this->Project_id);
        $stmt->bindParam(":Comments", $this->Comments);
        $stmt->bindParam(":Start_date", $this->Start_date);
        $stmt->bindParam(":End_date", $this->End_date);
        $stmt->bindParam(":Situation_id", $this->Situation_id);
        
        return $stmt->execute();
    }

public function delete() {
        $this->Situation_id = htmlspecialchars(strip_tags($this->Situation_id));
        
        $delete_achieve = "DELETE FROM Achieve WHERE Situation_id = ?";
        $stmt_achieve = $this->conn->prepare($delete_achieve);
        $stmt_achieve->bindParam(1, $this->Situation_id);
        $stmt_achieve->execute();
        
        $query = "DELETE FROM " . $this->table_name . " WHERE Situation_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Situation_id);
        return $stmt->execute();
    }

    public function getSituationsByProject() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE Project_id = ? 
                  ORDER BY Start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Project_id);
        $stmt->execute();
        return $stmt;
    }

    public function getSituationDetails() {
        $query = "SELECT s.*, p.Project_name, c.First_name, c.Last_name,
                         COUNT(DISTINCT a.WorkP_id) as work_count,
                         COALESCE(SUM(wp.Unit_price * a.Achieved_quantity), 0) as total_cost
                  FROM " . $this->table_name . " s
                  JOIN Project p ON s.Project_id = p.Project_id
                  JOIN Client c ON p.Client_id = c.Client_id
                  LEFT JOIN Achieve a ON s.Situation_id = a.Situation_id
                  LEFT JOIN Work_P wp ON a.WorkP_id = wp.WorkP_id
                  WHERE s.Situation_id = ?
                  GROUP BY s.Situation_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Situation_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function getSituationWorks() {
        $query = "SELECT wp.*, a.Achieved_quantity, a.Situation_id, a.WorkP_id,
                         (wp.Unit_price * a.Achieved_quantity) as line_total
                  FROM Achieve a
                  JOIN Work_P wp ON a.WorkP_id = wp.WorkP_id
                  WHERE a.Situation_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Situation_id);
        $stmt->execute();
        return $stmt;
    }

    public function getAvailableWorks() {
        $query = "SELECT p.SD_id FROM Project p 
                  JOIN Situation s ON p.Project_id = s.Project_id 
                  WHERE s.Situation_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Situation_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$row || !$row['SD_id']) {
            return [];
        }
        
        $query = "SELECT wp.* FROM Work_P wp 
                  WHERE wp.SD_id = ? 
                  AND wp.WorkP_id NOT IN (
                      SELECT WorkP_id FROM Achieve WHERE Situation_id = ?
                  )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $row['SD_id']);
        $stmt->bindParam(2, $this->Situation_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addWorkAchievement($workP_id, $achieved_quantity) {
        $query = "INSERT INTO Achieve (Situation_id, WorkP_id, Achieved_quantity) 
                  VALUES (:Situation_id, :WorkP_id, :Achieved_quantity)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Situation_id", $this->Situation_id);
        $stmt->bindParam(":WorkP_id", $workP_id);
        $stmt->bindParam(":Achieved_quantity", $achieved_quantity);
        return $stmt->execute();
    }

    public function updateWorkAchievement($workP_id, $achieved_quantity) {
        $query = "UPDATE Achieve SET Achieved_quantity = :Achieved_quantity 
                  WHERE WorkP_id = :WorkP_id AND Situation_id = :Situation_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":WorkP_id", $workP_id);
        $stmt->bindParam(":Achieved_quantity", $achieved_quantity);
        $stmt->bindParam(":Situation_id", $this->Situation_id);
        return $stmt->execute();
    }

    public function deleteWorkAchievement($workP_id) {
        $query = "DELETE FROM Achieve WHERE WorkP_id = ? AND Situation_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $workP_id);
        $stmt->bindParam(2, $this->Situation_id);
        return $stmt->execute();
    }

    public function search($search = '') {
        $query = "SELECT s.*, p.Project_name 
                  FROM " . $this->table_name . " s
                  LEFT JOIN Project p ON s.Project_id = p.Project_id
                  WHERE 1=1";
        
        $params = [];
        
        if(!empty($search)) {
            $query .= " AND (s.Comments LIKE ? OR p.Project_name LIKE ?)";
            $search_param = "%{$search}%";
            $params[] = $search_param;
            $params[] = $search_param;
        }
        
        $query .= " ORDER BY s.Start_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>