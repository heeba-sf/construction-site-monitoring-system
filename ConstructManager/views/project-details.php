<?php
$current_page = 'projects';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2><?php echo htmlspecialchars($project['Project_name']); ?></h2>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=situation&action=create&project_id=<?php echo $project['Project_id']; ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> New Situation
            </a>
            <a href="index.php?controller=work&action=create&project_id=<?php echo $project['Project_id']; ?>" class="btn btn-info">
                <i class="fas fa-hard-hat"></i> Add Work Package
            </a>
            <a href="index.php?controller=document&action=create&project_id=<?php echo $project['Project_id']; ?>" class="btn btn-secondary">
                <i class="fas fa-file"></i> Add Document
            </a>
            <a href="index.php?controller=project&action=report&id=<?php echo $project['Project_id']; ?>"
               class="btn btn-primary" target="_blank" title="Generate printable report">
                <i class="fas fa-print"></i> Generate Report
            </a>
            <a href="index.php?controller=project&action=edit&id=<?php echo $project['Project_id']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button onclick="confirmDelete(<?php echo $project['Project_id']; ?>, '<?php echo htmlspecialchars(addslashes($project['Project_name'])); ?>')"
                    class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
            <a href="index.php?controller=project&action=index" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- ── Project Header Card ─────────────────────────────── -->
    <div class="detail-card">
        <div class="detail-header">
            <div style="display:flex;align-items:center;gap:24px;flex:1">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:36px;color:white;">
                    <i class="fas fa-building"></i>
                </div>
                <div class="project-info">
                    <h3><?php echo htmlspecialchars($project['Project_name']); ?></h3>
                    <div class="project-meta">
                        <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($project['First_name'] . ' ' . $project['Last_name']); ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($project['Address']); ?></p>
                        <p><i class="fas fa-file-contract"></i> SD:
                            <a href="index.php?controller=sd&action=view&id=<?php echo $project['SD_id']; ?>"
                               style="color:var(--primary);font-weight:700;text-decoration:none">
                               <?php echo htmlspecialchars($project['Version']); ?> →
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="project-progress-large">
                <div class="progress-circle">
                    <svg width="120" height="120">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="var(--gray-lighter)" stroke-width="12"/>
                        <circle cx="60" cy="60" r="54" fill="none" stroke="var(--primary)" stroke-width="12"
                                stroke-dasharray="339.292"
                                stroke-dashoffset="<?php echo 339.292 * (1 - $progress/100); ?>"
                                transform="rotate(-90 60 60)"/>
                        <text x="30" y="65" text-anchor="start" font-size="20" font-weight="bold" fill="var(--primary)">
                            <?php echo $progress; ?>%
                        </text>
                    </svg>
                </div>
            </div>
        </div>
        <div class="detail-stats">
            <div class="stat-item"><span class="stat-label">Project ID</span><span class="stat-value">#<?php echo $project['Project_id']; ?></span></div>
            <div class="stat-item"><span class="stat-label">Total Budget</span><span class="stat-value"><?php echo number_format($project['Budget'], 3); ?> DT</span></div>
            <div class="stat-item"><span class="stat-label">Start Date</span><span class="stat-value"><?php echo date('d/m/Y', strtotime($project['Project_start_date'])); ?></span></div>
            <div class="stat-item"><span class="stat-label">Expected End</span><span class="stat-value"><?php echo date('d/m/Y', strtotime($project['Project_expected_end_date'])); ?></span></div>
            <div class="stat-item"><span class="stat-label">Work Packages</span><span class="stat-value"><?php echo count($works); ?></span></div>
            <div class="stat-item"><span class="stat-label">Situations</span><span class="stat-value"><?php echo count($situations); ?></span></div>
            <div class="stat-item"><span class="stat-label">Documents</span><span class="stat-value"><?php echo count($documents); ?></span></div>
        </div>
    </div>

    <!-- ── Financial Health Card (Feature 1) ──────────────── -->
    <?php
    $fin        = $financial;
    $cons       = (float)$fin['consumption'];
    $bar_color  = $cons < 60 ? 'var(--success)' : ($cons < 90 ? 'var(--warning)' : 'var(--danger)');
    $badge_bg   = $cons < 60 ? '#dcfce7' : ($cons < 90 ? '#fef9c3' : '#fee2e2');
    $badge_txt  = $cons < 60 ? 'var(--success)' : ($cons < 90 ? '#92400e' : 'var(--danger)');
    $status_icon= $cons < 60 ? 'fa-circle-check' : ($cons < 90 ? 'fa-triangle-exclamation' : 'fa-circle-xmark');
    ?>
    <div class="content-card" style="margin-top:20px;border-left:4px solid <?php echo $bar_color; ?>">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:18px">
            <h3 style="margin:0;font-size:16px;font-weight:700;color:var(--dark)">
                <i class="fas fa-chart-line" style="color:var(--primary)"></i>&nbsp; Financial Health
            </h3>
            <span style="background:<?php echo $badge_bg; ?>;color:<?php echo $badge_txt; ?>;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;letter-spacing:.5px">
                <i class="fas <?php echo $status_icon; ?>"></i>&nbsp; <?php echo $fin['status']; ?>
            </span>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:18px">
            <div style="background:var(--light);border-radius:12px;padding:14px 16px">
                <div style="font-size:11px;color:var(--gray);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Budget</div>
                <div style="font-size:18px;font-weight:700;color:var(--dark);margin-top:4px"><?php echo number_format($fin['budget'],3); ?> DT</div>
            </div>
            <div style="background:var(--light);border-radius:12px;padding:14px 16px">
                <div style="font-size:11px;color:var(--gray);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Planned Cost</div>
                <div style="font-size:18px;font-weight:700;color:var(--dark);margin-top:4px"><?php echo number_format($fin['planned_cost'],3); ?> DT</div>
            </div>
            <div style="background:var(--light);border-radius:12px;padding:14px 16px">
                <div style="font-size:11px;color:var(--gray);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Earned Value</div>
                <div style="font-size:18px;font-weight:700;color:var(--primary);margin-top:4px"><?php echo number_format($fin['earned_value'],3); ?> DT</div>
            </div>
            <div style="background:var(--light);border-radius:12px;padding:14px 16px">
                <div style="font-size:11px;color:var(--gray);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Remaining Budget</div>
                <div style="font-size:18px;font-weight:700;color:<?php echo $fin['remaining'] < 0 ? 'var(--danger)' : 'var(--success)'; ?>;margin-top:4px">
                    <?php echo number_format($fin['remaining'],3); ?> DT
                </div>
            </div>
        </div>

        <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px;font-weight:600">
                <span style="color:var(--gray)">Budget Consumption</span>
                <span style="color:<?php echo $bar_color; ?>"><?php echo $cons; ?>%</span>
            </div>
            <div style="background:var(--gray-lighter);border-radius:99px;height:10px;overflow:hidden">
                <div style="width:<?php echo min($cons,100); ?>%;background:<?php echo $bar_color; ?>;height:100%;border-radius:99px;transition:width .6s ease"></div>
            </div>
        </div>
    </div>

    <!-- ── Tabs ───────────────────────────────────────────── -->
    <div class="tabs-container">
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('works',this)"><i class="fas fa-hard-hat"></i> Works</button>
            <button class="tab-btn" onclick="showTab('situations',this)"><i class="fas fa-tasks"></i> Situations</button>
            <button class="tab-btn" onclick="showTab('documents',this)"><i class="fas fa-folder"></i> Documents</button>
        </div>

        <!-- Works Tab (Feature 2: color-coded bars + remaining qty) -->
        <div id="works" class="tab-content active">
            <div class="content-card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Work Package</th>
                                <th>Expected Qty</th>
                                <th>Unit</th>
                                <th>Unit Price</th>
                                <th>Achieved Qty</th>
                                <th>Remaining</th>
                                <th>Completion</th>
                                <th>Total Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_amount = 0;
                            foreach($works as $work):
                                $pct        = min((float)$work['completion_rate'], 100);
                                $line_total = $work['total_achieved'] * $work['Unit_price'];
                                $total_amount += $line_total;
                                $remaining_qty = max(0, $work['Expected_quantity'] - $work['total_achieved']);

                                // Feature 2: color thresholds
                                if ($pct >= 75)      $bar_col = 'var(--success)';
                                elseif ($pct >= 30)  $bar_col = 'var(--warning)';
                                else                 $bar_col = 'var(--danger)';

                                $rem_color = $remaining_qty == 0 ? 'var(--success)' : 'var(--danger)';
                            ?>
                            <tr>
                                <td>
                                    <a href="index.php?controller=work&action=view&id=<?php echo $work['WorkP_id']; ?>" class="work-link">
                                        <?php echo htmlspecialchars($work['WorkP_name']); ?>
                                    </a>
                                </td>
                                <td><?php echo number_format($work['Expected_quantity'], 2); ?></td>
                                <td><span class="badge bg-primary-light"><?php echo htmlspecialchars($work['Measurment_unit']); ?></span></td>
                                <td><?php echo number_format($work['Unit_price'], 3); ?> DT</td>
                                <td><?php echo number_format($work['total_achieved'], 2); ?></td>
                                <td style="font-weight:700;color:<?php echo $rem_color; ?>">
                                    <?php echo $remaining_qty == 0
                                        ? '<i class="fas fa-check-circle"></i> 0'
                                        : number_format($remaining_qty, 2); ?>
                                </td>
                                <td style="min-width:130px">
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <div style="flex:1;background:var(--gray-lighter);border-radius:99px;height:8px;overflow:hidden">
                                            <div style="width:<?php echo $pct; ?>%;background:<?php echo $bar_col; ?>;height:100%;border-radius:99px;transition:width .5s"></div>
                                        </div>
                                        <span style="font-size:12px;font-weight:700;color:<?php echo $bar_col; ?>;min-width:36px;text-align:right"><?php echo round($pct,1); ?>%</span>
                                    </div>
                                </td>
                                <td><?php echo number_format($line_total, 3); ?> DT</td>
                                <td class="actions-cell">
                                    <a href="index.php?controller=work&action=edit&id=<?php echo $work['WorkP_id']; ?>"
                                       class="btn-icon" title="Edit" style="background:var(--warning);color:white;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?controller=work&action=delete&id=<?php echo $work['WorkP_id']; ?>"
                                       class="btn-icon" title="Delete" style="background:var(--danger);color:white;"
                                       onclick="return confirm('Delete this work package? This cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="7"><strong>Grand Total (Earned)</strong></td>
                                <td><strong><?php echo number_format($total_amount, 3); ?> DT</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Situations Tab -->
        <div id="situations" class="tab-content">
            <div class="content-card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Comment</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($situations as $situation): ?>
                            <tr>
                                <td>#<?php echo $situation['Situation_id']; ?></td>
                                <td><?php echo htmlspecialchars($situation['Comments']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($situation['Start_date'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($situation['End_date'])); ?></td>
                                <td class="actions-cell">
                                    <a href="index.php?controller=situation&action=view&id=<?php echo $situation['Situation_id']; ?>" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="index.php?controller=situation&action=edit&id=<?php echo $situation['Situation_id']; ?>" class="btn-icon" title="Edit" style="background:var(--warning);color:white;"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?controller=situation&action=delete&id=<?php echo $situation['Situation_id']; ?>" class="btn-icon" title="Delete" style="background:var(--danger);color:white;" onclick="return confirm('Delete this situation?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Documents Tab -->
        <div id="documents" class="tab-content">
            <div class="content-card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>File</th>
                                <th>Upload Date</th>
                                <th>Authorization</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($documents as $document):
                                $doc_icons = ['Contract'=>'fa-file-signature','Plan'=>'fa-drafting-compass','Quote'=>'fa-file-invoice-dollar','QC Sheet'=>'fa-clipboard-check','Approval'=>'fa-stamp'];
                                $doc_icon  = $doc_icons[$document['Document_type']] ?? 'fa-file-alt';
                                $auth_icon = $document['Authorization_level'] == 'Confidential' ? 'fa-lock' : ($document['Authorization_level'] == 'Restricted' ? 'fa-user-lock' : 'fa-globe');
                                $auth_cls  = 'badge-' . strtolower($document['Authorization_level']);
                            ?>
                            <tr>
                                <td>
                                    <span class="badge badge-<?php echo strtolower(str_replace(' ','-',$document['Document_type'])); ?>">
                                        <i class="fas <?php echo $doc_icon; ?>"></i> <?php echo htmlspecialchars($document['Document_type']); ?>
                                    </span>
                                </td>
                                <td><i class="fas fa-file-pdf text-danger"></i> <?php echo htmlspecialchars(basename($document['Document_url'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($document['Upload_date'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $auth_cls; ?>">
                                        <i class="fas <?php echo $auth_icon; ?>"></i> <?php echo htmlspecialchars($document['Authorization_level']); ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <a href="<?php echo htmlspecialchars($document['Document_url']); ?>" target="_blank" class="btn-icon" title="Download" style="background:var(--success);color:white;"><i class="fas fa-download"></i></a>
                                    <a href="index.php?controller=document&action=edit&id=<?php echo $document['Document_id']; ?>" class="btn-icon" title="Edit" style="background:var(--warning);color:white;"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?controller=document&action=delete&id=<?php echo $document['Document_id']; ?>" class="btn-icon" title="Delete" style="background:var(--danger);color:white;" onclick="return confirm('Delete this document?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}
function confirmDelete(id, name) {
    if (confirm('Delete project "' + name + '"?\nThis is irreversible.')) {
        window.location.href = 'index.php?controller=project&action=delete&id=' + id;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>
