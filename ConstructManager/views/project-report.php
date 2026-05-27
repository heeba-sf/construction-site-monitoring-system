<?php
// Standalone page — no sidebar/header layout
// All styles are inline
$cons      = (float)($financial['consumption'] ?? 0);
$bar_color = $cons < 60 ? '#16a34a' : ($cons < 90 ? '#d97706' : '#dc2626');
$status    = $financial['status'] ?? 'ON TRACK';
$sta_bg    = $cons < 60 ? '#dcfce7' : ($cons < 90 ? '#fef9c3' : '#fee2e2');
$sta_col   = $cons < 60 ? '#15803d' : ($cons < 90 ? '#92400e' : '#991b1b');
$generated = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Project Report — <?php echo htmlspecialchars($project['Project_name']); ?></title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, sans-serif; font-size: 13px; color: #1e293b; background: #fff; }

  /* ── Page wrapper ── */
  .report { max-width: 900px; margin: 0 auto; padding: 32px 28px; }

  /* ── Header ── */
  .rpt-header { display: flex; justify-content: space-between; align-items: flex-start;
                border-bottom: 3px solid #1e3a5f; padding-bottom: 16px; margin-bottom: 24px; }
  .rpt-header-left h1 { font-size: 22px; color: #1e3a5f; font-weight: 800; }
  .rpt-header-left p  { font-size: 12px; color: #64748b; margin-top: 4px; }
  .rpt-logo { font-size: 28px; font-weight: 900; color: #1e3a5f; letter-spacing: -1px; }
  .rpt-logo span { color: #2e75b6; }

  /* ── Section title ── */
  .section-title { font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: .7px;
                   color: #1e3a5f; border-left: 4px solid #2e75b6; padding-left: 10px;
                   margin: 24px 0 12px; }

  /* ── Info grid (project meta) ── */
  .info-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 4px; }
  .info-cell { background: #f8fafc; border-radius: 8px; padding: 10px 14px; }
  .info-cell .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; font-weight: 600; }
  .info-cell .val { font-size: 14px; font-weight: 700; color: #1e293b; margin-top: 2px; }

  /* ── Financial cards ── */
  .fin-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 14px; }
  .fin-cell { background: #f8fafc; border-radius: 8px; padding: 10px 14px; }
  .fin-cell .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; font-weight: 600; }
  .fin-cell .val { font-size: 15px; font-weight: 800; margin-top: 3px; }

  /* ── Progress bar ── */
  .bar-wrap { margin-top: 6px; }
  .bar-track { background: #e2e8f0; border-radius: 99px; height: 10px; overflow: hidden; margin-top: 4px; }
  .bar-fill  { height: 100%; border-radius: 99px; }
  .bar-meta  { display: flex; justify-content: space-between; font-size: 11px; color: #64748b; margin-top: 3px; }

  /* ── Status badge ── */
  .status-badge { display: inline-block; padding: 4px 12px; border-radius: 99px;
                  font-size: 11px; font-weight: 700; letter-spacing: .4px; }

  /* ── Tables ── */
  table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 4px; }
  thead tr { background: #1e3a5f; color: #fff; }
  thead th { padding: 9px 10px; text-align: left; font-weight: 600; font-size: 11px; letter-spacing: .4px; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tbody td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
  .total-row td { background: #e8f0fb; font-weight: 800; border-top: 2px solid #2e75b6; }

  /* ── Mini progress bar in table ── */
  .mini-track { background: #e2e8f0; border-radius: 99px; height: 6px; width: 80px; display: inline-block; vertical-align: middle; overflow: hidden; }
  .mini-fill  { height: 100%; border-radius: 99px; }

  /* ── Footer ── */
  .rpt-footer { margin-top: 36px; border-top: 1px solid #e2e8f0; padding-top: 12px;
                display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; }

  /* ── Print button ── */
  .print-bar { position: fixed; bottom: 24px; right: 24px; display: flex; gap: 10px; }
  .print-btn { background: #1e3a5f; color: #fff; border: none; padding: 12px 24px;
               border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer;
               box-shadow: 0 4px 16px rgba(0,0,0,0.18); display: flex; align-items: center; gap: 8px; }
  .close-btn { background: #64748b; color: #fff; border: none; padding: 12px 18px;
               border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer;
               box-shadow: 0 4px 16px rgba(0,0,0,0.12); }

  @media print {
    .print-bar { display: none; }
    body { font-size: 11px; }
    .report { padding: 16px; }
  }
</style>
</head>
<body>

<div class="report">

  <!-- ── Header ──────────────────────────────────────────── -->
  <div class="rpt-header">
    <div class="rpt-header-left">
      <h1><?php echo htmlspecialchars($project['Project_name']); ?></h1>
      <p>Project Progress &amp; Financial Report &nbsp;|&nbsp; Generated: <?php echo $generated; ?></p>
    </div>
    <div class="rpt-logo">Construct<span>Manager</span></div>
  </div>

  <!-- ── Project Info ─────────────────────────────────────── -->
  <div class="section-title">Project Information</div>
  <div class="info-grid">
    <div class="info-cell"><div class="lbl">Project ID</div><div class="val">#<?php echo $project['Project_id']; ?></div></div>
    <div class="info-cell"><div class="lbl">Client</div><div class="val"><?php echo htmlspecialchars($project['First_name'] . ' ' . $project['Last_name']); ?></div></div>
    <div class="info-cell"><div class="lbl">SD Version</div><div class="val"><?php echo htmlspecialchars($project['Version']); ?></div></div>
    <div class="info-cell"><div class="lbl">Start Date</div><div class="val"><?php echo date('d/m/Y', strtotime($project['Project_start_date'])); ?></div></div>
    <div class="info-cell"><div class="lbl">Expected End</div><div class="val"><?php echo date('d/m/Y', strtotime($project['Project_expected_end_date'])); ?></div></div>
    <div class="info-cell"><div class="lbl">Overall Progress</div><div class="val" style="color:#2e75b6"><?php echo $progress; ?>%</div></div>
  </div>

  <!-- ── Financial Summary ────────────────────────────────── -->
  <div class="section-title">Financial Summary</div>
  <div class="fin-grid">
    <div class="fin-cell">
      <div class="lbl">Budget</div>
      <div class="val" style="color:#1e293b"><?php echo number_format($financial['budget'],3); ?> DT</div>
    </div>
    <div class="fin-cell">
      <div class="lbl">Planned Cost</div>
      <div class="val" style="color:#1e293b"><?php echo number_format($financial['planned_cost'],3); ?> DT</div>
    </div>
    <div class="fin-cell">
      <div class="lbl">Earned Value</div>
      <div class="val" style="color:#2e75b6"><?php echo number_format($financial['earned_value'],3); ?> DT</div>
    </div>
    <div class="fin-cell">
      <div class="lbl">Remaining</div>
      <div class="val" style="color:<?php echo $financial['remaining'] < 0 ? '#dc2626' : '#16a34a'; ?>">
        <?php echo number_format($financial['remaining'],3); ?> DT
      </div>
    </div>
  </div>

  <div class="bar-wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
      <span style="font-size:12px;font-weight:700;color:#475569">Budget Consumption: <strong style="color:<?php echo $bar_color; ?>"><?php echo $cons; ?>%</strong></span>
      <span class="status-badge" style="background:<?php echo $sta_bg; ?>;color:<?php echo $sta_col; ?>"><?php echo $status; ?></span>
    </div>
    <div class="bar-track">
      <div class="bar-fill" style="width:<?php echo min($cons,100); ?>%;background:<?php echo $bar_color; ?>"></div>
    </div>
    <div class="bar-meta"><span>0%</span><span>60% — On Track</span><span>90% — At Risk</span><span>100%</span></div>
  </div>

  <!-- ── Work Packages ────────────────────────────────────── -->
  <div class="section-title">Work Packages</div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Work Package</th>
        <th>Unit</th>
        <th>Expected Qty</th>
        <th>Achieved Qty</th>
        <th>Remaining</th>
        <th>Unit Price (DT)</th>
        <th>Earned (DT)</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $grand_earned = 0;
    $grand_planned = 0;
    foreach ($works as $w):
        $pct_w      = min((float)$w['completion_rate'], 100);
        $earned_w   = $w['total_achieved'] * $w['Unit_price'];
        $planned_w  = $w['Expected_quantity'] * $w['Unit_price'];
        $rem_w      = max(0, $w['Expected_quantity'] - $w['total_achieved']);
        $grand_earned  += $earned_w;
        $grand_planned += $planned_w;
        $bc = $pct_w >= 75 ? '#16a34a' : ($pct_w >= 30 ? '#d97706' : '#dc2626');
    ?>
      <tr>
        <td style="font-weight:700;color:#2e75b6"><?php echo $w['WorkP_id']; ?></td>
        <td style="font-weight:600"><?php echo htmlspecialchars($w['WorkP_name']); ?></td>
        <td><?php echo htmlspecialchars($w['Measurment_unit']); ?></td>
        <td><?php echo number_format($w['Expected_quantity'],2); ?></td>
        <td><?php echo number_format($w['total_achieved'],2); ?></td>
        <td style="color:<?php echo $rem_w == 0 ? '#16a34a' : '#dc2626'; ?>;font-weight:700"><?php echo $rem_w == 0 ? '✓ 0' : number_format($rem_w,2); ?></td>
        <td><?php echo number_format($w['Unit_price'],3); ?></td>
        <td style="font-weight:700"><?php echo number_format($earned_w,3); ?></td>
        <td>
          <div style="display:flex;align-items:center;gap:5px">
            <div class="mini-track"><div class="mini-fill" style="width:<?php echo $pct_w; ?>%;background:<?php echo $bc; ?>"></div></div>
            <span style="font-size:11px;font-weight:700;color:<?php echo $bc; ?>"><?php echo round($pct_w,1); ?>%</span>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="7" style="text-align:right;padding-right:12px">TOTAL</td>
        <td><?php echo number_format($grand_earned,3); ?> DT</td>
        <td></td>
      </tr>
    </tbody>
  </table>

  <!-- ── Situations ───────────────────────────────────────── -->
  <div class="section-title">Situations</div>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Comments</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Duration (days)</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($situations as $sit):
        $dur = (strtotime($sit['End_date']) - strtotime($sit['Start_date'])) / 86400;
    ?>
      <tr>
        <td style="font-weight:700;color:#2e75b6">#<?php echo $sit['Situation_id']; ?></td>
        <td><?php echo htmlspecialchars($sit['Comments']); ?></td>
        <td><?php echo date('d/m/Y', strtotime($sit['Start_date'])); ?></td>
        <td><?php echo date('d/m/Y', strtotime($sit['End_date'])); ?></td>
        <td><?php echo max(0, (int)$dur); ?> days</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <!-- ── Footer ───────────────────────────────────────────── -->
  <div class="rpt-footer">
    <span>ConstructManager — Construction Project Management System</span>
    <span>Report generated on <?php echo $generated; ?></span>
  </div>

</div><!-- /report -->

<!-- Print / Close buttons -->
<div class="print-bar">
  <button class="close-btn" onclick="window.close()">✕ Close</button>
  <button class="print-btn" onclick="window.print()">🖨 Print / Save PDF</button>
</div>

</body>
</html>
