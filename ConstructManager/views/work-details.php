<?php
$current_page = 'works';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

$budgetedCost = $work['Expected_quantity'] * $work['Unit_price'];
$latestQuantity = $latest ? $latest['Achieved_quantity'] : 0;
$currentTotalEarned = $latestQuantity * $work['Unit_price'];
$completionPercent = $work['Expected_quantity'] > 0 ? min(100, ($latestQuantity / $work['Expected_quantity']) * 100) : 0;
$isCompleted = $latestQuantity >= $work['Expected_quantity'];

$prevQuantity = 0;
$periodData = [];
$totalSituations = count($achievements);
$quantities = array_column($achievements, 'Achieved_quantity');

foreach($achievements as $idx => $ach) {
    $periodWork = $ach['Achieved_quantity'] - $prevQuantity;
    $cumulativeAmount = $ach['Achieved_quantity'] * $work['Unit_price'];
    $periodAmount = $periodWork * $work['Unit_price'];
    $periodProgress = $work['Expected_quantity'] > 0 ? min(100, ($ach['Achieved_quantity'] / $work['Expected_quantity']) * 100) : 0;
    $isLast = ($idx == count($achievements) - 1);
    $isComplete = $ach['Achieved_quantity'] >= $work['Expected_quantity'];
    
    $periodData[] = [
        'number' => $idx + 1,
        'situation_id' => $ach['Situation_id'],
        'comments' => $ach['Comments'],
        'start_date' => $ach['Start_date'],
        'end_date' => $ach['End_date'],
        'project_id' => $ach['Project_id'],
        'project_name' => $ach['Project_name'],
        'cumulative_qty' => $ach['Achieved_quantity'],
        'period_work' => $periodWork,
        'cumulative_amount' => $cumulativeAmount,
        'period_amount' => $periodAmount,
        'progress' => $periodProgress,
        'is_last' => $isLast,
        'is_complete' => $isComplete
    ];
    
    $prevQuantity = $ach['Achieved_quantity'];
}

