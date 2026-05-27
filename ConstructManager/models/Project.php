<?php
class Project {
    private $conn;
    private $table_name = "Project";

    public $Project_id;
    public $Client_id;
    public $SD_id;
    public $Project_name;
    public $Budget;
    public $Project_start_date;
    public $Project_expected_end_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT p.*, c.First_name, c.Last_name, c.Address, s.Version 
                  FROM " . $this->table_name . " p
                  LEFT JOIN Client c ON p.Client_id = c.Client_id
                  LEFT JOIN SD s ON p.SD_id = s.SD_id
                  ORDER BY p.Project_start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT p.*, c.First_name, c.Last_name, c.Address, s.Version, s.date_creation 
                  FROM " . $this->table_name . " p
                  LEFT JOIN Client c ON p.Client_id = c.Client_id
                  LEFT JOIN SD s ON p.SD_id = s.SD_id
                  WHERE p.Project_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Project_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->Client_id = $row['Client_id'];
            $this->SD_id = $row['SD_id'];
            $this->Project_name = $row['Project_name'];
            $this->Budget = $row['Budget'];
            $this->Project_start_date = $row['Project_start_date'];
            $this->Project_expected_end_date = $row['Project_expected_end_date'];
            return $row;
        }
        return false;
    }

    public function create() {
        // Get next ID
        $query = "SELECT MAX(Project_id) as max_id FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->Project_id = ($row['max_id'] ?? 0) + 1;
        
        // Create SD first
        $sd_id = $this->createSD();
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (Project_id, Client_id, SD_id, Project_name, Budget, Project_start_date, Project_expected_end_date) 
                  VALUES (:Project_id, :Client_id, :SD_id, :Project_name, :Budget, :Project_start_date, :Project_expected_end_date)";
        $stmt = $this->conn->prepare($query);
        
        $this->Project_name = htmlspecialchars(strip_tags($this->Project_name));
        $this->Client_id = htmlspecialchars(strip_tags($this->Client_id));
        $this->Budget = htmlspecialchars(strip_tags($this->Budget));
        
        $stmt->bindParam(":Project_id", $this->Project_id);
        $stmt->bindParam(":Client_id", $this->Client_id);
        $stmt->bindParam(":SD_id", $sd_id);
        $stmt->bindParam(":Project_name", $this->Project_name);
        $stmt->bindParam(":Budget", $this->Budget);
        $stmt->bindParam(":Project_start_date", $this->Project_start_date);
        $stmt->bindParam(":Project_expected_end_date", $this->Project_expected_end_date);
        
        return $stmt->execute();
    }

    private function createSD() {
        $query = "SELECT MAX(SD_id) as max_id FROM SD";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $sd_id = ($row['max_id'] ?? 0) + 1;
        
        $version = 'v1.0';
        $date_creation = date('Y-m-d');
        
        $query = "INSERT INTO SD (SD_id, date_creation, Version) VALUES (:SD_id, :date_creation, :Version)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":SD_id", $sd_id);
        $stmt->bindParam(":date_creation", $date_creation);
        $stmt->bindParam(":Version", $version);
        $stmt->execute();
        
        return $sd_id;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET Client_id=:Client_id, Project_name=:Project_name, Budget=:Budget, 
                      Project_start_date=:Project_start_date, Project_expected_end_date=:Project_expected_end_date 
                  WHERE Project_id=:Project_id";
        $stmt = $this->conn->prepare($query);
        
        $this->Project_name = htmlspecialchars(strip_tags($this->Project_name));
        $this->Client_id = htmlspecialchars(strip_tags($this->Client_id));
        $this->Budget = htmlspecialchars(strip_tags($this->Budget));
        $this->Project_id = htmlspecialchars(strip_tags($this->Project_id));
        
        $stmt->bindParam(":Client_id", $this->Client_id);
        $stmt->bindParam(":Project_name", $this->Project_name);
        $stmt->bindParam(":Budget", $this->Budget);
        $stmt->bindParam(":Project_start_date", $this->Project_start_date);
        $stmt->bindParam(":Project_expected_end_date", $this->Project_expected_end_date);
        $stmt->bindParam(":Project_id", $this->Project_id);
        
        return $stmt->execute();
    }

