<?php
$current_page = 'works';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

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
            <h2>Work Packages</h2>
            <p class="text-muted">List of all work packages</p>
        </div>
    </div>
    
    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SD ID</th>
                        <th>Work Name</th>
                        <th>Expected Qty</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($works as $work): ?>
                    <tr>
                        <td><span class="badge bg-primary-light">#<?php echo $work['WorkP_id']; ?></span></td>
                        <td>SD #<?php echo $work['SD_id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($work['WorkP_name']); ?></strong></td>
                        <td><?php echo number_format($work['Expected_quantity'], 2); ?></td>
                        <td><?php echo htmlspecialchars($work['Measurment_unit']); ?></td>
                        <td><?php echo number_format($work['Unit_price'], 3); ?> DT</td>
                        <td class="actions-cell">
                            <a href="index.php?controller=work&action=view&id=<?php echo $work['WorkP_id']; ?>" 
                               class="btn-icon" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?controller=work&action=edit&id=<?php echo $work['WorkP_id']; ?>" 
                               class="btn-icon" title="Edit" style="background: var(--warning); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $work['WorkP_id']; ?>, '<?php echo htmlspecialchars(addslashes($work['WorkP_name'])); ?>')" 
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
    if(confirm('Are you sure you want to delete the work package "' + name + '"?\nThis action is irreversible.')) {
        window.location.href = 'index.php?controller=work&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>