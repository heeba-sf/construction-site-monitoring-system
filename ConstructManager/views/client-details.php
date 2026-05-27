<?php
$current_page = 'clients';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2>Client Details</h2>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=client&action=edit&id=<?php echo $client['Client_id']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button onclick="confirmDelete(<?php echo $client['Client_id']; ?>, '<?php echo htmlspecialchars(addslashes($client['First_name'] . ' ' . $client['Last_name'])); ?>')" 
                    class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
            <a href="index.php?controller=client&action=index" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    
<div class="detail-card">
        <div class="detail-header">
            <div style="display: flex; align-items: center; gap: 24px; flex: 1;">
                <div class="client-avatar" style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 36px; color: white;">
                    <i class="fas fa-user"></i>
                </div>
                <div class="client-info">
                    <h3><?php echo htmlspecialchars($client['First_name'] . ' ' . $client['Last_name']); ?></h3>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($client['Address']); ?></p>
                    <p><i class="fas fa-building"></i> <?php echo count($projects); ?> project(s) assigned</p>
                </div>
            </div>
            <div style="display: flex; gap: 24px; text-align: center;">
                <div style="padding: 16px 24px; background: var(--light); border-radius: 12px;">
                    <div style="font-size: 28px; font-weight: 700; color: var(--primary);"><?php echo count($projects); ?></div>
                    <div style="font-size: 12px; color: var(--gray); text-transform: uppercase;">Projects</div>
                </div>
            </div>
        </div>
        <div class="detail-stats">
            <div class="stat-item">
                <span class="stat-label">Client ID</span>
                <span class="stat-value">#<?php echo $client['Client_id']; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Address</span>
                <span class="stat-value" style="font-size: 16px;"><?php echo htmlspecialchars($client['Address']); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total Projects</span>
                <span class="stat-value"><?php echo count($projects); ?></span>
            </div>
        </div>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h3>Client Projects</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project Name</th>
                        <th>Budget</th>
                        <th>Start Date</th>
                        <th>Expected End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($projects as $project): ?>
                    <tr>
                        <td>#<?php echo $project['Project_id']; ?></td>
                        <td><?php echo htmlspecialchars($project['Project_name']); ?></td>
                        <td><?php echo number_format($project['Budget'], 3); ?> DT</td>
                        <td><?php echo date('d/m/Y', strtotime($project['Project_start_date'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($project['Project_expected_end_date'])); ?></td>
                        <td>
                            <a href="index.php?controller=project&action=view&id=<?php echo $project['Project_id']; ?>" 
                               class="btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
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
    if(confirm('Are you sure you want to delete the client "' + name + '"?\nThis action is irreversible.')) {
        window.location.href = 'index.php?controller=client&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>