public function delete() {
        // Get the project first to get SD_id
        $this->readOne();
        $sd_id = $this->SD_id;
        $project_id = $this->Project_id;
        
        try {
            // Start transaction
            $this->conn->beginTransaction();
            
            // 1. Delete Achieve records (through Situations)
            $query = "DELETE a FROM Achieve a 
                      JOIN Situation s ON a.Situation_id = s.Situation_id 
                      WHERE s.Project_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $project_id);
            $stmt->execute();
            
            // 2. Delete Situations
            $query = "DELETE FROM Situation WHERE Project_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $project_id);
            $stmt->execute();
            
            // 3. Delete Documents
            $query = "DELETE FROM Document WHERE Project_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $project_id);
            $stmt->execute();
            
            // 4. Delete Work_P (linked to SD)
            if($sd_id) {
                $query = "DELETE FROM Work_P WHERE SD_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $sd_id);
                $stmt->execute();
            }
            
            // 5. Delete Project FIRST (to remove SD reference)
            $query = "DELETE FROM " . $this->table_name . " WHERE Project_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $project_id);
            $stmt->execute();
            
            // 6. Finally delete SD (after Project is gone)
            if($sd_id) {
                $query = "DELETE FROM SD WHERE SD_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $sd_id);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

public function getProjectProgress() {
        $query = "SELECT 
                    COALESCE(AVG(CASE WHEN wp.Expected_quantity > 0 
                        THEN (COALESCE(total_achieved.achieved, 0) / wp.Expected_quantity) * 100 
                        ELSE 0 END), 0) as progress_percentage
                  FROM Project p
                  LEFT JOIN SD s ON p.SD_id = s.SD_id
                  LEFT JOIN Work_P wp ON s.SD_id = wp.SD_id
                  LEFT JOIN (
                      SELECT a.WorkP_id, MAX(a.Achieved_quantity) as achieved
                      FROM Achieve a
                      GROUP BY a.WorkP_id
                  ) total_achieved ON wp.WorkP_id = total_achieved.WorkP_id
                  WHERE p.Project_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Project_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($row['progress_percentage'] ?? 0, 2);
    }

public function getProjectWorks() {
        $query = "SELECT wp.*, 
                         COALESCE(MAX(a.Achieved_quantity), 0) as total_achieved,
                         CASE WHEN wp.Expected_quantity > 0 
                              THEN (COALESCE(MAX(a.Achieved_quantity), 0) / wp.Expected_quantity * 100)
                              ELSE 0 END as completion_rate
                  FROM Project p
                  JOIN SD s ON p.SD_id = s.SD_id
                  JOIN Work_P wp ON s.SD_id = wp.SD_id
                  LEFT JOIN Achieve a ON wp.WorkP_id = a.WorkP_id
                  WHERE p.Project_id = ?
                  GROUP BY wp.WorkP_id";
        $stmt = $this->conn->prepare($query);
$stmt->bindParam(1, $this->Project_id);
        $stmt->execute();
        return $stmt;
    }

    public function getFinancialDashboard($project_id = null) {
        $pid = $project_id ?? $this->Project_id;
        if (!$pid) {
            return [
                'budget' => 0.0,
                'planned_cost' => 0.0,
                'earned_value' => 0.0,
                'remaining' => 0.0,
                'consumption' => 0.0,
                'status' => 'ON TRACK',
            ];
        }

        $query = "SELECT
                    COALESCE(p.Budget, 0) AS budget,
                    COALESCE(SUM(wp.Expected_quantity * wp.Unit_price), 0) AS planned_cost,
                    COALESCE(SUM(COALESCE(a_max.achieved, 0) * wp.Unit_price), 0) AS earned_value
                  FROM Project p
                  LEFT JOIN SD s ON p.SD_id = s.SD_id
                  LEFT JOIN Work_P wp ON s.SD_id = wp.SD_id
                  LEFT JOIN (
                      SELECT WorkP_id, MAX(Achieved_quantity) AS achieved
                      FROM Achieve
                      GROUP BY WorkP_id
                  ) a_max ON a_max.WorkP_id = wp.WorkP_id
                  WHERE p.Project_id = ?
                  GROUP BY p.Project_id, p.Budget";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pid);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $budget = (float)($row['budget'] ?? 0);
        $planned = (float)($row['planned_cost'] ?? 0);
        $earned = (float)($row['earned_value'] ?? 0);

        $remaining = $budget - $earned;
        $consumption = $budget > 0 ? round(($earned / $budget) * 100, 2) : 0.0;

        if ($consumption < 60) $status = 'ON TRACK';
        elseif ($consumption < 90) $status = 'AT RISK';
        else $status = 'OVER BUDGET';

        return [
            'budget' => $budget,
            'planned_cost' => $planned,
            'earned_value' => $earned,
            'remaining' => $remaining,
            'consumption' => $consumption,
            'status' => $status,
        ];
    }

    public function getProjectPriorities() {
        $query = "SELECT p.Project_id, p.Project_name, p.Project_expected_end_date, p.Budget,
                         DATEDIFF(p.Project_expected_end_date, CURDATE()) as remaining_days,
                         CASE 
                             WHEN DATEDIFF(p.Project_expected_end_date, CURDATE()) < 0 THEN 'OVERDUE'
                             WHEN DATEDIFF(p.Project_expected_end_date, CURDATE()) <= 30 THEN 'HIGH'
                             WHEN DATEDIFF(p.Project_expected_end_date, CURDATE()) <= 60 THEN 'MEDIUM'
                             ELSE 'LOW'
                         END as priority
                  FROM Project p
                  WHERE p.Project_expected_end_date >= CURDATE() OR DATEDIFF(p.Project_expected_end_date, CURDATE()) IS NULL
                  ORDER BY remaining_days ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($search = '') {
        $query = "SELECT p.*, c.First_name, c.Last_name, s.Version,
                         (SELECT COALESCE(AVG(CASE WHEN wp.Expected_quantity > 0 
                            THEN (COALESCE(MAX(a.Achieved_quantity), 0) / wp.Expected_quantity) * 100 
                            ELSE 0 END), 0)
                          FROM Project p2
                          LEFT JOIN SD s2 ON p2.SD_id = s2.SD_id
                          LEFT JOIN Work_P wp ON s2.SD_id = wp.SD_id
                          LEFT JOIN Achieve a ON wp.WorkP_id = a.WorkP_id
                          WHERE p2.Project_id = p.Project_id
                          GROUP BY wp.WorkP_id) as progress
                  FROM Project p
                  LEFT JOIN Client c ON p.Client_id = c.Client_id
                  LEFT JOIN SD s ON p.SD_id = s.SD_id
                  WHERE 1=1";
        
        $params = [];
        
        if(!empty($search)) {
            $query .= " AND (p.Project_name LIKE ? OR c.First_name LIKE ? OR c.Last_name LIKE ?)";
            $search_param = "%{$search}%";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }
        
        $query .= " ORDER BY p.Project_id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>