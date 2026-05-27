<?php
$base_url = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if ($base_url == '/' || $base_url == '\\') {
        $base_url = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construction Management System</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <header class="app-header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><img src="<?php echo $base_url; ?>/assets/images/logo.png" alt="ConstructManager" style="height: 60px;width:auto;vertical-align:middle;border-radius:8px;margin-right:10px;">ConstructManager</h1>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <i class="fas fa-user-circle"></i>
                    <span>Administrator</span>
                </div>
                <a href="index.php?controller=auth&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </header>
        
        <div class="app-content">