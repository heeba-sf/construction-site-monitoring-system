<?php
$current_page = 'documents';
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
            <h2>Document Management</h2>
            <p class="text-muted">Centralized documents for all your projects</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=document&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Document
            </a>
        </div>
    </div>
    
    <div class="stats-grid">
        <?php foreach($stats as $stat): ?>
        <div class="stat-card small">
            <div class="stat-icon">
                <?php
                $type_icons = [
                    'Contract' => 'fa-file-signature',
                    'Plan'     => 'fa-drafting-compass',
                    'Quote'    => 'fa-file-invoice-dollar',
                    'QC Sheet' => 'fa-clipboard-check',
                    'Approval' => 'fa-stamp',
                ];
                $icon = $type_icons[$stat['Document_type']] ?? 'fa-file-alt';
            ?>
            <i class="fas <?php echo $icon; ?>"></i>
            </div>
            <div class="stat-content">
                <h4><?php echo htmlspecialchars($stat['Document_type']); ?></h4>
                <p class="stat-number"><?php echo $stat['count']; ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Document</th>
                        <th>Upload Date</th>
                        <th>Authorization</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($documents as $document): ?>
                    <tr>
                        <td><span class="badge bg-primary-light">#<?php echo $document['Document_id']; ?></span></td>
                        <td>
                            <strong><?php echo htmlspecialchars($document['Project_name']); ?></strong>
                        </td>
                        <td>
                            <?php
                            $doc_icons = ['Contract'=>'fa-file-signature','Plan'=>'fa-drafting-compass','Quote'=>'fa-file-invoice-dollar','QC Sheet'=>'fa-clipboard-check','Approval'=>'fa-stamp'];
                            $doc_icon = $doc_icons[$document['Document_type']] ?? 'fa-file-alt';
                            ?>
                            <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $document['Document_type'])); ?>">
                                <i class="fas <?php echo $doc_icon; ?>"></i> <?php echo htmlspecialchars($document['Document_type']); ?>
                            </span>
                        </td>
                        <td>
                            <i class="fas fa-file-pdf text-danger"></i>
                            <?php echo htmlspecialchars(basename($document['Document_url'])); ?>
                        </td>
                        <td>
                            <i class="fas fa-calendar text-gray"></i>
                            <?php echo date('d/m/Y', strtotime($document['Upload_date'])); ?>
                        </td>
                        <td>
                            <?php
                            $auth_class = $document['Authorization_level'] == 'Confidential' ? 'badge-confidential' : 
                                         ($document['Authorization_level'] == 'Restricted' ? 'badge-restricted' : 'badge-public');
                            $auth_icon = $document['Authorization_level'] == 'Confidential' ? 'fa-lock' : 
                                    ($document['Authorization_level'] == 'Restricted' ? 'fa-user-lock' : 'fa-globe');
                            ?>
                            <span class="badge <?php echo $auth_class; ?>">
                                <i class="fas <?php echo $auth_icon; ?>"></i>
                                <?php echo htmlspecialchars($document['Authorization_level']); ?>
                            </span>
                        </td>
                        <td class="actions-cell">
                            <a href="<?php echo htmlspecialchars($document['Document_url']); ?>" 
                               class="btn-icon" title="Download" target="_blank" style="background: var(--success); color: white;">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="index.php?controller=document&action=edit&id=<?php echo $document['Document_id']; ?>" 
                               class="btn-icon" title="Edit" style="background: var(--warning); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $document['Document_id']; ?>, 'Document #<?php echo $document['Document_id']; ?>')" 
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
        window.location.href = 'index.php?controller=document&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>