<?php
$current_page = 'projects';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$is_edit = isset($project) && !empty($project);
$title = $is_edit ? 'Edit Project' : 'New Project';
$action = $is_edit ? 'update&id=' . $project['Project_id'] : 'store';
$has_clients = !empty($clients);
?>

<div class="page-container">
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
        <a href="index.php?controller=project&action=index" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    
    <?php if(!$has_clients && !$is_edit): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span>No client exists in the database.</span>
        <a href="index.php?controller=client&action=create" class="btn btn-primary" style="margin-left: auto;">
            <i class="fas fa-plus"></i> Create a client first
        </a>
    </div>
    <?php else: ?>
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-project-diagram text-primary"></i> 
                <?php echo $is_edit ? 'Edit Information' : 'Fill Information'; ?>
            </h3>
        </div>
        
        <div class="form-container">
            <form action="index.php?controller=project&action=<?php echo $action; ?>" method="POST" class="crud-form">
                <div class="form-group">
                    <label for="Client_id">Client <span class="required">*</span></label>
                    <select id="Client_id" name="Client_id" required class="form-control">
                        <option value="">Select a client</option>
                        <?php foreach($clients as $client): ?>
                        <option value="<?php echo $client['Client_id']; ?>" 
                            <?php echo ($is_edit && $project['Client_id'] == $client['Client_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($client['First_name'] . ' ' . $client['Last_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="Project_name">Project Name <span class="required">*</span></label>
<input type="text" id="Project_name" name="Project_name" 
                           value="<?php echo $is_edit ? htmlspecialchars($project['Project_name']) : ''; ?>" 
                           placeholder="Enter project name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="Budget">Budget (DT) <span class="required">*</span></label>
<input type="number" step="0.001" id="Budget" name="Budget" 
                           value="<?php echo $is_edit ? $project['Budget'] : ''; ?>" 
                           placeholder="Enter budget amount" required class="form-control">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="Project_start_date">Start Date <span class="required">*</span></label>
                        <input type="date" id="Project_start_date" name="Project_start_date" 
                               value="<?php echo $is_edit ? $project['Project_start_date'] : ''; ?>" 
                               required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="Project_expected_end_date">Expected End Date <span class="required">*</span></label>
                        <input type="date" id="Project_expected_end_date" name="Project_expected_end_date" 
                               value="<?php echo $is_edit ? $project['Project_expected_end_date'] : ''; ?>" 
                               required class="form-control">
                    </div>
                </div>
                
                <?php if(!$is_edit): ?>
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <span>An SD document (v1.0) will be automatically created for this project.</span>
                </div>
                <?php endif; ?>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Create Project'; ?>
                    </button>
                    <a href="index.php?controller=project&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layout/footer.php'; ?>