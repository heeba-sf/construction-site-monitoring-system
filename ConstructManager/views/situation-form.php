<?php
$current_page = 'situations';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$is_edit = isset($situation) && !empty($situation);
$title = $is_edit ? 'Edit Situation' : 'New Situation';
$action = $is_edit ? 'update&id=' . $situation['Situation_id'] : 'store';
?>

<div class="page-container">
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
        <a href="index.php?controller=situation&action=index" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-tasks text-primary"></i> Situation Information</h3>
        </div>
        
        <div class="form-container">
            <form action="index.php?controller=situation&action=<?php echo $action; ?>" method="POST" class="crud-form" id="situationForm">
                <div class="form-group">
                    <label for="Project_id">Project <span class="required">*</span></label>
                    <select id="Project_id" name="Project_id" required class="form-control" onchange="loadWorks(this.value)">
                        <option value="">Select a project</option>
                        <?php foreach($projects as $proj): ?>
                        <option value="<?php echo $proj['Project_id']; ?>" 
                            <?php echo ($is_edit && $situation['Project_id'] == $proj['Project_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($proj['Project_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="Comments">Comment</label>
                    <textarea id="Comments" name="Comments" rows="3" class="form-control"><?php echo $is_edit ? htmlspecialchars($situation['Comments']) : ''; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="Start_date">Start Date <span class="required">*</span></label>
                        <input type="date" id="Start_date" name="Start_date" 
                               value="<?php echo $is_edit ? $situation['Start_date'] : date('Y-m-d'); ?>" 
                               required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="End_date">End Date <span class="required">*</span></label>
                        <input type="date" id="End_date" name="End_date" 
                               value="<?php echo $is_edit ? $situation['End_date'] : date('Y-m-d'); ?>" 
                               required class="form-control">
                    </div>
                </div>
                
                <?php if(!$is_edit): ?>
                <div id="works-section" style="display: none;">
                    <h4 style="margin: 30px 0 20px; color: var(--dark);">
                        <i class="fas fa-hard-hat text-primary"></i> Completed Works
                    </h4>
                    <div id="works-list"></div>
                </div>
                <?php endif; ?>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Save'; ?>
                    </button>
                    <a href="index.php?controller=situation&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadWorks(projectId) {
    if(!projectId) {
        document.getElementById('works-section').style.display = 'none';
        return;
    }
    
    fetch('index.php?controller=situation&action=getWorksByProject&project_id=' + projectId)
        .then(response => response.json())
        .then(works => {
            const section = document.getElementById('works-section');
            const list = document.getElementById('works-list');
            
            if(works.length > 0) {
                let html = '<div class="works-grid">';
                works.forEach(work => {
                    html += `
                        <div class="work-item">
                            <label>
                                <strong>${work.WorkP_name}</strong><br>
                                <small>Expected: ${work.Expected_quantity} ${work.Measurment_unit} | Price: ${work.Unit_price} DT</small>
                            </label>
                            <input type="number" 
                                   name="achievements[${work.WorkP_id}]" 
                                   min="0" 
                                   max="${work.Expected_quantity}"
                                   step="0.01"
                                   placeholder="Achieved quantity"
                                   class="form-control"
                                   style="margin-top: 8px;">
                        </div>
                    `;
                });
                html += '</div>';
                list.innerHTML = html;
                section.style.display = 'block';
            } else {
                list.innerHTML = '<p class="text-muted">No work packages found for this project.</p>';
                section.style.display = 'block';
            }
        });
}

<?php if($is_edit && isset($situation['Project_id'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    loadWorks(<?php echo $situation['Project_id']; ?>);
});
<?php endif; ?>
</script>

<?php require_once 'views/layout/footer.php'; ?>