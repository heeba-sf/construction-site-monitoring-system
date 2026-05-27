<?php
$current_page = 'sd';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2><i class="fas fa-file-contract" style="color:var(--primary)"></i> Specification Documents</h2>
            <p style="color:var(--gray);margin-top:4px;font-size:14px">Manage contractual SD documents and their work packages</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=sd&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> New SD
            </a>
        </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
        <i class="fas fa-<?php echo $_SESSION['message_type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <button class="close-alert" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>SD ID</th>
                        <th>Version</th>
                        <th>Creation Date</th>
                        <th>Linked Project</th>
                        <th>Work Packages</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($sds)): ?>
                    <tr><td colspan="6" style="text-align:center;color:var(--gray);padding:40px">No specification documents found.</td></tr>
                <?php else: foreach($sds as $sd): ?>
                    <tr>
                        <td><span style="font-weight:700;color:var(--primary)">#<?php echo $sd['SD_id']; ?></span></td>
                        <td>
                            <span class="badge bg-primary-light">
                                <i class="fas fa-code-branch"></i> <?php echo htmlspecialchars($sd['Version']); ?>
                            </span>
                        </td>
                        <td><?php echo $sd['date_creation'] ? date('d/m/Y', strtotime($sd['date_creation'])) : '—'; ?></td>
                        <td>
                            <?php if($sd['Project_id']): ?>
                                <a href="index.php?controller=project&action=view&id=<?php echo $sd['Project_id']; ?>"
                                   style="color:var(--primary);font-weight:600;text-decoration:none">
                                    <i class="fas fa-building"></i> <?php echo htmlspecialchars($sd['Project_name']); ?>
                                </a>
                            <?php else: ?>
                                <span style="color:var(--gray);font-style:italic">Not assigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-weight:700"><?php echo $sd['work_count']; ?></span>
                            <span style="color:var(--gray);font-size:13px"> packages</span>
                        </td>
                        <td class="actions-cell">
                            <a href="index.php?controller=sd&action=view&id=<?php echo $sd['SD_id']; ?>" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="index.php?controller=sd&action=edit&id=<?php echo $sd['SD_id']; ?>" class="btn-icon" title="Edit" style="background:var(--warning);color:#fff"><i class="fas fa-edit"></i></a>
                            <button onclick="confirmDelete(<?php echo $sd['SD_id']; ?>, 'SD #<?php echo $sd['SD_id']; ?> (<?php echo htmlspecialchars(addslashes($sd['Version'])); ?>)')" class="btn-icon" style="background:#fee2e2;color:var(--danger)" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Delete ' + name + '?\nThis will also delete all its Work Packages.')) {
        window.location.href = 'index.php?controller=sd&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>
