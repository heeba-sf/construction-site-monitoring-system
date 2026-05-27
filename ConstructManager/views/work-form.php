<?php
$current_page = 'works';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$is_edit = isset($work) && !empty($work);
$title   = $is_edit ? 'Edit Work Package' : 'New Work Package';
$action  = $is_edit ? 'update&id=' . $work['WorkP_id'] : 'store';

// SD passed via URL (from project-details or sd-details)
$prefill_sd = isset($sd_id) ? (int)$sd_id : ($is_edit ? (int)$work['SD_id'] : null);
?>

<div class="page-container">
    <div class="page-header">
        <h2><i class="fas fa-hard-hat" style="color:var(--primary)"></i> <?php echo $title; ?></h2>
        <?php
        $back = 'index.php?controller=work&action=index';
        if ($prefill_sd) $back = 'index.php?controller=sd&action=view&id=' . $prefill_sd;
        ?>
        <a href="<?php echo $back; ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-hard-hat text-primary"></i> Work Package Information</h3>
        </div>
        <div class="form-container">
            <form action="index.php?controller=work&action=<?php echo $action; ?>" method="POST" class="crud-form">

                <!-- SD assignment -->
                <?php if ($is_edit): ?>
                    <input type="hidden" name="SD_id" value="<?php echo $work['SD_id']; ?>">
                    <div class="form-group">
                        <label>Specification Document</label>
                        <div style="padding:12px 16px;background:var(--light);border-radius:10px;border:2px solid var(--gray-lighter);display:flex;align-items:center;gap:10px">
                            <i class="fas fa-file-contract" style="color:var(--primary)"></i>
                            <a href="index.php?controller=sd&action=view&id=<?php echo $work['SD_id']; ?>"
                               style="color:var(--primary);font-weight:600;text-decoration:none">
                                SD #<?php echo $work['SD_id']; ?> — <?php echo htmlspecialchars($work['Version'] ?? ''); ?>
                            </a>
                        </div>
                    </div>
                <?php elseif ($prefill_sd): ?>
                    <input type="hidden" name="SD_id" value="<?php echo $prefill_sd; ?>">
                    <div class="form-group">
                        <label>Specification Document</label>
                        <div style="padding:12px 16px;background:var(--light);border-radius:10px;border:2px solid var(--gray-lighter);display:flex;align-items:center;gap:10px">
                            <i class="fas fa-file-contract" style="color:var(--primary)"></i>
                            <a href="index.php?controller=sd&action=view&id=<?php echo $prefill_sd; ?>"
                               style="color:var(--primary);font-weight:600;text-decoration:none">
                                SD #<?php echo $prefill_sd; ?>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="SD_id">Specification Document <span class="required">*</span></label>
                        <select id="SD_id" name="SD_id" required class="form-control">
                            <option value="">— Select an SD —</option>
                            <?php if (!empty($all_sds)): foreach($all_sds as $s): ?>
                            <option value="<?php echo $s['SD_id']; ?>">
                                SD #<?php echo $s['SD_id']; ?> — <?php echo htmlspecialchars($s['Version']); ?>
                                <?php echo $s['Project_name'] ? ' (' . htmlspecialchars($s['Project_name']) . ')' : ' (unassigned)'; ?>
                            </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="WorkP_name">Work Package Name <span class="required">*</span></label>
                    <input type="text" id="WorkP_name" name="WorkP_name" required class="form-control"
                           placeholder="e.g. Earthworks, RC Structure, Facade Coating"
                           value="<?php echo $is_edit ? htmlspecialchars($work['WorkP_name']) : ''; ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="Expected_quantity">Expected Quantity <span class="required">*</span></label>
                        <input type="number" step="0.01" id="Expected_quantity" name="Expected_quantity"
                               required class="form-control" placeholder="e.g. 500"
                               value="<?php echo $is_edit ? $work['Expected_quantity'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="Measurment_unit">Unit <span class="required">*</span></label>
                        <select id="Measurment_unit" name="Measurment_unit" required class="form-control">
                            <option value="">Select</option>
                            <?php foreach(['m3','m2','ml','unit','tonne','lumpsum'] as $u): ?>
                            <option value="<?php echo $u; ?>" <?php echo ($is_edit && $work['Measurment_unit'] == $u) ? 'selected' : ''; ?>>
                                <?php echo $u; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Unit_price">Unit Price (DT) <span class="required">*</span></label>
                    <input type="number" step="0.001" id="Unit_price" name="Unit_price"
                           required class="form-control" placeholder="e.g. 45.000"
                           value="<?php echo $is_edit ? $work['Unit_price'] : ''; ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $is_edit ? 'Update' : 'Save'; ?>
                    </button>
                    <a href="<?php echo $back; ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
