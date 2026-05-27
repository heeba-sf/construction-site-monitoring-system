<?php
require_once 'models/SD.php';
require_once 'models/Work.php';

class SDController {
    private $db;
    private $sd;
    private $work;

    public function __construct() {
        $database = new Database();
        $this->db   = $database->getConnection();
        $this->sd   = new SD($this->db);
        $this->work = new Work($this->db);
    }

    public function index() {
        $stmt = $this->sd->read();
        $sds  = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/sd-list.php';
    }

    public function view($id = null) {
        if (!$id) { header("Location: index.php?controller=sd&action=index"); exit(); }
        $this->sd->SD_id = $id;
        $sd = $this->sd->readOne();
        if ($sd) {
            $works_stmt = $this->sd->getWorkPackages();
            $works      = $works_stmt->fetchAll(PDO::FETCH_ASSOC);
            $contract_value = $this->sd->getContractValue();
            include 'views/sd-details.php';
        } else {
            header("Location: index.php?controller=sd&action=index");
        }
    }

    public function create() {
        include 'views/sd-form.php';
    }

    public function store() {
        if ($_POST) {
            $this->sd->Version       = $_POST['Version'];
            $this->sd->date_creation = $_POST['date_creation'];
            if ($this->sd->create()) {
                $_SESSION['message']      = "Specification Document created successfully!";
                $_SESSION['message_type'] = "success";
                header("Location: index.php?controller=sd&action=view&id=" . $this->sd->SD_id);
            } else {
                $_SESSION['message']      = "Error creating SD.";
                $_SESSION['message_type'] = "danger";
                header("Location: index.php?controller=sd&action=create");
            }
        }
    }

    public function edit($id = null) {
        if (!$id) { header("Location: index.php?controller=sd&action=index"); exit(); }
        $this->sd->SD_id = $id;
        $sd = $this->sd->readOne();
        if ($sd) {
            include 'views/sd-form.php';
        } else {
            header("Location: index.php?controller=sd&action=index");
        }
    }

    public function update($id = null) {
        if (!$id) { header("Location: index.php?controller=sd&action=index"); exit(); }
        if ($_POST) {
            $this->sd->SD_id         = $id;
            $this->sd->Version       = $_POST['Version'];
            $this->sd->date_creation = $_POST['date_creation'];
            if ($this->sd->update()) {
                $_SESSION['message']      = "Specification Document updated!";
                $_SESSION['message_type'] = "success";
                header("Location: index.php?controller=sd&action=view&id=" . $id);
            } else {
                $_SESSION['message']      = "Error updating SD.";
                $_SESSION['message_type'] = "danger";
                header("Location: index.php?controller=sd&action=edit&id=" . $id);
            }
        }
    }

    public function delete($id = null) {
        if (!$id) { header("Location: index.php?controller=sd&action=index"); exit(); }
        $this->sd->SD_id = $id;
        try {
            if ($this->sd->delete()) {
                $_SESSION['message']      = "Specification Document deleted.";
                $_SESSION['message_type'] = "success";
            }
        } catch (Exception $e) {
            $_SESSION['message']      = "Cannot delete: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php?controller=sd&action=index");
    }
}
?>
