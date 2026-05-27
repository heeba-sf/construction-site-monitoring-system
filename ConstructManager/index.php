<?php
session_start();

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if ($controller !== 'auth') {
        include 'views/login.php';
        exit();
    }
}

require_once 'config/database.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$situation_id = isset($_GET['situation_id']) ? $_GET['situation_id'] : null;
$workP_id = isset($_GET['workP_id']) ? $_GET['workP_id'] : null;

// Define which actions require an ID parameter
$actions_requiring_id = ['view', 'edit', 'update', 'delete'];

// Route mapping for valid controllers and actions
$routes = [
    'dashboard' => ['index'],
    'client' => ['index', 'view', 'create', 'store', 'edit', 'update', 'delete'],
    'project' => ['index', 'view', 'create', 'store', 'edit', 'update', 'delete', 'report'],
    'situation' => ['index', 'view', 'create', 'store', 'edit', 'update', 'delete', 'getWorksByProject', 'addWork', 'updateWork', 'deleteWork'],
    'document' => ['index', 'create', 'store', 'edit', 'update', 'delete'],
    'work' => ['index', 'view', 'create', 'store', 'edit', 'update', 'delete'],
    'sd'   => ['index', 'view', 'create', 'store', 'edit', 'update', 'delete'],
    'chat' => ['message']
];

// Auth routes
if ($controller == 'auth') {
    if ($action == 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid username or password';
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }
    
    if ($action == 'logout') {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
    
    include 'views/login.php';
    exit();
}

// Dashboard route
if ($controller == 'dashboard') {
    require_once 'models/Project.php';
    require_once 'models/Client.php';
    require_once 'models/Situation.php';
    require_once 'models/Document.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $project = new Project($db);
    $client = new Client($db);
    $situation = new Situation($db);
    $document = new Document($db);
    
$projects_stmt = $project->read();
    $all_projects = $projects_stmt->fetchAll(PDO::FETCH_ASSOC);
    $recent_projects = array_slice($all_projects, 0, 5);
    
    foreach($recent_projects as &$proj) {
        $project->Project_id = $proj['Project_id'];
        $proj['progress'] = $project->getProjectProgress();
    }
    
    $situations_stmt = $situation->read();
    $all_situations = $situations_stmt->fetchAll(PDO::FETCH_ASSOC);
    $recent_situations = array_slice($all_situations, 0, 5);
    
    $clients_stmt = $client->read();
    $total_clients = $clients_stmt->rowCount();
    
    $total_projects = count($all_projects);
    
    $documents_all = $document->read();
    $total_documents = $documents_all->rowCount();
    
$stats = [
        'total_projects' => $total_projects,
        'total_clients' => $total_clients,
        'active_situations' => count($recent_situations),
        'total_documents' => $total_documents
    ];
    
    $project_priorities = $project->getProjectPriorities();
    
    include 'views/dashboard.php';
    
} else {
    // Check if controller is valid
    if (!isset($routes[$controller])) {
        header("Location: index.php");
        exit();
    }
    
    // Check if action is valid for this controller
    if (!in_array($action, $routes[$controller])) {
        header("Location: index.php?controller=" . $controller . "&action=index");
        exit();
    }
    
// Check if action requires ID but none provided (exclude situation actions that use different params)
    if (in_array($action, $actions_requiring_id) && !$id && !in_array($action, ['deleteWork', 'addWork', 'updateWork'])) {
        header("Location: index.php?controller=" . $controller . "&action=index");
        exit();
    }
    
    // Special controller name mappings
    $controllerMap = ['sd' => 'SDController'];
    $controllerName = isset($controllerMap[$controller]) ? $controllerMap[$controller] : ucfirst($controller) . 'Controller';
    $controllerFile = 'controllers/' . $controllerName . '.php';
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controllerInstance = new $controllerName();
        
if (method_exists($controllerInstance, $action)) {
            // Handle special situation actions with multiple parameters
            if ($action === 'deleteWork' && $situation_id && $workP_id) {
                $controllerInstance->$action($situation_id, $workP_id);
            } elseif ($action === 'addWork' && $id) {
                $controllerInstance->$action($id);
            } elseif ($action === 'updateWork' && $id) {
                $controllerInstance->$action($id);
            } elseif ($id) {
                $controllerInstance->$action($id);
            } else {
                $controllerInstance->$action();
            }
        } else {
            echo "Action not found in controller!";
        }
    } else {
        echo "Controller file not found: " . $controllerFile;
    }
}
?>