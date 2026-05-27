<?php
require_once 'models/Client.php';

class ClientController {
    private $db;
    private $client;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->client = new Client($this->db);
    }

public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if(!empty($search)) {
            $clients = $this->client->search($search);
        } else {
            $stmt = $this->client->read();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
include 'views/clients.php';
    }

    public function view($id = null) {
        if (!$id) {
            header("Location: index.php?controller=client&action=index");
            exit();
        }
        $this->client->Client_id = $id;
        if($this->client->readOne()) {
            $client = [
                'Client_id' => $this->client->Client_id,
                'First_name' => $this->client->First_name,
                'Last_name' => $this->client->Last_name,
                'Address' => $this->client->Address
            ];
            $stmt = $this->client->getClientProjects();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            include 'views/client-details.php';
        } else {
            header("Location: index.php?controller=client&action=index");
        }
    }

public function create() {
        include 'views/client-form.php';
    }

    public function store() {
        if($_POST) {
            $this->client->First_name = $_POST['First_name'];
            $this->client->Last_name = $_POST['Last_name'];
            $this->client->Address = $_POST['Address'];
            
            if($this->client->create()) {
                $_SESSION['message'] = "Client created successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error creating client.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=client&action=index");
        }
    }

    public function edit($id = null) {
        if (!$id) {
            header("Location: index.php?controller=client&action=index");
            exit();
        }
        $this->client->Client_id = $id;
        if($this->client->readOne()) {
            $client = [
                'Client_id' => $this->client->Client_id,
                'First_name' => $this->client->First_name,
                'Last_name' => $this->client->Last_name,
                'Address' => $this->client->Address
];
            include 'views/client-form.php';
        } else {
            header("Location: index.php?controller=client&action=index");
        }
    }

    public function update($id = null) {
        if (!$id) {
            header("Location: index.php?controller=client&action=index");
            exit();
        }
        if($_POST) {
            $this->client->Client_id = $id;
            $this->client->First_name = $_POST['First_name'];
            $this->client->Last_name = $_POST['Last_name'];
            $this->client->Address = $_POST['Address'];
            
            if($this->client->update()) {
                $_SESSION['message'] = "Client updated!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating client.";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?controller=client&action=index");
        }
    }

    public function delete($id = null) {
        if (!$id) {
            header("Location: index.php?controller=client&action=index");
            exit();
        }
        $this->client->Client_id = $id;
        
        if($this->client->hasProjects()) {
            $_SESSION['message'] = "Cannot delete this client (has associated projects).";
            $_SESSION['message_type'] = "warning";
        } else {
            if($this->client->delete()) {
                $_SESSION['message'] = "Client deleted!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de la suppression.";
                $_SESSION['message_type'] = "danger";
            }
        }
        header("Location: index.php?controller=client&action=index");
    }
}
?>