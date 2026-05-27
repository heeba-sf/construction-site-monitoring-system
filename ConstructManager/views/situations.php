<?php
$current_page = 'situations';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if(isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
        <i class="fas fa-<?php echo $_SESSION['message_type'] == 'success' ? 'check-circle' : ($_SESSION['message_type'] == 'danger' ? 'exclamation-circle' : 'info-circle'); ?>"></i>
        <?php echo $_SESSION['message']; ?>
        <button class="close-alert" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php 
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
endif; 
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2>Work Situations</h2>
            <p class="text-muted">Periodic tracking of work progress</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=situation&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Situation
            </a>
        </div>
    </div>
    
    <div style="margin-bottom: 20px;">
        <form method="GET" action="" style="display: flex; gap: 10px;">
            <input type="hidden" name="controller" value="situation">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; flex: 1; max-width: 300px;">
            <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Search</button>
            <?php if($search): ?>
            <a href="index.php?controller=situation&action=index" class="btn btn-secondary" style="padding: 8px 16px;">Clear</a>
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
                        <th>Comment</th>
                        <th>Period</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($situations as $situation): 
                        $start = strtotime($situation['Start_date']);
                        $end = strtotime($situation['End_date']);
                        $duration = round(($end - $start) / (60 * 60 * 24));
                    ?>
                    <tr>
                        <td><span class="badge bg-info">#<?php echo $situation['Situation_id']; ?></span></td>
                        <td>
                            <strong><?php echo htmlspecialchars($situation['Project_name']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($situation['Comments']); ?></td>
                        <td>
                            <div><i class="fas fa-calendar-alt text-primary"></i> <?php echo date('d/m/Y', strtotime($situation['Start_date'])); ?></div>
                            <div><i class="fas fa-calendar-check text-success"></i> <?php echo date('d/m/Y', strtotime($situation['End_date'])); ?></div>
                        </td>
                        <td>
                            <span class="badge bg-warning"><?php echo $duration; ?> days</span>
                        </td>
                        <td class="actions-cell">
                            <a href="index.php?controller=situation&action=view&id=<?php echo $situation['Situation_id']; ?>" 
                               class="btn-icon" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?controller=situation&action=edit&id=<?php echo $situation['Situation_id']; ?>" 
                               class="btn-icon" title="Edit" style="background: var(--warning); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $situation['Situation_id']; ?>, 'Situation #<?php echo $situation['Situation_id']; ?>')" 
                                    class="btn-icon" title="Delete" style="background: var(--danger); color: white; border: none; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if(confirm('Are you sure you want to delete ' + name + '?\nThis action is irreversible.')) {
        window.location.href = 'index.php?controller=situation&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>