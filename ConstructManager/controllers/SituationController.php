<?php
require_once 'models/Situation.php';
require_once 'models/Project.php';
require_once 'models/Work.php';

class SituationController {
    private $db;
    private $situation;
    private $project;
    private $work;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->situation = new Situation($this->db);
        $this->project = new Project($this->db);
        $this->work = new Work($this->db);
    }

public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if(!empty($search)) {
            $situations = $this->situation->search($search);
        } else {
            $stmt = $this->situation->read();
            $situations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
include 'views/situations.php';
    }

public function view($id = null) {
        if (!$id) {
            header("Location: index.php?controller=situation&action=index");
            exit();
        }
        $this->situation->Situation_id = $id;
        $situation = $this->situation->getSituationDetails();
        
        if($situation) {
            $works_stmt = $this->situation->getSituationWorks();
            $works = $works_stmt->fetchAll(PDO::FETCH_ASSOC);
            $availableWorks = $this->situation->getAvailableWorks();
            include 'views/situation-details.php';
        } else {
            header("Location: index.php?controller=situation&action=index");
        }
    }

    public function create() {
        $projects_stmt = $this->project->read();
        $projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/situation-form.php';
    }

    public function store() {
        if($_POST) {
            $this->situation->Project_id = $_POST['Project_id'];
            $this->situation->Comments = $_POST['Comments'];
            $this->situation->Start_date = $_POST['Start_date'];
            $this->situation->End_date = $_POST['End_date'];
            
            $achievements = isset($_POST['achievements']) ? $_POST['achievements'] : [];
            
            if($this->situation->create($achievements)) {
                $_SESSION['message'] = "Situation created successfully!";
                $_SESSION['message_type'] = "success";
                header("Location: index.php?controller=situation&action=view&id=" . $this->situation->Situation_id);
            } else {
                $_SESSION['message'] = "Error creating situation.";
                $_SESSION['message_type'] = "danger";
                header("Location: index.php?controller=situation&action=create");
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            header("Location: index.php?controller=situation&action=index");
            exit();
        }
        $this->situation->Situation_id = $id;
        if($this->situation->readOne()) {
            $situation = [
                'Situation_id' => $this->situation->Situation_id,
                'Project_id' => $this->situation->Project_id,
                'Comments' => $this->situation->Comments,
                'Start_date' => $this->situation->Start_date,
                'End_date' => $this->situation->End_date
            ];
            
$projects_stmt = $this->project->read();
            $projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            include 'views/situation-form.php';
        } else {
            header("Location: index.php?controller=situation&action=index");
        }
    }

    public function update($id = null) {
        if (!$id) {
            header("Location: index.php?controller=situation&action=index");
            exit();
        }
        if($_POST) {
            $this->situation->Situation_id = $id;
            $this->situation->Project_id = $_POST['Project_id'];
            $this->situation->Comments = $_POST['Comments'];
            $this->situation->Start_date = $_POST['Start_date'];
            $this->situation->End_date = $_POST['End_date'];
            
            if($this->situation->update()) {
                $_SESSION['message'] = "Situation updated!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating situation.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=situation&action=index");
        }
    }

    public function delete($id = null) {
        if (!$id) {
            header("Location: index.php?controller=situation&action=index");
            exit();
        }
        $this->situation->Situation_id = $id;
        
        if($this->situation->delete()) {
            $_SESSION['message'] = "Situation deleted!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting situation.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php?controller=situation&action=index");
    }

public function getWorksByProject() {
        if(isset($_GET['project_id'])) {
            $this->project->Project_id = $_GET['project_id'];
            $project = $this->project->readOne();
            
            if($project && $project['SD_id']) {
                $works_stmt = $this->work->getWorksBySD($project['SD_id']);
                $works = $works_stmt->fetchAll(PDO::FETCH_ASSOC);
                header('Content-Type: application/json');
                echo json_encode($works);
            }
        }
    }

    public function addWork($situation_id = null) {
        if (!$situation_id || !isset($_POST['WorkP_id'])) {
            header("Location: index.php?controller=situation&action=view&id=" . $situation_id);
            exit();
        }
        
        $this->situation->Situation_id = $situation_id;
        $quantity = $_POST['Achieved_quantity'] ?? 0;
        
        if($this->situation->addWorkAchievement($_POST['WorkP_id'], $quantity)) {
            $_SESSION['message'] = "Work package added to situation!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding work package.";
            $_SESSION['message_type'] = "danger";
        }
        
        header("Location: index.php?controller=situation&action=view&id=" . $situation_id);
    }

    public function updateWork($situation_id = null) {
        if (!$situation_id || !isset($_POST['WorkP_id'])) {
            header("Location: index.php?controller=situation&action=view&id=" . $situation_id);
            exit();
        }
        
        $this->situation->Situation_id = $situation_id;
        
        if($this->situation->updateWorkAchievement($_POST['WorkP_id'], $_POST['Achieved_quantity'])) {
            $_SESSION['message'] = "Work package updated!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating work package.";
            $_SESSION['message_type'] = "danger";
        }
        
        header("Location: index.php?controller=situation&action=view&id=" . $situation_id);
    }

    public function deleteWork($situation_id = null, $workP_id = null) {
        if (!$situation_id || !$workP_id) {
            header("Location: index.php?controller=situation&action=index");
            exit();
        }
        
        $this->situation->Situation_id = $situation_id;
        
        if($this->situation->deleteWorkAchievement($workP_id)) {
            $_SESSION['message'] = "Work package removed from situation!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error removing work package.";
            $_SESSION['message_type'] = "danger";
        }
        
        header("Location: index.php?controller=situation&action=view&id=" . $situation_id);
    }
}
?>