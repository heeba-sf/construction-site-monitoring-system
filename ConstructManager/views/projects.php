<?php
$current_page = 'projects';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2>Project Management</h2>
            <p class="text-muted">Track the progress of all your construction projects</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=project&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Project
            </a>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <form method="GET" action="" style="display: flex; gap: 10px;">
            <input type="hidden" name="controller" value="project">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; flex: 1; max-width: 300px;">
            <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Search</button>
            <?php if($search): ?>
            <a href="index.php?controller=project&action=index" class="btn btn-secondary" style="padding: 8px 16px;">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Budget</th>
                        <th>Dates</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($projects)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 40px;">
                            No projects found matching your criteria.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($projects as $project): 
                        $progress_color = $project['progress'] >= 75 ? 'success' : ($project['progress'] >= 40 ? 'warning' : 'danger');
                    ?>
                    <tr>
                        <td><span class="badge bg-primary-light">#<?php echo $project['Project_id']; ?></span></td>
                        <td>
                            <strong><?php echo htmlspecialchars($project['Project_name']); ?></strong>
                            <br>
                            <small class="text-muted">SD v<?php echo htmlspecialchars($project['Version'] ?? '1.0'); ?></small>
                        </td>
                        <td>
                            <i class="fas fa-user text-gray"></i>
                            <?php echo htmlspecialchars($project['First_name'] . ' ' . $project['Last_name']); ?>
                        </td>
                        <td>
                            <strong><?php echo number_format($project['Budget'], 3); ?> DT</strong>
                        </td>
                        <td>
                            <div><i class="fas fa-play text-success"></i> <?php echo date('d/m/Y', strtotime($project['Project_start_date'])); ?></div>
                            <div><i class="fas fa-flag-checkered text-primary"></i> <?php echo date('d/m/Y', strtotime($project['Project_expected_end_date'])); ?></div>
                        </td>
                        <td>
                            <div class="progress-container" style="gap: 10px;">
                                <span class="progress-text" style="min-width: 40px;"><?php echo $project['progress']; ?>%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $project['progress']; ?>%; background: <?php echo $progress_color == 'success' ? 'var(--success)' : ($progress_color == 'warning' ? 'var(--warning)' : 'var(--danger)'); ?>"></div>
                                </div>
                            </div>
                        </td>
                        <td class="actions-cell">
                            <a href="index.php?controller=project&action=view&id=<?php echo $project['Project_id']; ?>" 
                               class="btn-icon" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?controller=project&action=edit&id=<?php echo $project['Project_id']; ?>" 
                               class="btn-icon" title="Edit" style="background: var(--warning); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?controller=work&action=create&project_id=<?php echo $project['Project_id']; ?>" 
                               class="btn-icon" title="Add Work Package" style="background: var(--info); color: white;">
                                <i class="fas fa-hard-hat"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $project['Project_id']; ?>, '<?php echo htmlspecialchars(addslashes($project['Project_name'])); ?>')" 
                                    class="btn-icon" title="Delete" style="background: var(--danger); color: white; border: none; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if(confirm('Are you sure you want to delete the project "' + name + '"?\nThis action is irreversible and will delete all associated data (SD, situations, documents).')) {
        window.location.href = 'index.php?controller=project&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>