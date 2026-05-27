<?php
$current_page = 'sd';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2><i class="fas fa-file-contract" style="color:var(--primary)"></i>
                SD #<?php echo $sd['SD_id']; ?> — <?php echo htmlspecialchars($sd['Version']); ?>
            </h2>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=work&action=create&sd_id=<?php echo $sd['SD_id']; ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Work Package
            </a>
            <a href="index.php?controller=sd&action=edit&id=<?php echo $sd['SD_id']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button onclick="confirmDelete(<?php echo $sd['SD_id']; ?>)" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
            <a href="index.php?controller=sd&action=index" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- SD Header Card -->
    <div class="detail-card">
        <div class="detail-header">
            <div style="display:flex;align-items:center;gap:24px;flex:1">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:36px;color:white;">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div>
                    <h3 style="margin:0;font-size:22px">Specification Document</h3>
                    <div style="display:flex;gap:16px;margin-top:8px;flex-wrap:wrap">
                        <span style="color:var(--gray);font-size:14px"><i class="fas fa-code-branch"></i> Version: <strong><?php echo htmlspecialchars($sd['Version']); ?></strong></span>
                        <span style="color:var(--gray);font-size:14px"><i class="fas fa-calendar"></i> Created: <strong><?php echo $sd['date_creation'] ? date('d/m/Y', strtotime($sd['date_creation'])) : '—'; ?></strong></span>
                        <?php if($sd['Project_name']): ?>
                        <span style="color:var(--gray);font-size:14px"><i class="fas fa-building"></i> Project:
                            <a href="index.php?controller=project&action=view&id=<?php echo $sd['Project_id']; ?>"
                               style="color:var(--primary);font-weight:600;text-decoration:none">
                                <?php echo htmlspecialchars($sd['Project_name']); ?>
                            </a>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="detail-stats">
            <div class="stat-item">
                <span class="stat-label">SD ID</span>
                <span class="stat-value">#<?php echo $sd['SD_id']; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Work Packages</span>
                <span class="stat-value"><?php echo $sd['work_count']; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Contract Value</span>
                <span class="stat-value"><?php echo number_format($contract_value, 3); ?> DT</span>
            </div>
            <?php if($sd['First_name']): ?>
            <div class="stat-item">
                <span class="stat-label">Client</span>
                <span class="stat-value"><?php echo htmlspecialchars($sd['First_name'] . ' ' . $sd['Last_name']); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Work Packages Table -->
    <div class="content-card" style="margin-top:24px">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
            <h3><i class="fas fa-hard-hat text-primary"></i> Work Packages</h3>
            <a href="index.php?controller=work&action=create&sd_id=<?php echo $sd['SD_id']; ?>" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add
            </a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Work Package Name</th>
                        <th>Expected Qty</th>
                        <th>Unit</th>
                        <th>Unit Price (DT)</th>
                        <th>Total (DT)</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($works)): ?>
                    <tr><td colspan="8" style="text-align:center;color:var(--gray);padding:32px">No work packages yet. <a href="index.php?controller=work&action=create&sd_id=<?php echo $sd['SD_id']; ?>">Add one →</a></td></tr>
                <?php else:
                    $grand_total = 0;
                    foreach($works as $w):
                        $line_total = $w['Expected_quantity'] * $w['Unit_price'];
                        $grand_total += $line_total;
                        $pct = min((float)$w['completion_rate'], 100);
                        $bar_color = $pct >= 100 ? 'var(--success)' : ($pct >= 60 ? 'var(--primary)' : ($pct >= 30 ? 'var(--warning)' : 'var(--danger)'));
                ?>
                    <tr>
                        <td style="font-weight:700;color:var(--primary)"><?php echo $w['WorkP_id']; ?></td>
                        <td style="font-weight:600"><?php echo htmlspecialchars($w['WorkP_name']); ?></td>
                        <td><?php echo number_format($w['Expected_quantity']); ?></td>
                        <td><span class="badge bg-primary-light"><?php echo htmlspecialchars($w['Measurment_unit']); ?></span></td>
                        <td><?php echo number_format($w['Unit_price'], 3); ?></td>
                        <td style="font-weight:700"><?php echo number_format($line_total, 3); ?></td>
                        <td style="min-width:120px">
                            <div style="background:var(--gray-lighter);border-radius:99px;height:8px;overflow:hidden">
                                <div style="width:<?php echo $pct; ?>%;background:<?php echo $bar_color; ?>;height:100%;border-radius:99px;transition:width 0.5s"></div>
                            </div>
                            <span style="font-size:12px;color:var(--gray)"><?php echo $pct; ?>%</span>
                        </td>
                        <td class="actions-cell">
                            <a href="index.php?controller=work&action=edit&id=<?php echo $w['WorkP_id']; ?>" class="btn-icon" style="background:var(--warning);color:#fff" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="index.php?controller=work&action=delete&id=<?php echo $w['WorkP_id']; ?>" class="btn-icon" style="background:#fee2e2;color:var(--danger)" title="Delete" onclick="return confirm('Delete this work package?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    <tr style="background:var(--light);font-weight:700">
                        <td colspan="5" style="text-align:right;padding-right:16px">Total Contract Value:</td>
                        <td style="color:var(--primary);font-size:16px"><?php echo number_format($grand_total, 3); ?> DT</td>
                        <td colspan="2"></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Delete this Specification Document?\nThis will also delete all its Work Packages.')) {
        window.location.href = 'index.php?controller=sd&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>
