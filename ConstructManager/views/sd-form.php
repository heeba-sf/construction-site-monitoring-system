<?php
$current_page = 'sd';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$is_edit = isset($sd) && !empty($sd);
$title   = $is_edit ? 'Edit Specification Document' : 'New Specification Document';
$action  = $is_edit ? 'update&id=' . $sd['SD_id'] : 'store';
?>

<div class="page-container">
    <div class="page-header">
        <h2><i class="fas fa-file-contract" style="color:var(--primary)"></i> <?php echo $title; ?></h2>
        <a href="index.php?controller=sd&action=index" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-file-alt text-primary"></i> SD Information</h3>
        </div>
        <div class="form-container">
            <form action="index.php?controller=sd&action=<?php echo $action; ?>" method="POST" class="crud-form">

                <?php if($is_edit): ?>
                <div class="form-group">
                    <label>SD ID</label>
                    <input type="text" class="form-control" value="#<?php echo $sd['SD_id']; ?>" disabled style="background:var(--light);font-weight:700">
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="Version">Version <span class="required">*</span></label>
                    <input type="text" id="Version" name="Version" required class="form-control"
                           placeholder="e.g. v1.0, v2.1"
                           value="<?php echo $is_edit ? htmlspecialchars($sd['Version']) : ''; ?>">
                    <small style="color:var(--gray);font-size:12px;margin-top:4px;display:block">
                        Use semantic versioning (e.g. v1.0 for initial, v1.1 for minor revision, v2.0 for major revision)
                    </small>
                </div>

                <div class="form-group">
                    <label for="date_creation">Creation Date <span class="required">*</span></label>
                    <input type="date" id="date_creation" name="date_creation" required class="form-control"
                           value="<?php echo $is_edit ? $sd['date_creation'] : date('Y-m-d'); ?>">
                </div>

                <?php if($is_edit && isset($sd['Project_name']) && $sd['Project_name']): ?>
                <div class="form-group">
                    <label>Linked Project</label>
                    <div style="padding:12px 16px;background:var(--light);border-radius:10px;border:2px solid var(--gray-lighter);display:flex;align-items:center;gap:10px">
                        <i class="fas fa-building" style="color:var(--primary)"></i>
                        <a href="index.php?controller=project&action=view&id=<?php echo $sd['Project_id']; ?>"
                           style="color:var(--primary);font-weight:600;text-decoration:none">
                            <?php echo htmlspecialchars($sd['Project_name']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Create SD'; ?>
                    </button>
                    <a href="index.php?controller=sd&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
