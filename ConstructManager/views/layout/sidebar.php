<?php
$base_url = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if ($base_url == '/' || $base_url == '\\') $base_url = '';
}
$current = isset($current_page) ? $current_page : '';
?>
            <aside class="app-sidebar" id="sidebar">
                <nav class="sidebar-nav">
                    <a href="<?php echo $base_url; ?>/index.php?controller=dashboard&action=index"
                       class="nav-item <?php echo $current == 'dashboard' ? 'active' : ''; ?>" title="Dashboard">
                        <i class="fas fa-chart-pie"></i><span>Dashboard</span>
                    </a>
                    <a href="<?php echo $base_url; ?>/index.php?controller=client&action=index"
                       class="nav-item <?php echo $current == 'clients' ? 'active' : ''; ?>" title="Clients">
                        <i class="fas fa-users"></i><span>Clients</span>
                    </a>
                    <a href="<?php echo $base_url; ?>/index.php?controller=project&action=index"
                       class="nav-item <?php echo $current == 'projects' ? 'active' : ''; ?>" title="Projects">
                        <i class="fas fa-building"></i><span>Projects</span>
                    </a>
                    <a href="<?php echo $base_url; ?>/index.php?controller=sd&action=index"
                       class="nav-item <?php echo $current == 'sd' ? 'active' : ''; ?>" title="Spec. Documents">
                        <i class="fas fa-file-contract"></i><span>Spec. Documents</span>
                    </a>
                    <a href="<?php echo $base_url; ?>/index.php?controller=situation&action=index"
                       class="nav-item <?php echo $current == 'situations' ? 'active' : ''; ?>" title="Situations">
                        <i class="fas fa-tasks"></i><span>Situations</span>
                    </a>
                    <a href="<?php echo $base_url; ?>/index.php?controller=document&action=index"
                       class="nav-item <?php echo $current == 'documents' ? 'active' : ''; ?>" title="Documents">
                        <i class="fas fa-folder"></i><span>Documents</span>
                    </a>
                </nav>
            </aside>
            <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
            <main class="app-main" id="appMain">
