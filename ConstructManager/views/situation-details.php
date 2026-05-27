
<?php
$current_page = 'situations';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2>Situation #<?php echo $situation['Situation_id']; ?></h2>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=situation&action=edit&id=<?php echo $situation['Situation_id']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button onclick="confirmDeleteSituation(<?php echo $situation['Situation_id']; ?>)" 
                    class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
            <a href="index.php?controller=situation&action=index" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    
    <div class="detail-card">
        <div class="detail-header">
            <div class="situation-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="situation-info">
                <p><strong>Project:</strong> <?php echo htmlspecialchars($situation['Project_name']); ?></p>
                <p><strong>Client:</strong> <?php echo htmlspecialchars($situation['First_name'] . ' ' . $situation['Last_name']); ?></p>
                <p><strong>Comment:</strong> <?php echo htmlspecialchars($situation['Comments']); ?></p>
            </div>
        </div>
        <div class="detail-stats">
            <div class="stat-item">
                <span class="stat-label">Start Date</span>
                <span class="stat-value"><?php echo date('d/m/Y', strtotime($situation['Start_date'])); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">End Date</span>
                <span class="stat-value"><?php echo date('d/m/Y', strtotime($situation['End_date'])); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Number of Works</span>
                <span class="stat-value"><?php echo $situation['work_count']; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total Cost</span>
                <span class="stat-value"><?php echo number_format($situation['total_cost'], 3); ?> DT</span>
            </div>
        </div>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h3>Completed Works</h3>
            <button class="btn btn-primary" onclick="showAddWorkModal()">
                <i class="fas fa-plus"></i> Add Work Package
            </button>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Work Package</th>
                        <th>Achieved Quantity</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($works as $work): 
                        $total += $work['line_total'];
                    ?>
                    <tr>
                        <td>
                                <a href="index.php?controller=work&action=view&id=<?php echo $work['WorkP_id']; ?>" class="work-link">
                                    <?php echo htmlspecialchars($work['WorkP_name']); ?>
                                </a>
                            </td>
                        <td>
                            <span id="qty-display-<?php echo $work['WorkP_id']; ?>">
                                <?php echo number_format($work['Achieved_quantity'], 2); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($work['Measurment_unit']); ?></td>
                        <td><?php echo number_format($work['Unit_price'], 3); ?> DT</td>
                        <td><?php echo number_format($work['line_total'], 3); ?> DT</td>
                        <td class="actions-cell">
                            <a href="javascript:void(0);" onclick="editWorkQuantity(<?php echo $work['WorkP_id']; ?>, <?php echo $work['Achieved_quantity']; ?>)" 
                                    class="btn-icon" title="Edit Quantity">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?controller=situation&action=deleteWork&situation_id=<?php echo $situation['Situation_id']; ?>&workP_id=<?php echo $work['WorkP_id']; ?>" 
                                    class="btn-icon" title="Remove" style="background: var(--danger); color: white;"
                                    onclick="return confirm('Are you sure you want to remove this work package?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4"><strong>Total</strong></td>
                        <td><strong><?php echo number_format($total, 3); ?> DT</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="workModal" class="modal" style="display: none;" onclick="event.stopPropagation()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalTitle">Add Work Package</h3>
            <button type="button" onclick="closeModal()" class="close-modal">&times;</button>
        </div>
        
        <div id="addWorkSection">
            <form action="index.php?controller=situation&action=addWork&id=<?php echo $situation['Situation_id']; ?>" method="POST" onsubmit="return true;">
                <div class="form-group">
                    <label for="WorkP_id">Select Work Package</label>
                    <select name="WorkP_id" id="workPackageSelect" required class="form-control">
                        <option value="">-- Select --</option>
                        <?php foreach($availableWorks as $aw): ?>
                        <option value="<?php echo $aw['WorkP_id']; ?>">
                            <?php echo htmlspecialchars($aw['WorkP_name'] . ' - ' . $aw['Expected_quantity'] . ' ' . $aw['Measurment_unit']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Achieved_quantity">Achieved Quantity</label>
                    <input type="number" name="Achieved_quantity" step="0.01" min="0" required class="form-control" placeholder="Enter quantity">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Work
                    </button>
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        
        <div id="editWorkSection" style="display: none;">
            <form id="editWorkForm" action="index.php?controller=situation&action=updateWork&id=<?php echo $situation['Situation_id']; ?>" method="POST">
                <input type="hidden" name="WorkP_id" id="editAchieveId">
                <div class="form-group">
                    <label for="editQuantity">Achieved Quantity</label>
                    <input type="number" name="Achieved_quantity" id="editQuantity" step="0.01" min="0" required class="form-control">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-primary" onclick="submitEditForm()">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}
.modal-content {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 24px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.modal-header h3 {
    margin: 0;
    color: var(--dark);
}
.close-modal {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: var(--gray);
    line-height: 1;
}
.close-modal:hover {
    color: var(--danger);
}
</style>

<script>
function showAddWorkModal(e) {
    if(e) e.preventDefault();
    document.getElementById('modalTitle').textContent = 'Add Work Package';
    document.getElementById('addWorkSection').style.display = 'block';
    document.getElementById('editWorkSection').style.display = 'none';
    document.getElementById('workModal').style.display = 'flex';
    return false;
}

function editWorkQuantity(workPId, currentQuantity) {
    document.getElementById('modalTitle').textContent = 'Update Work Quantity';
    document.getElementById('addWorkSection').style.display = 'none';
    document.getElementById('editWorkSection').style.display = 'block';
    document.getElementById('editAchieveId').value = workPId;
    document.getElementById('editQuantity').value = currentQuantity;
    document.getElementById('workModal').style.display = 'flex';
    return false;
}

function closeModal(e) {
    if(e) e.preventDefault();
    document.getElementById('workModal').style.display = 'none';
    return false;
}

function submitEditForm() {
    document.getElementById('editWorkForm').submit();
}

function confirmDeleteWork(situationId, workPId, workName) {
    if(confirm('Are you sure you want to remove "' + workName + '" from this situation?')) {
        window.location.href = 'index.php?controller=situation&action=deleteWork&situation_id=' + situationId + '&workP_id=' + workPId;
    }
    return false;
}

function confirmDeleteSituation(id) {
    if(confirm('Are you sure you want to delete this situation? This action is irreversible.')) {
        window.location.href = 'index.php?controller=situation&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>