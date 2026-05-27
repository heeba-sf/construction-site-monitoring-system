<?php
require_once 'models/Project.php';
require_once 'models/Client.php';
require_once 'models/Situation.php';
require_once 'models/Document.php';
require_once 'models/Work.php';

class ProjectController {
    private $db;
    private $project;
    private $client;
    private $situation;
    private $document;
    private $work;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->project = new Project($this->db);
        $this->client = new Client($this->db);
        $this->situation = new Situation($this->db);
        $this->document = new Document($this->db);
        $this->work = new Work($this->db);
    }

public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if(!empty($search)) {
            $projects = $this->project->search($search);
        } else {
            $stmt = $this->project->read();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        foreach($projects as &$project) {
            $this->project->Project_id = $project['Project_id'];
            $project['progress'] = $this->project->getProjectProgress();
        }
        
include 'views/projects.php';
    }

    public function view($id = null) {
        if (!$id) {
            header("Location: index.php?controller=project&action=index");
            exit();
        }
        $this->project->Project_id = $id;
        $project = $this->project->readOne();
        
        if($project) {
            $progress = $this->project->getProjectProgress();
            
            $this->situation->Project_id = $id;
            $situations_stmt = $this->situation->getSituationsByProject();
            $situations = $situations_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->document->Project_id = $id;
            $documents_stmt = $this->document->getDocumentsByProject();
            $documents = $documents_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $works_stmt = $this->project->getProjectWorks();
            $works = $works_stmt->fetchAll(PDO::FETCH_ASSOC);

            $financial = $this->project->getFinancialDashboard($id);

            include 'views/project-details.php';
        } else {
            header("Location: index.php?controller=project&action=index");
        }
    }

    public function create() {
        $clients_stmt = $this->client->getAllClients();
        $clients = $clients_stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/project-form.php';
    }

    public function store() {
        if($_POST) {
            $this->project->Client_id = $_POST['Client_id'];
            $this->project->Project_name = $_POST['Project_name'];
            $this->project->Budget = $_POST['Budget'];
            $this->project->Project_start_date = $_POST['Project_start_date'];
            $this->project->Project_expected_end_date = $_POST['Project_expected_end_date'];
            
            if($this->project->create()) {
                $_SESSION['message'] = "Project created successfully!";
                $_SESSION['message_type'] = "success";
                header("Location: index.php?controller=project&action=view&id=" . $this->project->Project_id);
            } else {
                $_SESSION['message'] = "Error creating project.";
                $_SESSION['message_type'] = "danger";
                header("Location: index.php?controller=project&action=create");
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            header("Location: index.php?controller=project&action=index");
            exit();
        }
        $this->project->Project_id = $id;
        if($this->project->readOne()) {
            $project = [
                'Project_id' => $this->project->Project_id,
                'Client_id' => $this->project->Client_id,
                'SD_id' => $this->project->SD_id,
                'Project_name' => $this->project->Project_name,
                'Budget' => $this->project->Budget,
                'Project_start_date' => $this->project->Project_start_date,
                'Project_expected_end_date' => $this->project->Project_expected_end_date
            ];
            
$clients_stmt = $this->client->getAllClients();
            $clients = $clients_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            include 'views/project-form.php';
        } else {
            header("Location: index.php?controller=project&action=index");
        }
    }

    public function update($id = null) {
        if (!$id) {
            header("Location: index.php?controller=project&action=index");
            exit();
        }
        if($_POST) {
            $this->project->Project_id = $id;
            $this->project->Client_id = $_POST['Client_id'];
            $this->project->Project_name = $_POST['Project_name'];
            $this->project->Budget = $_POST['Budget'];
            $this->project->Project_start_date = $_POST['Project_start_date'];
            $this->project->Project_expected_end_date = $_POST['Project_expected_end_date'];
            
            if($this->project->update()) {
                $_SESSION['message'] = "Project updated!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating project.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=project&action=index");
        }
    }

    public function delete($id = null) {
        if (!$id) {
            header("Location: index.php?controller=project&action=index");
            exit();
        }
        $this->project->Project_id = $id;
        
        if($this->project->delete()) {
            $_SESSION['message'] = "Project deleted!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting project.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php?controller=project&action=index");
    }
    public function report($id = null) {
        if (!$id) {
            header("Location: index.php?controller=project&action=index");
            exit();
        }
        $this->project->Project_id = $id;
        $project = $this->project->readOne();

        if ($project) {
            $progress  = $this->project->getProjectProgress();
            $financial = $this->project->getFinancialDashboard($id);

            $this->situation->Project_id = $id;
            $situations_stmt = $this->situation->getSituationsByProject();
            $situations = $situations_stmt->fetchAll(PDO::FETCH_ASSOC);

            $works_stmt = $this->project->getProjectWorks();
            $works = $works_stmt->fetchAll(PDO::FETCH_ASSOC);

            include 'views/project-report.php';
        } else {
            header("Location: index.php?controller=project&action=index");
        }
    }

}
?>