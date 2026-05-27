<?php
class Work {
    private $conn;
    private $table_name = "Work_P";

    public $WorkP_id;
    public $SD_id;
    public $WorkP_name;
    public $Expected_quantity;
    public $Measurment_unit;
    public $Unit_price;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT wp.*, s.Version, s.date_creation 
                  FROM " . $this->table_name . " wp
                  JOIN SD s ON wp.SD_id = s.SD_id
                  ORDER BY wp.WorkP_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getWorksBySD($sd_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE SD_id = ? ORDER BY WorkP_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $sd_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "SELECT MAX(WorkP_id) as max_id FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->WorkP_id = ($row['max_id'] ?? 0) + 1;
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (WorkP_id, SD_id, WorkP_name, Expected_quantity, Measurment_unit, Unit_price) 
                  VALUES (:WorkP_id, :SD_id, :WorkP_name, :Expected_quantity, :Measurment_unit, :Unit_price)";
        $stmt = $this->conn->prepare($query);
        
        $this->WorkP_name = htmlspecialchars(strip_tags($this->WorkP_name));
        $this->Measurment_unit = htmlspecialchars(strip_tags($this->Measurment_unit));
        $this->SD_id = htmlspecialchars(strip_tags($this->SD_id));
        $this->Expected_quantity = htmlspecialchars(strip_tags($this->Expected_quantity));
        $this->Unit_price = htmlspecialchars(strip_tags($this->Unit_price));
        
        $stmt->bindParam(":WorkP_id", $this->WorkP_id);
        $stmt->bindParam(":SD_id", $this->SD_id);
        $stmt->bindParam(":WorkP_name", $this->WorkP_name);
        $stmt->bindParam(":Expected_quantity", $this->Expected_quantity);
        $stmt->bindParam(":Measurment_unit", $this->Measurment_unit);
        $stmt->bindParam(":Unit_price", $this->Unit_price);
        
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET WorkP_name=:WorkP_name, Expected_quantity=:Expected_quantity, 
                      Measurment_unit=:Measurment_unit, Unit_price=:Unit_price 
                  WHERE WorkP_id=:WorkP_id";
        $stmt = $this->conn->prepare($query);
        
        $this->WorkP_name = htmlspecialchars(strip_tags($this->WorkP_name));
        $this->Measurment_unit = htmlspecialchars(strip_tags($this->Measurment_unit));
        $this->WorkP_id = htmlspecialchars(strip_tags($this->WorkP_id));
        $this->Expected_quantity = htmlspecialchars(strip_tags($this->Expected_quantity));
        $this->Unit_price = htmlspecialchars(strip_tags($this->Unit_price));
        
        $stmt->bindParam(":WorkP_name", $this->WorkP_name);
        $stmt->bindParam(":Expected_quantity", $this->Expected_quantity);
        $stmt->bindParam(":Measurment_unit", $this->Measurment_unit);
        $stmt->bindParam(":Unit_price", $this->Unit_price);
        $stmt->bindParam(":WorkP_id", $this->WorkP_id);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE WorkP_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->WorkP_id = htmlspecialchars(strip_tags($this->WorkP_id));
        $stmt->bindParam(1, $this->WorkP_id);
        return $stmt->execute();
    }

    public function getWorkDetails() {
        $query = "SELECT wp.*, sd.SD_id, sd.Version, sd.date_creation, p.Project_id, p.Project_name, p.Budget as Project_Budget
                  FROM " . $this->table_name . " wp
                  JOIN SD sd ON wp.SD_id = sd.SD_id
                  JOIN Project p ON p.SD_id = sd.SD_id
                  WHERE wp.WorkP_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->WorkP_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getWorkAchievements() {
        $query = "SELECT a.Achieved_quantity, a.Situation_id, a.WorkP_id,
                         sit.Comments, sit.Start_date, sit.End_date, sit.Project_id,
                         p.Project_name
                  FROM Achieve a
                  JOIN Situation sit ON a.Situation_id = sit.Situation_id
                  JOIN Project p ON sit.Project_id = p.Project_id
                  WHERE a.WorkP_id = ?
                  ORDER BY sit.Start_date, a.Situation_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->WorkP_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestAchievement() {
        $query = "SELECT a.Achieved_quantity, s.Start_date
                  FROM Achieve a
                  JOIN Situation s ON a.Situation_id = s.Situation_id
                  WHERE a.WorkP_id = ?
                  ORDER BY s.Start_date DESC, a.Situation_id DESC
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->WorkP_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>