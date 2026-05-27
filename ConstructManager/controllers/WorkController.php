<?php
require_once 'models/Work.php';
require_once 'models/Project.php';

class WorkController {
    private $db;
    private $work;
    private $project;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->work = new Work($this->db);
        $this->project = new Project($this->db);
    }

public function index() {
        $stmt = $this->work->read();
        $works = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'views/works.php';
    }

    public function view($id = null) {
        if (!$id) {
            header("Location: index.php?controller=work&action=index");
            exit();
        }
        $this->work->WorkP_id = $id;
        $work = $this->work->getWorkDetails();
        
        if($work) {
            $achievements = $this->work->getWorkAchievements();
            $latest = $this->work->getLatestAchievement();
            include 'views/work-details.php';
        } else {
            header("Location: index.php?controller=work&action=index");
        }
    }

    public function create() {
        require_once 'models/SD.php';
        $sd_model = new SD($this->db);
        $sd_id = isset($_GET['sd_id']) ? (int)$_GET['sd_id'] : null;
        if (!$sd_id && isset($_GET['project_id'])) {
            $this->project->Project_id = (int)$_GET['project_id'];
            $project = $this->project->readOne();
            $sd_id = $project['SD_id'] ?? null;
        }
        $all_sds = $sd_model->getAllForDropdown();
        include 'views/work-form.php';
    }

    public function store() {
        if($_POST) {
            $this->work->SD_id = $_POST['SD_id'];
            $this->work->WorkP_name = $_POST['WorkP_name'];
            $this->work->Expected_quantity = $_POST['Expected_quantity'];
            $this->work->Measurment_unit = $_POST['Measurment_unit'];
            $this->work->Unit_price = $_POST['Unit_price'];
            
            if($this->work->create()) {
                $_SESSION['message'] = "Work Package created successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error creating work package.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=work&action=index");
        }
    }

public function edit($id = null) {
        if (!$id) {
            header("Location: index.php?controller=work&action=index");
            exit();
        }
        $this->work->WorkP_id = $id;
        $work = $this->work->getWorkDetails();
        
        if($work) {
            include 'views/work-form.php';
        } else {
            header("Location: index.php?controller=work&action=index");
        }
    }

    public function update($id = null) {
        if (!$id || !$_POST) {
            header("Location: index.php?controller=work&action=index");
            exit();
        }
        $this->work->WorkP_id = $id;
        $this->work->SD_id = $_POST['SD_id'];
        $this->work->WorkP_name = $_POST['WorkP_name'];
        $this->work->Expected_quantity = $_POST['Expected_quantity'];
        $this->work->Measurment_unit = $_POST['Measurment_unit'];
        $this->work->Unit_price = $_POST['Unit_price'];
        
        if($this->work->update()) {
            $_SESSION['message'] = "Work Package updated!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating work package.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php?controller=work&action=index");
    }

    public function delete($id = null) {
        if (!$id) {
            header("Location: index.php?controller=work&action=index");
            exit();
        }
        $this->work->WorkP_id = $id;
        
        if($this->work->delete()) {
            $_SESSION['message'] = "Work Package deleted!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting work package.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php?controller=work&action=index");
    }
}
?>