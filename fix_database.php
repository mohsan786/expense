<?php
require_once __DIR__ . '/db.php';

$data = get_ledger_data();

if (!$data) {
    echo "<h1>⚠️ No Ledger Data Found</h1><p>The database is currently empty.</p>";
    exit;
}

$repairedPaymentsCount = 0;
$repairedExpensesCount = 0;

// 1. Fix returned cash expenses (Ensure positive amount)
if (!empty($data['expenses']) && is_array($data['expenses'])) {
    foreach ($data['expenses'] as &$exp) {
        $desc = strtolower($exp['description'] ?? '');
        if (strpos($desc, 'returned') !== false || !empty($exp['isPurchasingCashReturn']) || ($exp['amount'] ?? 0) < 0) {
            if (($exp['amount'] ?? 0) < 0) {
                $exp['amount'] = abs($exp['amount']);
                $repairedExpensesCount++;
            }
        }
    }
    unset($exp);
}

// 2. Re-calculate & repair salary payments per employee chronologically
if (!empty($data['config']['employees']) && is_array($data['config']['employees']) && !empty($data['salaryPayments']) && is_array($data['salaryPayments'])) {
    foreach ($data['config']['employees'] as $emp) {
        $empId = $emp['id'];
        
        // Get all payments for this employee sorted oldest to newest for chronological recalculation
        $empPayments = [];
        foreach ($data['salaryPayments'] as $pIdx => $p) {
            if (($p['employeeId'] ?? '') === $empId) {
                $empPayments[] = ['idx' => $pIdx, 'payment' => $p];
            }
        }

        // Sort by date ascending (oldest first)
        usort($empPayments, function($a, $b) {
            return strcmp($a['payment']['date'] ?? '', $b['payment']['date'] ?? '');
        });

        $runningCarryover = 0;
        foreach ($empPayments as $item) {
            $pIdx = $item['idx'];
            $p = &$data['salaryPayments'][$pIdx];

            $givenAmt = floatval($p['amount'] ?? 0);
            $dedAdv = floatval($p['deductedAdvances'] ?? 0);
            $dedHeld = floatval($p['deductedHeld'] ?? 0);
            $dedAbs = floatval($p['deductedAbsences'] ?? 0);
            $reimb = floatval($p['reimbursedAmount'] ?? 0);

            // Determine base cycle rate
            if (isset($p['baseCycleWage']) && $p['baseCycleWage'] !== '') {
                $baseWage = floatval($p['baseCycleWage']);
            } else {
                $baseWage = ($emp['type'] === 'monthly') ? floatval($emp['monthlyRate'] ?? 0) : floatval($emp['weeklyRate'] ?? 0);
            }

            // Calculate Net Due for this cycle
            $netCycleWage = max(0, $baseWage - $dedAdv - $dedHeld - $dedAbs);
            $correctDueAmount = $netCycleWage + $runningCarryover + $reimb;
            $correctRemaining = max(0, $correctDueAmount - $givenAmt);

            if (($p['dueAmount'] ?? 0) != $correctDueAmount || ($p['remainingSalary'] ?? 0) != $correctRemaining || ($p['previousRemainingSalary'] ?? 0) != $runningCarryover) {
                $p['previousRemainingSalary'] = $runningCarryover;
                $p['dueAmount'] = $correctDueAmount;
                $p['remainingSalary'] = $correctRemaining;
                $repairedPaymentsCount++;
            }

            // Pass remaining unpaid balance to next cycle
            $runningCarryover = $correctRemaining;
            unset($p);
        }
    }
}

// Save repaired JSON data back to MySQL / DB
save_ledger_data($data);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Repair & Cleanup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0F172A; color: #F8FAFC; font-family: system-ui, -apple-system, sans-serif; padding-top: 50px; }
        .card { background-color: #1E293B; border: 1px solid #334155; border-radius: 12px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 650px;">
    <div class="card p-4 shadow-lg text-center">
        <div class="display-4 mb-3">🎉</div>
        <h2 class="fw-bold text-success mb-3">Database Repaired Successfully!</h2>
        <p class="text-light lead mb-4" style="color: #E2E8F0 !important;">Your JSON ledger database has been audited, recalculated, and cleaned up.</p>
        
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="p-3 border border-secondary rounded" style="background-color: #0F172A;">
                    <div class="small fw-bold text-uppercase mb-2" style="color: #94A3B8; font-size: 11px;">Salary Payments Repaired</div>
                    <div class="fs-2 fw-bold text-warning"><?php echo $repairedPaymentsCount; ?></div>
                    <div class="small text-light mt-1" style="font-size: 10px; color: #CBD5E1 !important;">Records audit &amp; recalculated</div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 border border-secondary rounded" style="background-color: #0F172A;">
                    <div class="small fw-bold text-uppercase mb-2" style="color: #94A3B8; font-size: 11px;">Returned Cash Entries Fixed</div>
                    <div class="fs-2 fw-bold text-info"><?php echo $repairedExpensesCount; ?></div>
                    <div class="small text-light mt-1" style="font-size: 10px; color: #CBD5E1 !important;">Errands &amp; cash signs fixed</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-2">
            <a href="index.php" class="btn btn-success btn-lg fw-bold px-4">🏠 Back to Main Ledger</a>
        </div>
    </div>
</div>
</body>
</html>
