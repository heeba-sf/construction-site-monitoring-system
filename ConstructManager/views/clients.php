<?php
$current_page = 'clients';
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
            <h2>Client Management</h2>
            <p class="text-muted">List of all your clients and their information</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=client&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Client
            </a>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <form method="GET" action="" style="display: flex; gap: 10px;">
            <input type="hidden" name="controller" value="client">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; flex: 1; max-width: 300px;">
            <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Search</button>
            <?php if($search): ?>
            <a href="index.php?controller=client&action=index" class="btn btn-secondary" style="padding: 8px 16px;">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Address</th>
                        <th>Projects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clients as $client): ?>
                    <tr>
                        <td><span class="badge bg-primary-light">#<?php echo $client['Client_id']; ?></span></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="table-avatar">
                                    <?php echo strtoupper(substr($client['First_name'], 0, 1) . substr($client['Last_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <strong><?php echo htmlspecialchars($client['First_name'] . ' ' . $client['Last_name']); ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-map-marker-alt text-gray"></i>
                            <?php echo htmlspecialchars($client['Address']); ?>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo $client['project_count']; ?> project(s)</span>
                        </td>
                        <td class="actions-cell">
                            <a href="index.php?controller=client&action=view&id=<?php echo $client['Client_id']; ?>" 
                               class="btn-icon" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?controller=client&action=edit&id=<?php echo $client['Client_id']; ?>" 
                               class="btn-icon" title="Edit" style="background: var(--warning); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $client['Client_id']; ?>, '<?php echo htmlspecialchars(addslashes($client['First_name'] . ' ' . $client['Last_name'])); ?>')" 
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
    if(confirm('Are you sure you want to delete the client "' + name + '"?\nThis action is irreversible and will delete all associated data.')) {
        window.location.href = 'index.php?controller=client&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>