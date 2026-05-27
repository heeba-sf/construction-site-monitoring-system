<?php
require_once 'models/Document.php';
require_once 'models/Project.php';

class DocumentController {
    private $db;
    private $document;
    private $project;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->document = new Document($this->db);
        $this->project = new Project($this->db);
    }

    public function index() {
        $stmt = $this->document->read();
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stats_stmt = $this->document->getDocumentStats();
        $stats = $stats_stmt->fetchAll(PDO::FETCH_ASSOC);
        
include 'views/documents.php';
    }

    public function create() {
        $projects_stmt = $this->project->read();
        $projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/document-form.php';
    }

    public function store() {
        if($_POST) {
            $this->document->Project_id = $_POST['Project_id'];
            $this->document->Document_type = $_POST['Document_type'];
            $this->document->Document_url = $_POST['Document_url'];
            $this->document->Authorization_level = $_POST['Authorization_level'];
            
            $file = isset($_FILES['document_file']) ? $_FILES['document_file'] : null;
            
            if($this->document->create($file)) {
                $_SESSION['message'] = "Document added successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error adding document.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=document&action=index");
        }
    }

    public function edit($id = null) {
        if (!$id) {
            header("Location: index.php?controller=document&action=index");
            exit();
        }
        $this->document->Document_id = $id;
        if($this->document->readOne()) {
            $document = [
                'Document_id' => $this->document->Document_id,
                'Project_id' => $this->document->Project_id,
                'Document_type' => $this->document->Document_type,
                'Document_url' => $this->document->Document_url,
                'Authorization_level' => $this->document->Authorization_level
            ];
            
$projects_stmt = $this->project->read();
            $projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            include 'views/document-form.php';
        } else {
            header("Location: index.php?controller=document&action=index");
        }
    }

    public function update($id = null) {
        if (!$id) {
            header("Location: index.php?controller=document&action=index");
            exit();
        }
        if($_POST) {
            $this->document->Document_id = $id;
            $this->document->Project_id = $_POST['Project_id'];
            $this->document->Document_type = $_POST['Document_type'];
            $this->document->Document_url = $_POST['Document_url'];
            $this->document->Authorization_level = $_POST['Authorization_level'];
            
            if($this->document->update()) {
                $_SESSION['message'] = "Document updated!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating document.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=document&action=index");
        }
    }

    public function delete($id = null) {
        if (!$id) {
            header("Location: index.php?controller=document&action=index");
            exit();
        }
        $this->document->Document_id = $id;
        
        if($this->document->delete()) {
            $_SESSION['message'] = "Document deleted!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting document.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php?controller=document&action=index");
    }
}
?>