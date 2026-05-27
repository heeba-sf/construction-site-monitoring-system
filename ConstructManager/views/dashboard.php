<?php
$current_page = 'dashboard';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="dashboard-container">
    <div class="page-header">
        <h2>Dashboard</h2>
        <p class="text-muted">Overview of your construction projects</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-content">
                <h3>Total Projects</h3>
                <p class="stat-number"><?php echo $stats['total_projects']; ?></p>
                <small>Active projects</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>Total Clients</h3>
                <p class="stat-number"><?php echo $stats['total_clients']; ?></p>
                <small>Registered clients</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <h3>Active Situations</h3>
                <p class="stat-number"><?php echo $stats['active_situations']; ?></p>
                <small>In progress</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-folder"></i>
            </div>
            <div class="stat-content">
                <h3>Documents</h3>
                <p class="stat-number"><?php echo $stats['total_documents']; ?></p>
                <small>Stored files</small>
            </div>
        </div>
    </div>

    <div class="dashboard-panel" style="margin-bottom: 24px;">
        <div class="panel-header">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Project Priority Dashboard</h3>
        </div>
        <div class="panel-content">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Expected End Date</th>
                            <th>Remaining Days</th>
                            <th>Budget</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($project_priorities)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray);">No projects found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($project_priorities as $proj): ?>
                        <?php 
                            $priority_class = '';
                            $priority_bg = '';
                            if($proj['priority'] == 'OVERDUE') {
                                $priority_class = 'text-danger';
                                $priority_bg = '#fee2e2';
                            } elseif($proj['priority'] == 'HIGH') {
                                $priority_class = 'text-warning';
                                $priority_bg = '#fef3c7';
                            } elseif($proj['priority'] == 'MEDIUM') {
                                $priority_class = 'text-info';
                                $priority_bg = '#dbeafe';
                            } else {
                                $priority_class = 'text-success';
                                $priority_bg = '#d1fae5';
                            }
                        ?>
                        <tr>
                            <td>
                                <a href="index.php?controller=project&action=view&id=<?php echo $proj['Project_id']; ?>" class="work-link">
                                    <?php echo htmlspecialchars($proj['Project_name']); ?>
                                </a>
                            </td>
                            <td><?php echo $proj['Project_expected_end_date'] ? date('d/m/Y', strtotime($proj['Project_expected_end_date'])) : '-'; ?></td>
                            <td>
                                <?php if($proj['remaining_days'] !== null): ?>
                                    <?php if($proj['remaining_days'] < 0): ?>
                                        <span class="text-danger"><?php echo abs($proj['remaining_days']); ?> days overdue</span>
                                    <?php else: ?>
                                        <?php echo $proj['remaining_days']; ?> days
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($proj['Budget'], 3); ?> DT</td>
                            <td>
                                <span class="badge" style="background: <?php echo $priority_bg; ?>; color: var(--dark);">
                                    <?php echo $proj['priority']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-panel">
            <div class="panel-header">
                <h3><i class="fas fa-clock text-primary"></i> Recent Projects</h3>
                <a href="index.php?controller=project&action=index" class="view-all">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="panel-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Client</th>
                                <th>Budget</th>
                                <th>Progress</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_projects as $project): ?>
                            <tr onclick="window.location='index.php?controller=project&action=view&id=<?php echo $project['Project_id']; ?>'">
                                <td>
                                    <strong><?php echo htmlspecialchars($project['Project_name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars(($project['First_name'] ?? '') . ' ' . ($project['Last_name'] ?? '')); ?></td>
                                <td><?php echo number_format($project['Budget'], 3); ?> DT</td>
                                <td>
                                    <div class="progress-container">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?php echo $project['progress'] ?? 0; ?>%"></div>
                                        </div>
                                        <span class="progress-text"><?php echo $project['progress'] ?? 0; ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-chevron-right text-gray"></i>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="dashboard-panel">
            <div class="panel-header">
                <h3><i class="fas fa-calendar text-primary"></i> Recent Situations</h3>
                <a href="index.php?controller=situation&action=index" class="view-all">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="panel-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Comment</th>
                                <th>Period</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_situations as $situation): ?>
                            <tr onclick="window.location='index.php?controller=situation&action=view&id=<?php echo $situation['Situation_id']; ?>'">
                                <td>
                                    <strong><?php echo htmlspecialchars($situation['Project_name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($situation['Comments']); ?></td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($situation['Start_date'])); ?> - 
                                    <?php echo date('d/m/Y', strtotime($situation['End_date'])); ?>
                                </td>
                                <td>
                                    <i class="fas fa-chevron-right text-gray"></i>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>