$firstProgress = $totalSituations > 0 ? $achievements[0]['Achieved_quantity'] : 0;
$latestProgress = $latestQuantity;
$avgPerSituation = $totalSituations > 0 ? $latestQuantity / $totalSituations : 0;
$largestJump = 0;
if(count($quantities) > 1) {
    for($i = 1; $i < count($quantities); $i++) {
        $jump = $quantities[$i] - $quantities[$i-1];
        if($jump > $largestJump) $largestJump = $jump;
    }
}
$remainingQty = max(0, $work['Expected_quantity'] - $latestQuantity);
$remainingCost = $remainingQty * $work['Unit_price'];
?>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2><?php echo htmlspecialchars($work['WorkP_name']); ?></h2>
            <p class="text-muted">Work Package Details</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controller=project&action=view&id=<?php echo $work['Project_id']; ?>" class="btn btn-primary">
                <i class="fas fa-building"></i> View Project
            </a>
            <a href="index.php?controller=work&action=index" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="content-card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-info-circle text-primary"></i> General Information</h3>
        </div>
        <div class="detail-content">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Work Package Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($work['WorkP_name']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">SD Document</span>
                    <span class="info-value">SD #<?php echo $work['SD_id']; ?> (v<?php echo $work['Version']; ?>)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Project</span>
                    <span class="info-value">
                        <a href="index.php?controller=project&action=view&id=<?php echo $work['Project_id']; ?>">
                            <?php echo htmlspecialchars($work['Project_name']); ?>
                        </a>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Expected Quantity</span>
                    <span class="info-value"><?php echo number_format($work['Expected_quantity'], 2); ?> <?php echo htmlspecialchars($work['Measurment_unit']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Unit Price</span>
                    <span class="info-value"><?php echo number_format($work['Unit_price'], 3); ?> DT</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Budgeted Cost</span>
                    <span class="info-value"><?php echo number_format($budgetedCost, 3); ?> DT</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Current Completion</span>
                    <span class="info-value">
                        <div class="progress-container" style="width: 200px;">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $completionPercent; ?>%; background: <?php echo $isCompleted ? 'var(--success)' : 'var(--primary)'; ?>"></div>
                            </div>
                            <span class="progress-text"><?php echo number_format($completionPercent, 1); ?>%</span>
                        </div>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Current Total Earned</span>
                    <span class="info-value <?php echo $isCompleted ? 'text-success' : ''; ?>">
                        <strong><?php echo number_format($currentTotalEarned, 3); ?> DT</strong>
                        <?php if($isCompleted): ?>
                        <span class="badge badge-qc" style="margin-left: 8px;">Completed</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-timeline text-primary"></i> Progress Timeline</h3>
        </div>
        <div class="timeline-container">
            <?php if(empty($periodData)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No Progress Recorded</h3>
                <p>This work package has not been added to any situation yet.</p>
            </div>
            <?php else: ?>
            <?php foreach($periodData as $period): ?>
            <div class="timeline-item <?php echo $period['is_last'] ? 'active' : ''; ?>">
                <div class="timeline-marker">
                    <?php if($period['is_complete']): ?>
                    <i class="fas fa-check-circle"></i>
                    <?php elseif($period['is_last']): ?>
                    <i class="fas fa-circle"></i>
                    <?php else: ?>
                    <i class="far fa-circle"></i>
                    <?php endif; ?>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h4>Situation #<?php echo $period['situation_id']; ?></h4>
                        <span class="timeline-date">
                            <?php echo date('d/m/Y', strtotime($period['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($period['end_date'])); ?>
                        </span>
                    </div>
                    <div class="timeline-body">
                        <div class="timeline-project">
                            <i class="fas fa-building"></i>
                            <a href="index.php?controller=project&action=view&id=<?php echo $period['project_id']; ?>">
                                <?php echo htmlspecialchars($period['project_name']); ?>
                            </a>
                        </div>
                        <?php if($period['comments']): ?>
                        <div class="timeline-comment">
                            <i class="fas fa-comment"></i> <?php echo htmlspecialchars($period['comments']); ?>
                        </div>
                        <?php endif; ?>
                        <div class="timeline-stats">
                            <div class="stat-box">
                                <span class="stat-label">Cumulative Qty</span>
                                <span class="stat-value"><?php echo number_format($period['cumulative_qty'], 2); ?> <?php echo htmlspecialchars($work['Measurment_unit']); ?></span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-label">This Period</span>
                                <span class="stat-value"><?php echo number_format($period['period_work'], 2); ?></span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-label">Cumulative Amount</span>
                                <span class="stat-value"><?php echo number_format($period['cumulative_amount'], 3); ?> DT</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-label">Period Amount</span>
                                <span class="stat-value"><?php echo number_format($period['period_amount'], 3); ?> DT</span>
                            </div>
                        </div>
                        <div class="timeline-progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $period['progress']; ?>%; background: <?php echo $period['is_complete'] ? 'var(--success)' : 'var(--primary)'; ?>"></div>
                                </div>
                                <span class="progress-text"><?php echo number_format($period['progress'], 1); ?>%</span>
                            </div>
                        </div>
                        <?php if($period['is_complete']): ?>
                        <div class="completion-badge">
                            <i class="fas fa-check-double"></i> Work Completed in this situation
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-chart-bar text-primary"></i> Summary Statistics</h3>
        </div>
        <div class="detail-content">
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>First Progress</h4>
                        <p class="stat-number"><?php echo number_format($firstProgress, 2); ?></p>
                        <small><?php echo htmlspecialchars($work['Measurment_unit']); ?></small>
                    </div>
                </div>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Latest Progress</h4>
                        <p class="stat-number"><?php echo number_format($latestProgress, 2); ?></p>
                        <small><?php echo htmlspecialchars($work['Measurment_unit']); ?></small>
                    </div>
                </div>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Total Situations</h4>
                        <p class="stat-number"><?php echo $totalSituations; ?></p>
                        <small>times recorded</small>
                    </div>
                </div>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Average per Situation</h4>
                        <p class="stat-number"><?php echo number_format($avgPerSituation, 2); ?></p>
                        <small><?php echo htmlspecialchars($work['Measurment_unit']); ?></small>
                    </div>
                </div>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Largest Jump</h4>
                        <p class="stat-number"><?php echo number_format($largestJump, 2); ?></p>
                        <small><?php echo htmlspecialchars($work['Measurment_unit']); ?></small>
                    </div>
                </div>
                <?php if($isCompleted): ?>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Status</h4>
                        <p class="stat-number text-success">Completed</p>
                        <small>All work finished</small>
                    </div>
                </div>
                <?php else: ?>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Remaining Qty</h4>
                        <p class="stat-number"><?php echo number_format($remainingQty, 2); ?></p>
                        <small><?php echo htmlspecialchars($work['Measurment_unit']); ?></small>
                    </div>
                </div>
                <div class="stat-card small">
                    <div class="stat-content">
                        <h4>Remaining Cost</h4>
                        <p class="stat-number"><?php echo number_format($remainingCost, 3); ?></p>
                        <small>DT</small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.mb-4 { margin-bottom: 32px; }
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.info-item {
    padding: 12px;
    background: var(--light);
    border-radius: var(--border-radius-sm);
}
.info-label {
    display: block;
    font-size: 12px;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.info-value {
    font-size: 16px;
    font-weight: 500;
    color: var(--dark);
}
.info-value a {
    color: var(--primary);
    text-decoration: none;
}
.info-value a:hover {
    text-decoration: underline;
}
.text-success { color: var(--success) !important; }

.timeline-container {
    padding: 24px;
    position: relative;
}
.timeline-container::before {
    content: '';
    position: absolute;
    left: 35px;
    top: 24px;
    bottom: 24px;
    width: 2px;
    background: var(--gray-lighter);
}
.timeline-item {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
    position: relative;
}
.timeline-item:last-child {
    margin-bottom: 0;
}
.timeline-marker {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--white);
    border: 2px solid var(--gray-lighter);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-light);
    font-size: 12px;
    z-index: 1;
    flex-shrink: 0;
}
.timeline-item.active .timeline-marker {
    border-color: var(--primary);
    color: var(--primary);
}
.timeline-item .timeline-marker .fa-check-circle {
    color: var(--success);
}
.timeline-content {
    flex: 1;
    background: var(--light);
    border-radius: var(--border-radius);
    padding: 16px;
    border: 1px solid var(--gray-lighter);
}
.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.timeline-header h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}
.timeline-date {
    font-size: 13px;
    color: var(--gray);
}
.timeline-project {
    margin-bottom: 8px;
}
.timeline-project i {
    color: var(--primary);
    margin-right: 8px;
}
.timeline-project a {
    color: var(--primary);
    text-decoration: none;
}
.timeline-project a:hover {
    text-decoration: underline;
}
.timeline-comment {
    font-size: 13px;
    color: var(--gray);
    margin-bottom: 12px;
    font-style: italic;
}
.timeline-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 12px;
}
.stat-box {
    text-align: center;
    padding: 8px;
    background: var(--white);
    border-radius: var(--border-radius-sm);
}
.stat-box .stat-label {
    display: block;
    font-size: 11px;
    color: var(--gray);
    margin-bottom: 4px;
}
.stat-box .stat-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--dark);
}
.timeline-progress {
    margin-top: 12px;
}
.completion-badge {
    margin-top: 12px;
    padding: 8px 12px;
    background: #d1fae5;
    color: #059669;
    border-radius: var(--border-radius-sm);
    font-size: 13px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.empty-state {
    text-align: center;
    padding: 40px;
    color: var(--gray);
}
.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    color: var(--gray-lighter);
}
.empty-state h3 {
    color: var(--dark);
    margin-bottom: 8px;
}

@media (max-width: 768px) {
    .timeline-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php require_once 'views/layout/footer.php'; ?>