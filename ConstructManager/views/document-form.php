<?php
$current_page = 'documents';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$is_edit = isset($document) && !empty($document);
$title = $is_edit ? 'Edit Document' : 'New Document';
$action = $is_edit ? 'update&id=' . $document['Document_id'] : 'store';
?>

<div class="page-container">
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
        <a href="index.php?controller=document&action=index" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-file-alt text-primary"></i> Document Information</h3>
        </div>
        
        <div class="form-container">
            <form action="index.php?controller=document&action=<?php echo $action; ?>" method="POST" enctype="multipart/form-data" class="crud-form">
                <div class="form-group">
                    <label for="Project_id">Project <span class="required">*</span></label>
                    <select id="Project_id" name="Project_id" required class="form-control">
                        <option value="">Select a project</option>
                        <?php foreach($projects as $proj): ?>
                        <option value="<?php echo $proj['Project_id']; ?>" 
                            <?php echo ($is_edit && $document['Project_id'] == $proj['Project_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($proj['Project_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="Document_type">Document Type <span class="required">*</span></label>
                    <select id="Document_type" name="Document_type" required class="form-control">
                        <option value="">Select a type</option>
                        <option value="Contract" <?php echo ($is_edit && $document['Document_type'] == 'Contract') ? 'selected' : ''; ?>>Contract</option>
                        <option value="Plan" <?php echo ($is_edit && $document['Document_type'] == 'Plan') ? 'selected' : ''; ?>>Plan</option>
                        <option value="Quote" <?php echo ($is_edit && $document['Document_type'] == 'Quote') ? 'selected' : ''; ?>>Quote</option>
                        <option value="QC Sheet" <?php echo ($is_edit && $document['Document_type'] == 'QC Sheet') ? 'selected' : ''; ?>>QC Sheet</option>
                        <option value="Approval" <?php echo ($is_edit && $document['Document_type'] == 'Approval') ? 'selected' : ''; ?>>Approval</option>
                        <option value="Other" <?php echo ($is_edit && $document['Document_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="Authorization_level">Authorization Level <span class="required">*</span></label>
                    <select id="Authorization_level" name="Authorization_level" required class="form-control">
                        <option value="">Select a level</option>
                        <option value="Public" <?php echo ($is_edit && $document['Authorization_level'] == 'Public') ? 'selected' : ''; ?>>Public</option>
                        <option value="Restricted" <?php echo ($is_edit && $document['Authorization_level'] == 'Restricted') ? 'selected' : ''; ?>>Restricted</option>
                        <option value="Confidential" <?php echo ($is_edit && $document['Authorization_level'] == 'Confidential') ? 'selected' : ''; ?>>Confidential</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="document_file">File <?php echo $is_edit ? '' : '<span class="required">*</span>'; ?></label>
                    <input type="file" id="document_file" name="document_file" 
                           <?php echo $is_edit ? '' : 'required'; ?> class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                    <?php if($is_edit && $document['Document_url']): ?>
                    <small class="text-muted">Current file: <?php echo basename($document['Document_url']); ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="Document_url">Or Document URL</label>
                    <input type="text" id="Document_url" name="Document_url" 
                           value="<?php echo $is_edit ? htmlspecialchars($document['Document_url']) : ''; ?>" 
                           class="form-control" placeholder="/docs/document.pdf">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Save'; ?>
                    </button>
                    <a href="index.php?controller=document&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>