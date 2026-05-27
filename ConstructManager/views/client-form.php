<?php
$current_page = 'clients';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$is_edit = isset($client) && !empty($client);
$title = $is_edit ? 'Edit Client' : 'New Client';
$action = $is_edit ? 'update&id=' . $client['Client_id'] : 'store';
?>

<div class="page-container">
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
        <a href="index.php?controller=client&action=index" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-user-<?php echo $is_edit ? 'edit' : 'plus'; ?> text-primary"></i> 
                <?php echo $is_edit ? 'Edit Information' : 'Fill Information'; ?>
            </h3>
        </div>
        
        <div class="form-container">
            <form action="index.php?controller=client&action=<?php echo $action; ?>" method="POST" class="crud-form">
                <div class="form-group">
                    <label for="First_name">First Name <span class="required">*</span></label>
<input type="text" id="First_name" name="First_name" 
                           value="<?php echo $is_edit ? htmlspecialchars($client['First_name']) : ''; ?>" 
                           placeholder="Enter first name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="Last_name">Last Name <span class="required">*</span></label>
<input type="text" id="Last_name" name="Last_name" 
                           value="<?php echo $is_edit ? htmlspecialchars($client['Last_name']) : ''; ?>" 
                           placeholder="Enter last name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="Address">Address <span class="required">*</span></label>
                    <textarea id="Address" name="Address" rows="3" placeholder="Enter address" required class="form-control"><?php echo $is_edit ? htmlspecialchars($client['Address']) : ''; ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Save'; ?>
                    </button>
                    <a href="index.php?controller=client&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>