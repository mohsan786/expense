<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

if (!is_authenticated()) {
    header('Location: index.php');
    exit;
}

$emp_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$data = get_ledger_data() ?: [
    'config' => ['currency' => 'Rs.', 'partners' => [], 'employees' => []],
    'expenses' => [],
    'income' => [],
    'advances' => [],
    'salaryPayments' => [],
    'attendanceLogs' => [],
    'workLogs' => []
];

$employees = $data['config']['employees'] ?? [];
$partners = $data['config']['partners'] ?? [];
$currency = $data['config']['currency'] ?? 'Rs.';

$employee = null;
foreach ($employees as $e) {
    if ($e['id'] === $emp_id) {
        $employee = $e;
        break;
    }
}

if (!$employee) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/jpeg" href="favicon.jpg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($employee['name']); ?> — Employee Ledger | Velto LS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root {
      --bg: #F5F5F7;
      --surface: #FFFFFF;
      --text: #1D1D1F;
      --muted: #86868B;
      --border: #E5E5EA;
      --primary: #000000;
      --teal: #34C759;
      --gold: #FF9500;
      --rust: #FF3B30;
      --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
      padding-bottom: 60px;
    }
    .mono { font-family: 'JetBrains Mono', monospace; }
    .serif { font-family: 'Newsreader', serif; }
    
    .navbar-custom {
      background: #FFFFFF;
      color: #1D1D1F;
      padding: 16px 28px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
      border-bottom: 1px solid #E5E5EA;
    }
    .navbar-custom a {
      color: #1D1D1F !important;
      border-color: #E5E5EA !important;
    }
    .navbar-custom small {
      color: #86868B !important;
    }
    
    .kpi-card {
      background: #FFFFFF;
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 22px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
    }
    .kpi-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }
    .kpi-title {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--muted);
      margin-bottom: 6px;
    }
    .kpi-value {
      font-size: 26px;
      font-weight: 800;
      font-family: 'JetBrains Mono', monospace;
      letter-spacing: -0.5px;
      color: #1D1D1F;
    }
    
    .content-panel {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 24px;
      box-shadow: var(--card-shadow);
      margin-bottom: 24px;
    }
      border-radius: 16px;
      padding: 24px;
      box-shadow: var(--card-shadow);
      margin-bottom: 24px;
    }
    .panel-header {
      font-size: 18px;
      font-weight: 700;
      font-family: 'Newsreader', serif;
      margin-bottom: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .history-row {
      display: flex;
      align-items: center;
      padding: 12px 14px;
      border-bottom: 1px solid var(--border);
      font-size: 13px;
    }
    .history-row:last-child { border-bottom: none; }
    
    .badge-custom {
      padding: 4px 10px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 11px;
    }
  </style>
</head>
<body>

  <!-- Top Header Navigation -->
  <header class="navbar-custom d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
      <a href="index.php" class="btn btn-outline-light btn-sm fw-bold px-3">← Back to Main Ledger</a>
      <div>
        <h4 class="mb-0 fw-bold serif" style="letter-spacing:-0.5px;"><?php echo htmlspecialchars($employee['name']); ?></h4>
        <small class="text-light opacity-75">
          <?php echo $employee['phone'] ? '📞 ' . htmlspecialchars($employee['phone']) . ' · ' : ''; ?>
          <?php echo ucfirst($employee['type']); ?> Salary Model
        </small>
      </div>
    </div>
    <div>
      <button class="btn btn-sm btn-light fw-bold px-3" onclick="showEditModal()">✏️ Edit Profile</button>
    </div>
  </header>

  <div class="container-fluid px-md-5">

    <!-- KPI Dashboard Summary Cards -->
    <div class="row g-3 mb-4" id="kpi-container">
      <div class="col-md-3 col-6">
        <div class="kpi-card">
          <div class="kpi-title">Salary Base Rate</div>
          <div class="kpi-value text-dark" id="kpi-base">—</div>
          <div class="small text-muted" id="kpi-base-sub">Cycle Rate</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="kpi-card">
          <div class="kpi-title">Joining Peshgi</div>
          <div class="kpi-value text-warning" id="kpi-peshgi">—</div>
          <div class="small text-muted" id="kpi-peshgi-sub">Initial Advance</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="kpi-card">
          <div class="kpi-title">Weekly Kharcha</div>
          <div class="kpi-value text-amber" style="color:var(--gold);" id="kpi-kharcha">—</div>
          <div class="small text-muted">Active Advances Taken</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="kpi-card" style="border-color:var(--teal);">
          <div class="kpi-title">Suggested Net Payday</div>
          <div class="kpi-value text-teal" style="color:var(--teal);" id="kpi-net">—</div>
          <div class="small text-muted">Calculated Settlement</div>
        </div>
      </div>
    </div>

    <!-- Action & Form Section -->
    <div class="row g-4 mb-4">
      <div class="col-lg-7 col-12">

        <!-- Piece-Rate Work Production Breakthrough Card (For Work-Based Employees) -->
        <?php if ($employee['type'] === 'workbased'): ?>
          <div class="content-panel" style="background:#F0FDF4;border-color:#86EFAC;">
            <div class="panel-header" style="color:#166534;">
              <span>🛠️ Piece-Rate Production &amp; Work Log Breakthrough</span>
              <span class="badge bg-success text-white mono fs-6" id="badge-total-earned">—</span>
            </div>

            <div class="small text-muted mb-3">
              Log production work done (pairs stitched, soles attached, items crafted) by quantity and unit piece rate. Total earned feeds directly into payday salary calculations.
            </div>

            <div class="table-responsive mb-3">
              <table class="table table-sm table-hover table-bordered align-middle mb-0 bg-white" style="font-size:12px;">
                <thead class="table-dark mono" style="font-size:11px;">
                  <tr>
                    <th>DATE</th>
                    <th>ITEM / OPERATION</th>
                    <th>QTY</th>
                    <th class="text-end">RATE / PIECE</th>
                    <th class="text-end">TOTAL EARNED (+)</th>
                    <th>NOTE</th>
                    <th>ACTION</th>
                  </tr>
                </thead>
                <tbody id="work-breakdown-body">
                  <tr><td colspan="7" class="text-center py-3 text-muted">Loading work production logs…</td></tr>
                </tbody>
              </table>
            </div>

            <button class="btn btn-success btn-sm fw-bold shadow-sm" onclick="toggleWorkForm()">🔨 + Log Work Production Entry</button>

            <!-- Hidden Form for Logging Work Production -->
            <div id="work-form-wrap" class="mt-3 p-3 border rounded bg-white" style="display:none;">
              <div class="fw-bold small mb-2 text-dark">Log Work Production Item</div>
              <div id="work-form-inner">
                <!-- Populated dynamically based on workItems catalog -->
                <div class="text-muted small text-center p-2">Loading work items catalog…</div>
              </div>
              <button class="btn btn-dark btn-sm fw-bold mt-3" onclick="saveWorkLog()">Submit Work Production Record</button>
            </div>
          </div>
        <?php endif; ?>

        <!-- Advance Breakthrough & Ledger Card -->
        <div class="content-panel">
          <div class="panel-header">
            <span>💰 Advance Ledger &amp; Return Breakthrough</span>
            <span class="badge bg-light text-dark border mono fs-6" id="badge-outstanding">—</span>
          </div>

          <div class="table-responsive mb-3">
            <table class="table table-sm table-hover table-bordered align-middle mb-0" style="font-size:12.5px;">
              <thead class="table-dark mono" style="font-size:11px;">
                <tr>
                  <th>DATE</th>
                  <th>TYPE</th>
                  <th>NOTE / DETAILS</th>
                  <th class="text-end">GIVEN (+)</th>
                  <th class="text-end">RETURNED (-)</th>
                  <th>SOURCE</th>
                  <th>STATUS</th>
                  <th class="text-center">ACTION</th>
                </tr>
              </thead>
              <tbody id="adv-breakdown-body">
                <tr><td colspan="8" class="text-center py-3 text-muted">Loading advance ledger…</td></tr>
              </tbody>
            </table>
          </div>

          <button class="btn btn-outline-dark btn-sm fw-bold" onclick="toggleAdvForm()">➕ Log New Weekly Advance (Kharcha)</button>

          <!-- Hidden Form for Logging Advance -->
          <div id="adv-form-wrap" class="mt-3 p-3 border rounded bg-light" style="display:none;">
            <div class="fw-bold small mb-2 text-dark">Log Weekly Kharcha Advance</div>
            <div class="row g-2">
              <div class="col-md-3 col-6"><input type="date" id="adv-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
              <div class="col-md-3 col-6"><input type="number" id="adv-amount" class="form-control form-control-sm" placeholder="Amount"></div>
              <div class="col-md-3 col-6">
                <select id="adv-paidby" class="form-select form-select-sm">
                  <option value="business" selected>🏢 Business Funds</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                  <?php foreach ($partners as $p): ?>
                    <option value="<?php echo htmlspecialchars($p['id']); ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3 col-6"><input id="adv-note" class="form-control form-control-sm" placeholder="Note (optional)"></div>
            </div>
            <button class="btn btn-dark btn-sm fw-bold mt-2" onclick="saveAdvance()">Submit Advance</button>
          </div>
        </div>

        <!-- Attendance & Leave Log Breakthrough Card -->
        <div class="content-panel">
          <div class="panel-header">
            <span>📅 Attendance &amp; Leave Log Breakthrough</span>
            <span class="badge bg-danger text-white mono fs-6" id="badge-unsettled-abs">—</span>
          </div>

          <div class="table-responsive mb-3">
            <table class="table table-sm table-hover table-bordered align-middle mb-0" style="font-size:12px;">
              <thead class="table-dark mono" style="font-size:11px;">
                <tr>
                  <th>DATE</th>
                  <th>ABSENCE TYPE</th>
                  <th>SALARY RULE</th>
                  <th class="text-end">PENALTY (-)</th>
                  <th>REASON / NOTE</th>
                  <th>STATUS / ACTION</th>
                </tr>
              </thead>
              <tbody id="att-breakdown-body">
                <tr><td colspan="6" class="text-center py-3 text-muted">Loading attendance breakthrough…</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Purchasing Cash & Material Errands Ledger Card -->
        <div class="content-panel" style="background:#FFFBEB;border-color:#FCD34D;">
          <div class="panel-header" style="color:#92400E;">
            <span>🛍️ Purchasing Cash &amp; Material Errands Ledger</span>
            <span class="badge bg-warning text-dark border mono fs-6" id="badge-unspent-cash">—</span>
          </div>

          <div class="small text-muted mb-3">
            Track money handed to worker for purchasing factory materials or running errands. Any unspent cash not returned is automatically tracked and deducted on payday.
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-4 col-12 text-center p-2 rounded bg-white border">
              <div class="muted small fw-bold" style="font-size:10px;">TOTAL CASH HANDED</div>
              <div class="mono strong text-warning" style="font-size:15px;" id="box-cash-given">—</div>
            </div>
            <div class="col-md-4 col-12 text-center p-2 rounded bg-white border">
              <div class="muted small fw-bold" style="font-size:10px;">MATERIAL EXPENSES SPENT</div>
              <div class="mono strong text-success" style="font-size:15px;" id="box-cash-spent">—</div>
            </div>
            <div class="col-md-4 col-12 text-center p-2 rounded bg-white border">
              <div class="muted small fw-bold" style="font-size:10px;">UNSPENT CASH HELD</div>
              <div class="mono strong text-danger" style="font-size:15px;" id="box-cash-unspent">—</div>
            </div>
          </div>

          <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-warning btn-sm fw-bold text-dark" onclick="togglePurchasingForm()">➕ Hand Cash for Purchases</button>
            <button class="btn btn-outline-dark btn-sm fw-bold" onclick="showReturnCashModal()">💵 Record Returned Cash</button>
          </div>

          <!-- Hidden Form for Logging Purchasing Cash Handout -->
          <div id="purchasing-form-wrap" class="mt-3 p-3 border rounded bg-white" style="display:none;">
            <div class="fw-bold small mb-2 text-dark">Hand Cash to Worker for Material Purchases / Errands</div>
            <div class="row g-2">
              <div class="col-md-3 col-6"><input type="date" id="pcash-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
              <div class="col-md-3 col-6"><input type="number" id="pcash-amount" class="form-control form-control-sm" placeholder="Amount (e.g. 8000)"></div>
              <div class="col-md-3 col-6">
                <select id="pcash-paidby" class="form-select form-select-sm">
                  <option value="business" selected>🏢 Business Funds</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                  <?php foreach ($partners as $p): ?>
                    <option value="<?php echo htmlspecialchars($p['id']); ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3 col-6"><input id="pcash-note" class="form-control form-control-sm" placeholder="Purpose (e.g. Buying leather)"></div>
            </div>
            <button class="btn btn-dark btn-sm fw-bold mt-2" onclick="savePurchasingCash()">Give Purchasing Cash</button>
          </div>

          <!-- Purchasing Cash Ledger Records Table -->
          <div class="mt-3 table-responsive bg-white rounded border">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size:13px;">
              <thead class="table-light">
                <tr>
                  <th>Date</th>
                  <th>Type / Purpose</th>
                  <th>Source / Receiver</th>
                  <th class="text-end">Amount</th>
                  <th>Status</th>
                  <th class="text-center" style="width:90px;">Action</th>
                </tr>
              </thead>
              <tbody id="pcash-breakdown-body">
                <tr><td colspan="6" class="text-center text-muted py-3">Loading purchasing cash records…</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Full Transaction History Card -->
        <div class="content-panel">
          <div class="panel-header">
            <span>📜 Transaction History Log</span>
          </div>
          <div id="history-container">
            <div class="text-muted small">Loading transaction history…</div>
          </div>
        </div>

      </div>

      <!-- Right Column: Payday & Operations -->
      <div class="col-lg-5 col-12">

        <!-- Payday Settlement Panel -->
        <div class="content-panel" style="background:#FAF5FF;border-color:#E9D5FF;">
          <div class="panel-header" style="color:#6B21A8;">
            <span>💵 Payday Salary Settlement</span>
          </div>

          <div id="payday-box-wrap">
            <div class="text-muted small">Loading payday details…</div>
          </div>
        </div>

        <!-- Attendance Logging Panel -->
        <div class="content-panel">
          <div class="panel-header">
            <span>📅 Log Attendance / Absence</span>
          </div>
          <div class="row g-2">
            <div class="col-6"><label class="form-label small mb-1">Date</label><input type="date" id="att-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
            <div class="col-6"><label class="form-label small mb-1">Status</label><select id="att-status" class="form-select form-select-sm"><option value="absent">Full Day Absent</option><option value="halfday">Half Day</option></select></div>
            <div class="col-12"><label class="form-label small mb-1">Salary Penalty</label><select id="att-deduct" class="form-select form-select-sm"><option value="yes">Deduct Salary (Unpaid)</option><option value="no">Paid Leave (No Deduct)</option></select></div>
            <div class="col-12"><label class="form-label small mb-1">Reason</label><input id="att-note" class="form-control form-control-sm" placeholder="Reason (optional)"></div>
          </div>
          <button class="btn btn-dark btn-sm fw-bold mt-3 w-100" onclick="saveAttendance()">+ Log Absence Record</button>
        </div>

      </div>
    </div>

  </div>

  <script>
    const EMP_ID = <?php echo json_encode($emp_id); ?>;
    let state = {
      data: null,
      customWeeklyDeduct: null,
      customJoiningDeduct: null,
      deductWeeklyAdv: true,
      deductJoiningAdv: false,
      includeReimb: true,
      deductHeld: true,
      deductAtt: true
    };

    function fmt(n) {
      const c = state.data ? state.data.config.currency : 'Rs.';
      return `${c}${Number(n || 0).toFixed(2)}`;
    }

    function esc(str) {
      return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    async function loadData() {
      const res = await fetch('api.php?action=load');
      const json = await res.json();
      if (json.success && json.data) {
        state.data = json.data;
        render();
      }
    }

    async function mutate(fn) {
      fn();
      await fetch('api.php?action=save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ data: state.data })
      });
      render();
    }

    function render() {
      if (!state.data) return;
      const emp = state.data.config.employees.find(e => e.id === EMP_ID);
      if (!emp) return;

      const partners = state.data.config.partners || [];
      const advances = (state.data.advances || []).filter(a => a.employeeId === EMP_ID);
      const payments = (state.data.salaryPayments || []).filter(p => p.employeeId === EMP_ID);
      const workLogs = (state.data.workLogs || []).filter(w => w.employeeId === EMP_ID);
      const attLogs = (state.data.attendanceLogs || []).filter(a => a.employeeId === EMP_ID);
      const unsettledAbs = attLogs.filter(a => !a.settled && a.status !== 'present');

      const joiningAdvEntry = advances.find(a => a.employeeId === EMP_ID && (a.isJoiningAdvance || (a.note && a.note.toLowerCase().includes("joining"))));
      const joiningAdvVal = emp.joiningAdvance || (joiningAdvEntry ? Number(joiningAdvEntry.amount || 0) : 0);
      const joiningAdvUnsettled = joiningAdvEntry ? (!joiningAdvEntry.settled ? Number(joiningAdvEntry.amount || 0) : 0) : Number(emp.joiningAdvance || 0);

      const weeklyAdvEntries = advances.filter(a => a.employeeId === EMP_ID && !a.isJoiningAdvance && !(a.note && a.note.toLowerCase().includes("joining")) && !a.isPurchasingCash);

      const totalWeeklyAdvGiven = weeklyAdvEntries.reduce((s, a) => s + Number(a.amount || 0), 0);
      const totalAdvReturned = payments.reduce((s, p) => s + Number(p.deductedAdvances || 0), 0);
      const weeklyAdvVal = Math.max(0, totalWeeklyAdvGiven - totalAdvReturned);
      const outstandingAdv = weeklyAdvVal + joiningAdvUnsettled;

      const defaultWD = emp.type === "monthly" ? 26 : 6;
      const wDays = emp.workingDays || defaultWD;
      const baseRate = emp.type === "monthly" ? Number(emp.monthlyRate || 0) : Number(emp.weeklyRate || 0);
      const dailyRate = baseRate / (wDays > 0 ? wDays : defaultWD);

      let absenceDeductionVal = 0;
      unsettledAbs.forEach(a => {
        if (a.deductSalary !== false) {
          absenceDeductionVal += dailyRate * (a.status === 'halfday' ? 0.5 : 1);
        }
      });

      // Calculate separate advance deductions
      const weeklyDeductAmt = state.deductWeeklyAdv ? (state.customWeeklyDeduct !== null ? Number(state.customWeeklyDeduct) : weeklyAdvVal) : 0;
      const joiningDeductAmt = state.deductJoiningAdv ? (state.customJoiningDeduct !== null ? Number(state.customJoiningDeduct) : joiningAdvVal) : 0;
      const actualAdvDeducted = Math.min(outstandingAdv, Math.max(0, weeklyDeductAmt + joiningDeductAmt));

      // Calculate Customer Sales Cash Collected by Employee
      let salesCashCollected = 0;
      const empSalesLogs = [];
      (state.data.income || []).forEach(s => {
        const itemTitle = s.item || "Customer Order";
        const qtyStr = s.quantity ? ` (Qty: ${s.quantity})` : "";
        const custName = s.customerName || "Customer";
        
        if (Array.isArray(s.payments) && s.payments.length > 0) {
          s.payments.forEach(p => {
            if (p.receivedByEmployeeId === EMP_ID || p.receiverVal === `employee:${EMP_ID}`) {
              const amt = Number(p.amount || 0);
              salesCashCollected += amt;
              empSalesLogs.push({
                date: p.date || s.date,
                kind: "sales_cash",
                item: itemTitle + qtyStr,
                customerName: custName,
                amount: amt,
                note: p.note || s.note || "Customer payment collected by worker"
              });
            }
          });
        } else {
          if (s.receivedByEmployeeId === EMP_ID || (s.receivedByType === 'employee' && s.receivedBy === EMP_ID)) {
            const amt = Number(s.paidAmount !== undefined ? s.paidAmount : s.amount || 0);
            if (amt > 0) {
              salesCashCollected += amt;
              empSalesLogs.push({
                date: s.date,
                kind: "sales_cash",
                item: itemTitle + qtyStr,
                customerName: custName,
                amount: amt,
                note: s.note || "Customer sale cash collected by worker"
              });
            }
          }
        }
      });

      // Calculate Purchasing Cash & Customer Sales Cash Holding vs Out-of-Pocket Reimbursement Owed
      const rawCashHandouts = (state.data.cashHandouts || []).filter(c => c.employeeId === EMP_ID && !c.settled);
      const uniqueHandouts = Array.from(new Map(rawCashHandouts.map(item => [item.id, item])).values());
      const totalPurchasingHanded = uniqueHandouts.reduce((s, c) => s + Number(c.amount || 0), 0);
      const totalCashHanded = totalPurchasingHanded + salesCashCollected;

      const workerExpenses = (state.data.expenses || []).filter(e => e.payerEmployeeId === EMP_ID && !e.settled);
      const totalWorkerSpent = workerExpenses.reduce((s, e) => s + Number(e.amount || 0), 0);
      
      const netPurchasingDiff = totalCashHanded - totalWorkerSpent;
      const unspentPurchasingCash = netPurchasingDiff > 0 ? netPurchasingDiff : 0;
      const reimbursementOwed = netPurchasingDiff < 0 ? Math.abs(netPurchasingDiff) : 0;

      const actualCashHeldDeducted = (state.deductCashHeld && unspentPurchasingCash > 0) ? unspentPurchasingCash : 0;
      const actualReimbAdded = (state.includeReimb && reimbursementOwed > 0) ? reimbursementOwed : 0;

      const totalEarned = workLogs.reduce((s, w) => s + w.amount, 0);
      const totalWagePaid = payments.reduce((s, p) => s + Number(p.amount || 0), 0);
      const unpaidWorkEarned = Math.max(0, totalEarned - totalWagePaid);

      const suggestedWage = (emp.type === "weekly" || emp.type === "monthly")
        ? Math.max(baseRate - actualAdvDeducted - actualCashHeldDeducted + actualReimbAdded - (state.deductAtt ? absenceDeductionVal : 0), 0)
        : Math.max(unpaidWorkEarned - actualAdvDeducted - actualCashHeldDeducted + actualReimbAdded, 0);

      // Render KPIs & Cash Holding Boxes
      if (emp.type === 'workbased') {
        document.getElementById('kpi-base').innerText = fmt(totalEarned);
        document.getElementById('kpi-base-sub').innerText = 'Total Work Production Earned';
      } else {
        document.getElementById('kpi-base').innerText = fmt(baseRate);
        document.getElementById('kpi-base-sub').innerText = `${emp.type === 'monthly' ? 'Monthly' : 'Weekly'} (${wDays}d)`;
      }
      document.getElementById('kpi-peshgi').innerText = fmt(joiningAdvVal);
      document.getElementById('kpi-peshgi-sub').innerText = joiningAdvEntry ? (joiningAdvEntry.paidBy === 'business' ? 'Business Funds' : 'Partner Paid') : 'Joining Peshgi';
      document.getElementById('kpi-kharcha').innerText = fmt(weeklyAdvVal);
      document.getElementById('kpi-net').innerText = fmt(suggestedWage);
      document.getElementById('badge-outstanding').innerText = `Outstanding: ${fmt(outstandingAdv)}`;

      if (document.getElementById('box-cash-given')) {
        document.getElementById('box-cash-given').innerText = fmt(totalCashHanded);
        document.getElementById('box-cash-spent').innerText = fmt(totalWorkerSpent);
        document.getElementById('box-cash-unspent').innerText = fmt(unspentPurchasingCash);
        document.getElementById('badge-unspent-cash').innerText = unspentPurchasingCash > 0 ? `Unspent: ${fmt(unspentPurchasingCash)}` : 'Settled';
      }

      // Render Purchasing Cash Ledger Records Table
      const pcashTbody = document.getElementById('pcash-breakdown-body');
      if (pcashTbody) {
        const rawHandoutsAll = (state.data.cashHandouts || []).filter(c => c.employeeId === EMP_ID);
        const advHandoutsAll = (state.data.advances || []).filter(a => a.employeeId === EMP_ID && a.isPurchasingCash);
        const combinedHandouts = [...rawHandoutsAll];
        advHandoutsAll.forEach(a => {
          if (!combinedHandouts.find(c => c.id === a.id)) combinedHandouts.push(a);
        });

        const rawReturnedCash = (state.data.expenses || []).filter(e => e.payerEmployeeId === EMP_ID && (
          (e.description && e.description.includes('Unspent Cash Returned')) || 
          e.isPurchasingCashReturn || 
          Number(e.amount || 0) < 0
        ));

        const pcashLogs = [
          ...combinedHandouts.map(c => ({
            id: c.id,
            date: c.date || '',
            kind: 'handout',
            purpose: c.note || 'Material Purchasing / Errands',
            source: c.paidBy === 'business' || !c.paidBy ? 'Business Funds' : (partners.find(p => p.id === c.paidBy)?.name || 'Partner'),
            amount: Number(c.amount || 0),
            settled: !!c.settled
          })),
          ...rawReturnedCash.map(e => ({
            id: e.id,
            date: e.date || '',
            kind: 'returned',
            purpose: e.description || 'Returned Unspent Cash',
            source: partners.find(p => p.id === e.paidBy)?.name || 'Business Funds',
            amount: -Math.abs(Number(e.amount || 0)),
            settled: !!e.settled
          }))
        ].sort((a, b) => (b.date || '').localeCompare(a.date || ''));

        if (pcashLogs.length === 0) {
          pcashTbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">No purchasing cash handouts or returned cash logged yet.</td></tr>`;
        } else {
          pcashTbody.innerHTML = pcashLogs.map(log => {
            if (log.kind === 'handout') {
              return `
                <tr>
                  <td class="mono muted">${log.date}</td>
                  <td>
                    <span class="badge bg-warning text-dark me-1">🛍️ Cash Given</span>
                    <span>${esc(log.purpose)}</span>
                  </td>
                  <td class="small">${esc(log.source)}</td>
                  <td class="mono text-end fw-bold text-amber">+${fmt(log.amount)}</td>
                  <td>
                    ${log.settled 
                      ? `<span class="badge bg-light text-dark border">Settled</span>` 
                      : `<span class="badge bg-warning text-dark">Active Held</span>`}
                  </td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary py-0 px-1 border-0" onclick="editPurchasingCash('${log.id}')" title="Edit Entry">✏️</button>
                    <button class="btn btn-sm btn-outline-danger py-0 px-1 border-0" onclick="deletePurchasingCash('${log.id}')" title="Delete Entry">🗑️</button>
                  </td>
                </tr>
              `;
            } else {
              return `
                <tr>
                  <td class="mono muted">${log.date}</td>
                  <td>
                    <span class="badge bg-success me-1">💵 Cash Returned</span>
                    <span>${esc(log.purpose)}</span>
                  </td>
                  <td class="small">${esc(log.source)}</td>
                  <td class="mono text-end fw-bold text-success">${fmt(log.amount)}</td>
                  <td><span class="badge bg-success">Returned</span></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary py-0 px-1 border-0" onclick="editReturnedCash('${log.id}')" title="Edit Entry">✏️</button>
                    <button class="btn btn-sm btn-outline-danger py-0 px-1 border-0" onclick="deleteReturnedCash('${log.id}')" title="Delete Entry">🗑️</button>
                  </td>
                </tr>
              `;
            }
          }).join('');
        }
      }

      // Render Work Production Breakthrough Table for Workbased Employees
      const workTbody = document.getElementById('work-breakdown-body');
      if (workTbody) {
        if (document.getElementById('badge-total-earned')) {
          document.getElementById('badge-total-earned').innerText = `Total Earned: ${fmt(totalEarned)}`;
        }
        if (workLogs.length === 0) {
          workTbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">No work production items logged yet. Click "+ Log Work Production Entry" above.</td></tr>`;
        } else {
          workTbody.innerHTML = workLogs.map(w => `
            <tr>
              <td class="mono muted">${w.date}</td>
              <td class="fw-bold">${esc(w.itemLabel || 'Production Item')}</td>
              <td class="mono text-center">${w.quantity}</td>
              <td class="mono text-end">${fmt(w.unitPrice)}</td>
              <td class="mono text-end fw-bold text-success">${fmt(w.amount)}</td>
              <td class="small text-muted">${w.note ? esc(w.note) : '—'}</td>
              <td class="text-center">
                <button class="btn btn-sm btn-outline-primary py-0 px-1 border-0 me-1" style="font-size:10px;" onclick="editWorkLog('${w.id}')" title="Edit Log">✏️</button>
                <button class="btn btn-sm btn-outline-danger py-0 px-1 border-0" style="font-size:10px;" onclick="deleteWorkLog('${w.id}')" title="Delete Log">🗑️</button>
              </td>
            </tr>
          `).join('');
        }
      }

      // Populate Work Log Form with item dropdown from catalog
      const workFormInner = document.getElementById('work-form-inner');
      if (workFormInner) {
        const workItems = state.data.workItems || [];
        if (workItems.length === 0) {
          workFormInner.innerHTML = `
            <div class="alert alert-warning py-2 mb-0" style="font-size:13px;">
              ⚠️ No work items defined yet. Go to the <strong>Employees page → 🛠️ Work Items Catalog</strong> to add items with rates first.
            </div>`;
        } else {
          const opts = workItems.map(wi => `<option value="${wi.id}" data-rate="${wi.unitPrice}" data-label="${esc(wi.name)}">${esc(wi.name)} — ${fmt(wi.unitPrice)} / ${esc(wi.unit||'piece')}</option>`).join('');
          workFormInner.innerHTML = `
            <div class="row g-2">
              <div class="col-md-3 col-6"><label class="form-label small mb-1">Date</label><input type="date" id="wl-date" class="form-control form-control-sm" value="${new Date().toISOString().split('T')[0]}"></div>
              <div class="col-md-5 col-12"><label class="form-label small mb-1">Select Item *</label>
                <select id="wl-item-sel" class="form-select form-select-sm" onchange="onItemSelect()">
                  <option value="">— Select a work item —</option>
                  ${opts}
                </select>
              </div>
              <div class="col-md-2 col-6"><label class="form-label small mb-1">Quantity *</label><input type="number" id="wl-qty" class="form-control form-control-sm" placeholder="e.g. 50" oninput="calcWorkTotal()"></div>
              <div class="col-md-2 col-6"><label class="form-label small mb-1">Rate (auto)</label><input type="number" id="wl-rate" class="form-control form-control-sm bg-light" placeholder="Auto-filled" readonly></div>
              <div class="col-md-6 col-12"><label class="form-label small mb-1">Note (optional)</label><input id="wl-note" class="form-control form-control-sm" placeholder="Details..."></div>
              <div class="col-md-6 col-12"><label class="form-label small mb-1">Total Earned</label><div class="form-control form-control-sm mono fw-bold text-success bg-light" id="wl-total-preview">Rs.0.00</div></div>
            </div>`;
        }
      }

      // Render Advance Breakthrough Table
      const advEvents = [
        ...advances.map(a => ({
          date: a.date,
          type: a.isJoiningAdvance ? 'Joining Peshgi (+)' : 'Weekly Kharcha (+)',
          note: a.note || (a.isJoiningAdvance ? 'Joining Advance (Peshgi)' : 'Weekly Advance'),
          given: Number(a.amount || 0),
          returned: 0,
          paidBy: a.paidBy === 'business' || !a.paidBy ? 'Business Funds' : (partners.find(p=>p.id===a.paidBy)?.name || 'Partner'),
          settled: a.settled,
          objType: 'advance',
          id: a.id
        })),
        ...payments.filter(p => Number(p.deductedAdvances || 0) > 0).map(p => ({
          date: p.date,
          type: 'Payday Deduction (-)',
          note: p.note ? `Salary Payday: ${p.note}` : 'Salary Payday Advance Repayment',
          given: 0,
          returned: Number(p.deductedAdvances || 0),
          paidBy: p.paidBy === 'business' || !p.paidBy ? 'Salary Settlement' : (partners.find(p=>p.id===p.paidBy)?.name || 'Salary Settlement'),
          settled: true,
          objType: 'payment',
          id: p.id
        }))
      ].sort((a, b) => b.date.localeCompare(a.date));

      const advTbody = document.getElementById('adv-breakdown-body');
      if (advEvents.length === 0) {
        advTbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-3">No advances given or returned yet.</td></tr>`;
      } else {
        advTbody.innerHTML = advEvents.map(ev => `
          <tr>
            <td class="mono muted">${ev.date}</td>
            <td><span class="badge ${ev.given > 0 ? (ev.type.includes('Peshgi') ? 'bg-warning text-dark' : 'bg-secondary') : 'bg-success'}">${ev.type}</span></td>
            <td>${ev.note}</td>
            <td class="mono text-end ${ev.given > 0 ? 'fw-bold text-amber' : 'text-muted'}">${ev.given > 0 ? fmt(ev.given) : '—'}</td>
            <td class="mono text-end ${ev.returned > 0 ? 'fw-bold text-success' : 'text-muted'}">${ev.returned > 0 ? fmt(ev.returned) : '—'}</td>
            <td class="small">${ev.paidBy}</td>
            <td>${ev.settled ? `<span class="badge bg-light text-dark border">Settled</span>` : `<span class="badge bg-warning text-dark">Active Due</span>`}</td>
            <td class="text-center">
              ${ev.objType === 'advance' 
                ? `<button class="btn btn-sm btn-outline-primary py-0 px-1 border-0 me-1" onclick="editAdvance('${ev.id}')" title="Edit Advance">✏️</button>
                   <button class="btn btn-sm btn-outline-danger py-0 px-1 border-0" onclick="deleteAdvance('${ev.id}')" title="Delete Advance">🗑️</button>`
                : `<span class="text-muted small" title="Delete Payday Deduction from Salary Settlement panel">-</span>`
              }
            </td>
          </tr>
        `).join('');
      }

      // Render Attendance Breakthrough Table
      if (document.getElementById('badge-unsettled-abs')) {
        document.getElementById('badge-unsettled-abs').innerText = unsettledAbs.length > 0 ? `${unsettledAbs.length} Unsettled (${fmt(absenceDeductionVal)})` : 'All Settled';
      }
      const attTbody = document.getElementById('att-breakdown-body');
      if (attTbody) {
        if (attLogs.length === 0) {
          attTbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">No absences or leave records logged yet.</td></tr>`;
        } else {
          attTbody.innerHTML = attLogs.map(att => {
            const isDeduct = att.deductSalary !== false;
            const lossVal = isDeduct ? dailyRate * (att.status === 'halfday' ? 0.5 : 1) : 0;
            const statusLabel = att.status === 'halfday' ? 'Half Day' : 'Full Day Absent';
            
            return `
              <tr>
                <td class="mono muted">${att.date}</td>
                <td><span class="badge ${isDeduct ? 'bg-danger' : 'bg-success'}">${statusLabel}</span></td>
                <td>${isDeduct ? `<span class="badge bg-danger">Unpaid Leave (Salary Deducted)</span>` : `<span class="badge bg-success">Paid Leave</span>`}</td>
                <td class="mono text-end ${isDeduct ? 'fw-bold text-danger' : 'text-muted'}">${isDeduct ? '-' + fmt(lossVal) : 'Rs.0.00'}</td>
                <td class="small">${att.note ? esc(att.note) : '—'}</td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    ${att.settled ? `<span class="badge bg-light text-dark border">Settled on Payday</span>` : `<span class="badge bg-warning text-dark">Active Unsettled</span>`}
                    <button class="btn btn-sm btn-outline-danger py-0 px-1 ms-1" style="font-size:10px;" onclick="deleteAttendance('${att.id}')" title="Delete Log">🗑️</button>
                  </div>
                </td>
              </tr>
            `;
          }).join('');
        }
      }

      // Render Payday Settlement Box
      const paydayWrap = document.getElementById('payday-box-wrap');
      paydayWrap.innerHTML = `
        <div class="mb-3">
          <div class="fw-bold mb-1">Suggested Wage Amount: <span class="mono text-success fs-5">${fmt(suggestedWage)}</span></div>
          <div class="small text-muted mb-2">Calculated from base rate minus advance deductions, unspent purchasing cash &amp; absence penalties.</div>
        </div>

        ${(outstandingAdv > 0 || unspentPurchasingCash > 0 || reimbursementOwed > 0) ? `
          <div class="p-3 border rounded bg-white mb-3">
            <div class="fw-bold small text-dark mb-2">Payday Deductions &amp; Reimbursements</div>

            <!-- Weekly Kharcha Checkbox (Default CHECKED) -->
            ${weeklyAdvVal > 0 ? `
              <div class="p-2 border rounded mb-2 bg-light">
                <div class="d-flex align-items-center justify-content-between gap-2">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="chk-weekly-adv" ${state.deductWeeklyAdv ? 'checked' : ''} onchange="state.deductWeeklyAdv=this.checked;render();">
                    <label class="form-check-label fw-bold small text-dark" for="chk-weekly-adv">
                      ⚡ Deduct Weekly Kharcha (${fmt(weeklyAdvVal)})
                    </label>
                  </div>
                  ${state.deductWeeklyAdv ? `
                    <input class="form-control form-control-sm mono fw-bold" type="number" style="max-width:110px;" 
                      value="${state.customWeeklyDeduct !== null ? state.customWeeklyDeduct : weeklyAdvVal}" 
                      oninput="state.customWeeklyDeduct=this.value;render();">
                  ` : ''}
                </div>
                <div class="text-muted small mt-1" style="font-size:11px;margin-left:24px;">Default: Checked (Deduct from weekly payday)</div>
              </div>
            ` : ''}

            <!-- Unspent Purchasing Cash Checkbox (Default CHECKED) -->
            ${unspentPurchasingCash > 0 ? `
              <div class="p-2 border rounded mb-2 bg-light" style="border-color:#FCD34D !important;background:#FEF3C7 !important;">
                <div class="d-flex align-items-center justify-content-between gap-2">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="chk-cash-held" ${state.deductCashHeld ? 'checked' : ''} onchange="state.deductCashHeld=this.checked;render();">
                    <label class="form-check-label fw-bold small text-dark" for="chk-cash-held">
                      🛍️ Deduct Unspent Purchasing Cash (${fmt(unspentPurchasingCash)})
                    </label>
                  </div>
                  <span class="mono fw-bold text-danger">${fmt(unspentPurchasingCash)}</span>
                </div>
                <div class="text-muted small mt-1" style="font-size:11px;margin-left:24px;">Cash given for purchasing material not returned (Default: Checked).</div>
              </div>
            ` : ''}

            <!-- Out-of-Pocket Expense Reimbursement Checkbox (Default CHECKED) -->
            ${reimbursementOwed > 0 ? `
              <div class="p-2 border rounded mb-2 bg-light" style="border-color:#86EFAC !important;background:#F0FDF4 !important;">
                <div class="d-flex align-items-center justify-content-between gap-2">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="chk-reimb" ${state.includeReimb ? 'checked' : ''} onchange="state.includeReimb=this.checked;render();">
                    <label class="form-check-label fw-bold small text-dark" for="chk-reimb">
                      💼 Add Out-of-Pocket Reimbursement (+${fmt(reimbursementOwed)})
                    </label>
                  </div>
                  <span class="mono fw-bold text-success">+${fmt(reimbursementOwed)}</span>
                </div>
                <div class="text-muted small mt-1" style="font-size:11px;margin-left:24px;">Expenses paid out of worker's own pocket added to payday salary.</div>
              </div>
            ` : ''}

            <!-- Joining Peshgi Checkbox (Default UNCHECKED) -->
            ${joiningAdvVal > 0 ? `
              <div class="p-2 border rounded mb-2 bg-light">
                <div class="d-flex align-items-center justify-content-between gap-2">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="chk-joining-adv" ${state.deductJoiningAdv ? 'checked' : ''} onchange="state.deductJoiningAdv=this.checked;render();">
                    <label class="form-check-label fw-bold small text-dark" for="chk-joining-adv">
                      🏢 Deduct Joining Peshgi (${fmt(joiningAdvVal)})
                    </label>
                  </div>
                  ${state.deductJoiningAdv ? `
                    <input class="form-control form-control-sm mono fw-bold" type="number" style="max-width:110px;" 
                      value="${state.customJoiningDeduct !== null ? state.customJoiningDeduct : joiningAdvVal}" 
                      oninput="state.customJoiningDeduct=this.value;render();">
                  ` : ''}
                </div>
                <div class="text-muted small mt-1" style="font-size:11px;margin-left:24px;">Default: Unchecked (Long-term advance)</div>
              </div>
            ` : ''}

            <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center small">
              <span class="fw-bold">Net Settlement Shift:</span>
              <span class="mono fw-bold ${(actualReimbAdded - actualAdvDeducted - actualCashHeldDeducted) >= 0 ? 'text-success' : 'text-danger'}">${(actualReimbAdded - actualAdvDeducted - actualCashHeldDeducted) >= 0 ? '+' : ''}${fmt(actualReimbAdded - actualAdvDeducted - actualCashHeldDeducted)}</span>
            </div>
          </div>
        ` : ''}

        <div class="row g-2 mb-3">
          <div class="col-6"><label class="form-label small mb-1">Pay Date</label><input type="date" id="pay-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
          <div class="col-6"><label class="form-label small mb-1">Custom Wage Override</label><input type="number" id="pay-amount" class="form-control form-control-sm" placeholder="${suggestedWage}"></div>
          <div class="col-12"><label class="form-label small mb-1">Paid By Partner</label><select id="pay-paidby" class="form-select form-select-sm"><option value="business">&#x1F4BC; Business Funds</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>${partners.map(p=>`<option value="${p.id}">${p.name}</option>`).join('')}</select></div>
          <div class="col-12"><label class="form-label small mb-1">Payment Note</label><input id="pay-note" class="form-control form-control-sm" placeholder="e.g. Weekly payday salary"></div>
        </div>

        <button class="btn btn-success btn-lg w-100 fw-bold shadow-sm" onclick="savePayday()">💰 Settle Salary Payday</button>
      `;

      // Render Transaction History Log
      const historyItems = [
        ...workLogs.map(w => ({ ...w, kind: "work" })),
        ...payments.map(p => ({ ...p, kind: "pay" })),
        ...advances.map(a => ({ ...a, kind: "advance" })),
        ...attLogs.map(a => ({ ...a, kind: "attendance" })),
        ...workerExpenses.map(e => ({ ...e, kind: "expense" })),
        ...empSalesLogs
      ].sort((a, b) => (b.date || "").localeCompare(a.date || ""));

      const histWrap = document.getElementById('history-container');
      if (historyItems.length === 0) {
        histWrap.innerHTML = `<div class="text-muted small py-2">No transaction history recorded yet.</div>`;
      } else {
        histWrap.innerHTML = historyItems.map(r => {
          let label = "";
          if (r.kind === "work") label = `Work: ${r.itemLabel||"Work"} × ${r.quantity}${r.note?" — "+r.note:""}`;
          else if (r.kind === "pay") label = `Salary Payment: Paid by ${partners.find(p=>p.id===r.paidBy)?.name||"—"}${r.deductedAdvances?` (− ${fmt(r.deductedAdvances)} advance)`:""}${r.reimbursedAmount?` (+ ${fmt(r.reimbursedAmount)} reimbursed)`:""}${r.note?" — "+r.note:""}`;
          else if (r.kind === "advance") {
            const payerName = r.paidBy === "business" || !r.paidBy ? "Business Funds" : (partners.find(p=>p.id===r.paidBy)?.name || "Business Funds");
            label = `Advance Given: Paid by ${payerName}${r.note?" — "+r.note:""}`;
          } else if (r.kind === "attendance") {
            const isD = r.deductSalary !== false;
            const loss = dailyRate * (r.status === 'halfday' ? 0.5 : 1);
            label = `Absence (${r.status === 'halfday' ? 'Half Day' : 'Full Day'}): ${r.note ? r.note + " — " : ""}${isD ? `Salary Deducted (-${fmt(loss)})` : 'Paid Leave'}`;
          } else if (r.kind === "expense") {
            label = `Out-of-Pocket Expense Paid by Worker: ${r.description || "Expense"}${r.category ? " (" + r.category + ")" : ""}`;
          } else if (r.kind === "sales_cash") {
            label = `🛒 Customer Sale Cash Collected: ${r.item} (from ${r.customerName})${r.note ? " — " + r.note : ""}`;
          }
          const color = r.kind==="work" ? "var(--teal)" : (r.kind==="advance" ? "var(--gold)" : (r.kind==="expense" ? "#16A34A" : (r.kind==="sales_cash" ? "#D97706" : "var(--rust)")));
          const sign = (r.kind==="work" || r.kind==="expense" || r.kind==="sales_cash") ? "+" : "-";
          const amt = r.kind==="pay" ? (r.amount + (r.reimbursedAmount||0)) : (r.kind==="attendance" ? (r.deductSalary !== false ? dailyRate * (r.status === 'halfday' ? 0.5 : 1) : 0) : r.amount);
          return `<div class="history-row" ${r.kind==="sales_cash" ? 'style="background:#FEF3C7;"' : ''}><span class="mono text-muted" style="width:95px">${r.date}</span><span style="flex:1">${label}</span><span class="mono strong" style="color:${color}">${sign}${fmt(amt)}</span></div>`;
        }).join('');
      }
    }

    function toggleAdvForm() {
      const f = document.getElementById('adv-form-wrap');
      f.style.display = f.style.display === 'none' ? 'block' : 'none';
    }

    function togglePurchasingForm() {
      const f = document.getElementById('purchasing-form-wrap');
      f.style.display = f.style.display === 'none' ? 'block' : 'none';
    }

    async function deleteAdvance(id) {
      const confirm = await Swal.fire({
        title: 'Delete Advance?',
        text: "Are you sure you want to delete this advance? This will remove it from the employee's balance.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      });

      if (confirm.isConfirmed) {
        await mutate(() => {
          state.data.advances = state.data.advances.filter(a => a.id !== id);
        });
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'The advance has been deleted.' });
      }
    }

    async function editAdvance(id) {
      const adv = (state.data.advances || []).find(a => a.id === id);
      if (!adv) return;

      const partners = state.data.config.partners || [];
      const partnerOptions = partners.map(p => 
        `<option value="${p.id}" ${adv.paidBy === p.id ? 'selected' : ''}>Given by ${esc(p.name)} individually</option>`
      ).join('');

      const isJoining = adv.isJoiningAdvance || (adv.note && adv.note.toLowerCase().includes("joining"));

      const { value: formValues } = await Swal.fire({
        title: '✏️ Edit Advance Record',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div>
              <label style="font-size:12px;font-weight:600;">Advance Type / Category</label>
              <select id="swal-adv-type" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="joining" ${isJoining ? 'selected' : ''}>🤝 Joining Advance (Peshgi)</option>
                <option value="weekly" ${!isJoining ? 'selected' : ''}>💵 Weekly Advance (Kharcha)</option>
              </select>
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Date</label>
              <input id="swal-adv-date" type="date" class="swal2-input" value="${adv.date}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Amount *</label>
              <input id="swal-adv-amount" type="number" class="swal2-input" value="${adv.amount}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Paid By / Source</label>
              <select id="swal-adv-paidby" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="business" ${adv.paidBy === 'business' || !adv.paidBy ? 'selected' : ''}>🏢 Business Funds (Shared by Ratio)</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                ${partnerOptions}
              </select>
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Note / Description</label>
              <input id="swal-adv-note" class="swal2-input" value="${esc(adv.note || '')}" placeholder="Advance details..." style="margin:4px 0 0 0;width:100%;">
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const type = document.getElementById('swal-adv-type').value;
          const date = document.getElementById('swal-adv-date').value || adv.date;
          const amount = Number(document.getElementById('swal-adv-amount').value);
          const paidBy = document.getElementById('swal-adv-paidby').value;
          const note = document.getElementById('swal-adv-note').value.trim();

          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          return { type, date, amount, paidBy, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          const target = (state.data.advances || []).find(a => a.id === id);
          if (target) {
            target.date = formValues.date;
            target.amount = formValues.amount;
            target.paidBy = formValues.paidBy;
            target.note = formValues.note || (formValues.type === 'joining' ? 'Joining Advance (Peshgi)' : 'Weekly Advance');
            target.isJoiningAdvance = formValues.type === 'joining';
          }
          const emp = state.data.config.employees.find(e => e.id === EMP_ID);
          if (emp && formValues.type === 'joining') {
            emp.joiningAdvance = formValues.amount;
            emp.joiningPaidBy = formValues.paidBy;
          }
        });
        Swal.fire({ icon: 'success', title: 'Updated!', text: 'Advance record updated successfully.' });
      }
    }

    async function savePurchasingCash() {
      const amount = Number(document.getElementById('pcash-amount').value);
      const date = document.getElementById('pcash-date').value || new Date().toISOString().split('T')[0];
      const paidBy = document.getElementById('pcash-paidby').value;
      const note = document.getElementById('pcash-note').value.trim() || 'Purchasing Material Cash';

      if (!amount || isNaN(amount) || amount <= 0) {
        Swal.fire('⚠️ Invalid Amount', 'Please enter a valid cash amount greater than zero.', 'warning');
        return;
      }

      await mutate(() => {
        const entry = {
          id: 'adv_' + Math.random().toString(36).substr(2, 9),
          employeeId: EMP_ID,
          date,
          amount,
          paidBy,
          note,
          isPurchasingCash: true,
          settled: false
        };
        state.data.cashHandouts = state.data.cashHandouts || [];
        state.data.cashHandouts.unshift(entry);
      });

      document.getElementById('purchasing-form-wrap').style.display = 'none';
      Swal.fire({ icon: 'success', title: '🛍️ Purchasing Cash Given!', text: `Recorded ${fmt(amount)} cash handed to worker for purchases.` });
    }

    async function showReturnCashModal() {
      const cashHandouts = (state.data.cashHandouts || []).filter(c => c.employeeId === EMP_ID && !c.settled);
      const totalCashHanded = cashHandouts.reduce((s, c) => s + Number(c.amount || 0), 0);
      const workerExpenses = (state.data.expenses || []).filter(e => e.payerEmployeeId === EMP_ID && !e.settled);
      const totalWorkerSpent = workerExpenses.reduce((s, e) => s + Number(e.amount || 0), 0);
      const unspentPurchasingCash = Math.max(0, totalCashHanded - totalWorkerSpent);

      if (unspentPurchasingCash <= 0) {
        Swal.fire('ℹ️ No Cash Outstanding', 'Worker does not currently hold any unspent purchasing cash.', 'info');
        return;
      }

      const { value: formValues } = await Swal.fire({
        title: 'Return Unspent Purchasing Cash',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div style="background:#FEF3C7;padding:10px;border-radius:6px;font-size:13px;" class="mono">
              <strong>Unspent Cash Held:</strong> <span style="color:var(--rust);font-weight:700;">${fmt(unspentPurchasingCash)}</span>
            </div>
            <div><label style="font-size:12px;font-weight:600;">Return Date</label><input id="swal-ret-date" type="date" class="swal2-input" value="${new Date().toISOString().split('T')[0]}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Amount Returned *</label><input id="swal-ret-amount" type="number" class="swal2-input" value="${unspentPurchasingCash}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Received By Partner</label><select id="swal-ret-receiver" class="swal2-input" style="margin:4px 0 0 0;width:100%;">${state.data.config.partners.map(p=>`<option value="${p.id}">${p.name}</option>`).join('')}</select></div>
            <div><label style="font-size:12px;font-weight:600;">Note (optional)</label><input id="swal-ret-note" class="swal2-input" placeholder="e.g. Remaining cash returned after purchasing" style="margin:4px 0 0 0;width:100%;"></div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const date = document.getElementById('swal-ret-date').value || new Date().toISOString().split('T')[0];
          const amount = Number(document.getElementById('swal-ret-amount').value);
          const receiver = document.getElementById('swal-ret-receiver').value;
          const note = document.getElementById('swal-ret-note').value.trim();

          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          if (amount > unspentPurchasingCash) {
            Swal.showValidationMessage(`Return amount cannot exceed held cash (${fmt(unspentPurchasingCash)}).`);
            return false;
          }
          return { date, amount, receiver, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          state.data.expenses = state.data.expenses || [];
          state.data.expenses.unshift({
            id: 'exp_' + Math.random().toString(36).substr(2, 9),
            date: formValues.date,
            description: `Unspent Cash Returned by Worker (${formValues.note || 'Cash Return'})`,
            amount: -formValues.amount,
            payerEmployeeId: EMP_ID,
            paidBy: formValues.receiver,
            settled: false
          });
        });
        Swal.fire({ icon: 'success', title: '💵 Cash Returned!', text: 'Unspent cash returned to business/partner.' });
      }
    }

    async function deletePurchasingCash(id) {
      const confirm = await Swal.fire({
        title: 'Delete Purchasing Cash?',
        text: "Are you sure you want to delete this purchasing cash record?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      });

      if (confirm.isConfirmed) {
        await mutate(() => {
          if (state.data.cashHandouts) {
            state.data.cashHandouts = state.data.cashHandouts.filter(c => c.id !== id);
          }
          if (state.data.advances) {
            state.data.advances = state.data.advances.filter(a => a.id !== id);
          }
        });
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Purchasing cash record has been deleted.' });
      }
    }

    async function editPurchasingCash(id) {
      let item = (state.data.cashHandouts || []).find(c => c.id === id);
      if (!item) {
        item = (state.data.advances || []).find(a => a.id === id);
      }
      if (!item) return;

      const partners = state.data.config.partners || [];
      const partnerOptions = partners.map(p => 
        `<option value="${p.id}" ${item.paidBy === p.id ? 'selected' : ''}>${esc(p.name)}</option>`
      ).join('');

      const { value: formValues } = await Swal.fire({
        title: '✏️ Edit Purchasing Cash Entry',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div>
              <label style="font-size:12px;font-weight:600;">Date</label>
              <input id="swal-edit-pcash-date" type="date" class="swal2-input" value="${item.date}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Amount *</label>
              <input id="swal-edit-pcash-amount" type="number" class="swal2-input" value="${item.amount}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Paid By / Source</label>
              <select id="swal-edit-pcash-paidby" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="business" ${item.paidBy === 'business' || !item.paidBy ? 'selected' : ''}>🏢 Business Funds</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                ${partnerOptions}
              </select>
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Purpose / Note</label>
              <input id="swal-edit-pcash-note" class="swal2-input" value="${esc(item.note || '')}" placeholder="Purpose (e.g. Buying leather)" style="margin:4px 0 0 0;width:100%;">
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const date = document.getElementById('swal-edit-pcash-date').value || item.date;
          const amount = Number(document.getElementById('swal-edit-pcash-amount').value);
          const paidBy = document.getElementById('swal-edit-pcash-paidby').value;
          const note = document.getElementById('swal-edit-pcash-note').value.trim() || 'Purchasing Material Cash';

          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          return { date, amount, paidBy, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          let target = (state.data.cashHandouts || []).find(c => c.id === id);
          if (target) {
            target.date = formValues.date;
            target.amount = formValues.amount;
            target.paidBy = formValues.paidBy;
            target.note = formValues.note;
          }
          let targetAdv = (state.data.advances || []).find(a => a.id === id);
          if (targetAdv) {
            targetAdv.date = formValues.date;
            targetAdv.amount = formValues.amount;
            targetAdv.paidBy = formValues.paidBy;
            targetAdv.note = formValues.note;
          }
        });
        Swal.fire({ icon: 'success', title: 'Updated!', text: 'Purchasing cash entry updated successfully.' });
      }
    }

    async function deleteReturnedCash(id) {
      const confirm = await Swal.fire({
        title: 'Delete Returned Cash Record?',
        text: "Are you sure you want to delete this returned cash record?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      });

      if (confirm.isConfirmed) {
        await mutate(() => {
          if (state.data.expenses) {
            state.data.expenses = state.data.expenses.filter(e => e.id !== id);
          }
        });
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Returned cash record deleted.' });
      }
    }

    async function editReturnedCash(id) {
      const item = (state.data.expenses || []).find(e => e.id === id);
      if (!item) return;

      const currentAmt = Math.abs(item.amount);
      const partners = state.data.config.partners || [];
      const partnerOptions = partners.map(p => 
        `<option value="${p.id}" ${item.paidBy === p.id ? 'selected' : ''}>${esc(p.name)}</option>`
      ).join('');

      const { value: formValues } = await Swal.fire({
        title: '✏️ Edit Returned Cash Entry',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div>
              <label style="font-size:12px;font-weight:600;">Return Date</label>
              <input id="swal-edit-ret-date" type="date" class="swal2-input" value="${item.date}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Amount Returned *</label>
              <input id="swal-edit-ret-amount" type="number" class="swal2-input" value="${currentAmt}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Received By Partner</label>
              <select id="swal-edit-ret-receiver" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                ${partnerOptions}
              </select>
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Note / Description</label>
              <input id="swal-edit-ret-note" class="swal2-input" value="${esc(item.description || '')}" style="margin:4px 0 0 0;width:100%;">
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const date = document.getElementById('swal-edit-ret-date').value || item.date;
          const amount = Number(document.getElementById('swal-edit-ret-amount').value);
          const receiver = document.getElementById('swal-edit-ret-receiver').value;
          const note = document.getElementById('swal-edit-ret-note').value.trim();

          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          return { date, amount, receiver, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          const target = (state.data.expenses || []).find(e => e.id === id);
          if (target) {
            target.date = formValues.date;
            target.amount = -Math.abs(formValues.amount);
            target.paidBy = formValues.receiver;
            target.description = formValues.note || `Unspent Cash Returned by Worker`;
          }
        });
        Swal.fire({ icon: 'success', title: 'Updated!', text: 'Returned cash entry updated successfully.' });
      }
    }

    async function saveAdvance() {
      const amount = Number(document.getElementById('adv-amount').value);
      const date = document.getElementById('adv-date').value || new Date().toISOString().split('T')[0];
      const paidBy = document.getElementById('adv-paidby').value;
      const note = document.getElementById('adv-note').value.trim();

      if (!amount || isNaN(amount) || amount <= 0) {
        Swal.fire('⚠️ Invalid Amount', 'Please enter a valid advance amount greater than zero.', 'warning');
        return;
      }

      await mutate(() => {
        state.data.advances.unshift({
          id: 'adv_' + Math.random().toString(36).substr(2, 9),
          employeeId: EMP_ID,
          date,
          amount,
          paidBy,
          note,
          settled: false
        });
      });

      document.getElementById('adv-form-wrap').style.display = 'none';
      Swal.fire({ toast: true, icon: 'success', title: '💵 Advance recorded!', timer: 2000, showConfirmButton: false });
    }

    async function saveAttendance() {
      const date = document.getElementById('att-date').value || new Date().toISOString().split('T')[0];
      const status = document.getElementById('att-status').value;
      const deductSalary = document.getElementById('att-deduct').value === 'yes';
      const note = document.getElementById('att-note').value.trim();

      await mutate(() => {
        state.data.attendanceLogs = state.data.attendanceLogs || [];
        state.data.attendanceLogs.unshift({
          id: 'att_' + Math.random().toString(36).substr(2, 9),
          employeeId: EMP_ID,
          date,
          status,
          deductSalary,
          note,
          settled: false
        });
      });

      Swal.fire({ toast: true, icon: 'success', title: '📅 Absence recorded!', timer: 2000, showConfirmButton: false });
    }

    async function savePayday() {
      const emp = state.data.config.employees.find(e => e.id === EMP_ID);
      const advances = (state.data.advances || []).filter(a => a.employeeId === EMP_ID);
      const payments = (state.data.salaryPayments || []).filter(p => p.employeeId === EMP_ID);

      const joiningAdvEntry = advances.find(a => a.isJoiningAdvance || (a.note && a.note.toLowerCase().includes("joining")));
      const joiningAdvVal = emp.joiningAdvance || (joiningAdvEntry ? joiningAdvEntry.amount : 0);

      const weeklyAdvEntries = advances.filter(a => !a.isJoiningAdvance && !(a.note && a.note.toLowerCase().includes("joining")));

      const totalWeeklyAdvGiven = weeklyAdvEntries.reduce((s, a) => s + Number(a.amount || 0), 0);
      const totalAdvReturned = payments.reduce((s, p) => s + Number(p.deductedAdvances || 0), 0);
      const weeklyAdvVal = Math.max(0, totalWeeklyAdvGiven - totalAdvReturned);
      const outstandingAdv = weeklyAdvVal + (joiningAdvEntry && !joiningAdvEntry.settled ? joiningAdvEntry.amount : 0);

      const weeklyDeductAmt = state.deductWeeklyAdv ? (state.customWeeklyDeduct !== null ? Number(state.customWeeklyDeduct) : weeklyAdvVal) : 0;
      const joiningDeductAmt = state.deductJoiningAdv ? (state.customJoiningDeduct !== null ? Number(state.customJoiningDeduct) : joiningAdvVal) : 0;
      const advDeductVal = Math.min(outstandingAdv, Math.max(0, weeklyDeductAmt + joiningDeductAmt));

      const rawCashHandouts = (state.data.cashHandouts || []).filter(c => c.employeeId === EMP_ID && !c.settled);
      const uniqueHandouts = Array.from(new Map(rawCashHandouts.map(item => [item.id, item])).values());
      const totalCashHanded = uniqueHandouts.reduce((s, c) => s + Number(c.amount || 0), 0);
      const workerExpenses = (state.data.expenses || []).filter(e => e.payerEmployeeId === EMP_ID && !e.settled);
      const totalWorkerSpent = workerExpenses.reduce((s, e) => s + Number(e.amount || 0), 0);
      
      const netPurchasingDiff = totalCashHanded - totalWorkerSpent;
      const unspentPurchasingCash = netPurchasingDiff > 0 ? netPurchasingDiff : 0;
      const reimbursementOwed = netPurchasingDiff < 0 ? Math.abs(netPurchasingDiff) : 0;

      const actualCashHeldDeducted = (state.deductCashHeld && unspentPurchasingCash > 0) ? unspentPurchasingCash : 0;
      const actualReimbAdded = (state.includeReimb && reimbursementOwed > 0) ? reimbursementOwed : 0;

      const date = document.getElementById('pay-date').value || new Date().toISOString().split('T')[0];
      const amountInput = document.getElementById('pay-amount').value;
      const paidBy = document.getElementById('pay-paidby').value;
      const note = document.getElementById('pay-note').value.trim();

      const defaultWD = emp.type === "monthly" ? 26 : 6;
      const wDays = emp.workingDays || defaultWD;
      const baseRate = emp.type === "monthly" ? Number(emp.monthlyRate || 0) : Number(emp.weeklyRate || 0);

      const attLogs = (state.data.attendanceLogs || []).filter(a => a.employeeId === EMP_ID);
      const unsettledAbs = attLogs.filter(a => !a.settled && a.status !== 'present');
      const dailyRate = baseRate / (wDays > 0 ? wDays : defaultWD);
      let absenceDeductionVal = 0;
      unsettledAbs.forEach(a => {
        if (a.deductSalary !== false) {
          absenceDeductionVal += dailyRate * (a.status === 'halfday' ? 0.5 : 1);
        }
      });

      const actualAbsDeducted = state.deductAtt ? absenceDeductionVal : 0;
      const suggestedWage = Math.max(0, baseRate - advDeductVal - actualCashHeldDeducted + actualReimbAdded - actualAbsDeducted);
      const wageAmount = amountInput !== "" ? Number(amountInput) : suggestedWage;

      if (isNaN(wageAmount) || wageAmount < 0) {
        Swal.fire('⚠️ Invalid Payment Amount', 'Payment amount cannot be negative.', 'warning');
        return;
      }

      await mutate(() => {
        state.data.salaryPayments.unshift({
          id: 'pay_' + Math.random().toString(36).substr(2, 9),
          employeeId: EMP_ID,
          date,
          amount: wageAmount,
          reimbursedAmount: actualReimbAdded,
          paidBy,
          note,
          deductedAdvances: advDeductVal,
          deductedHeld: actualCashHeldDeducted,
          deductedAbsences: actualAbsDeducted
        });

        if (state.deductAtt) {
          (state.data.attendanceLogs || []).forEach(a => {
            if (a.employeeId === EMP_ID && !a.settled && a.status !== 'present') {
              a.settled = true;
            }
          });
        }

        if (actualReimbAdded > 0) {
          (state.data.expenses || []).forEach(e => {
            if (e.payerEmployeeId === EMP_ID && !e.settled) {
              e.settled = true;
            }
          });
        }

        if (advDeductVal > 0) {
          let rem = advDeductVal;
          const empAdv = state.data.advances
            .filter(a => a.employeeId === EMP_ID && !a.settled && !a.isPurchasingCash)
            .sort((a, b) => (a.isJoiningAdvance ? 1 : 0) - (b.isJoiningAdvance ? 1 : 0));

          for (let a of empAdv) {
            if (rem <= 0) break;
            if (rem >= a.amount) {
              rem -= a.amount;
              a.settled = true;
            } else {
              a.amount -= rem;
              rem = 0;
            }
          }
        }
      });

      Swal.fire({ icon: 'success', title: '🎉 Payday Settled!', text: 'Salary payment, advance deductions, absence penalties, and expense reimbursements recorded successfully.' });
    }

    async function deleteAttendance(attId) {
      const confirm = await Swal.fire({
        title: 'Delete Attendance Log?',
        text: 'Are you sure you want to remove this attendance record?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF3B30',
        confirmButtonText: 'Yes, Delete'
      });
      if (confirm.isConfirmed) {
        await mutate(() => {
          state.data.attendanceLogs = (state.data.attendanceLogs || []).filter(a => a.id !== attId);
        });
        Swal.fire({ toast: true, icon: 'success', title: 'Record deleted!', timer: 1500, showConfirmButton: false });
      }
    }

    async function showEditModal() {
      const emp = state.data.config.employees.find(e => e.id === EMP_ID);
      const partners = state.data.config.partners || [];
      const partnerOptions = partners.map(p => 
        `<option value="${p.id}" ${emp.joiningPaidBy === p.id ? 'selected' : ''}>Given by ${esc(p.name)} individually</option>`
      ).join('');

      const joiningAdvEntry = (state.data.advances || []).find(a => a.employeeId === emp.id && (a.isJoiningAdvance || (a.note && a.note.toLowerCase().includes("joining"))));
      const isOpeningCurrent = joiningAdvEntry ? (joiningAdvEntry.isOpeningAdvance !== false) : (emp.joiningIsOpening !== false);

      const { value: formValues } = await Swal.fire({
        title: 'Edit Employee Profile',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div><label style="font-size:12px;font-weight:600;">Employee Name</label><input id="swal-emp-name" class="swal2-input" value="${esc(emp.name||'')}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Phone Number</label><input id="swal-emp-phone" class="swal2-input" value="${esc(emp.phone||'')}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Monthly Base Rate</label><input id="swal-emp-monthly" type="number" class="swal2-input" value="${emp.monthlyRate||0}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Weekly Rate</label><input id="swal-emp-weekly" type="number" class="swal2-input" value="${emp.weeklyRate||0}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Joining Advance (Peshgi Amount)</label><input id="swal-emp-joining-adv" type="number" class="swal2-input" value="${emp.joiningAdvance||0}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Peshgi Paid Source (Given By)</label>
              <select id="swal-emp-joining-paidby" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="business" ${(emp.joiningPaidBy||'business')==='business'?'selected':''}>🏢 Business Funds (Shared by Ratio)</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                ${partnerOptions}
              </select>
            </div>
            <div><label style="font-size:12px;font-weight:600;">Peshgi Outflow Type</label>
              <select id="swal-emp-joining-isopening" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="true" ${isOpeningCurrent ? 'selected' : ''}>⏳ Opening / Pre-existing Advance (Exclude from current Outflows)</option>
                <option value="false" ${!isOpeningCurrent ? 'selected' : ''}>💸 Current Outflow (Paid out during active period)</option>
              </select>
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const name = document.getElementById('swal-emp-name').value.trim();
          const phone = document.getElementById('swal-emp-phone').value.trim();
          const monthlyRate = Number(document.getElementById('swal-emp-monthly').value || 0);
          const weeklyRate = Number(document.getElementById('swal-emp-weekly').value || 0);
          const joiningAdvance = Number(document.getElementById('swal-emp-joining-adv').value || 0);
          const joiningPaidBy = document.getElementById('swal-emp-joining-paidby').value || 'business';
          const joiningIsOpening = document.getElementById('swal-emp-joining-isopening').value === 'true';
          if (!name) {
            Swal.showValidationMessage('Please enter employee name.');
            return false;
          }
          return { name, phone, monthlyRate, weeklyRate, joiningAdvance, joiningPaidBy, joiningIsOpening };
        }
      });

      if (formValues) {
        await mutate(() => {
          emp.name = formValues.name;
          emp.phone = formValues.phone;
          emp.monthlyRate = formValues.monthlyRate;
          emp.weeklyRate = formValues.weeklyRate;
          emp.joiningAdvance = formValues.joiningAdvance;
          emp.joiningPaidBy = formValues.joiningPaidBy;
          emp.joiningIsOpening = formValues.joiningIsOpening;

          const joiningAdvEntry = (state.data.advances || []).find(a => a.employeeId === emp.id && (a.isJoiningAdvance || (a.note && a.note.toLowerCase().includes("joining"))));
          if (joiningAdvEntry) {
            joiningAdvEntry.amount = formValues.joiningAdvance;
            joiningAdvEntry.paidBy = formValues.joiningPaidBy;
            joiningAdvEntry.isOpeningAdvance = formValues.joiningIsOpening;
          } else if (formValues.joiningAdvance > 0) {
            state.data.advances = state.data.advances || [];
            state.data.advances.unshift({
              id: 'adv_' + Math.random().toString(36).substr(2, 9),
              employeeId: emp.id,
              date: new Date().toISOString().split('T')[0],
              amount: formValues.joiningAdvance,
              paidBy: formValues.joiningPaidBy,
              note: "Joining Advance (Peshgi)",
              settled: false,
              isJoiningAdvance: true,
              isOpeningAdvance: formValues.joiningIsOpening
            });
          }
        });
      }
    }

    function calcWorkTotal() {
      const qty = Number(document.getElementById('wl-qty')?.value || 0);
      const rate = Number(document.getElementById('wl-rate')?.value || 0);
      const tot = qty * rate;
      if (document.getElementById('wl-total-preview')) {
        document.getElementById('wl-total-preview').innerText = fmt(tot);
      }
    }

    function toggleWorkForm() {
      const f = document.getElementById('work-form-wrap');
      if (f) f.style.display = f.style.display === 'none' ? 'block' : 'none';
    }

    function onItemSelect() {
      const sel = document.getElementById('wl-item-sel');
      if (!sel) return;
      const opt = sel.options[sel.selectedIndex];
      const rate = opt ? Number(opt.dataset.rate || 0) : 0;
      const rateEl = document.getElementById('wl-rate');
      if (rateEl) rateEl.value = rate || '';
      calcWorkTotal();
    }

    async function saveWorkLog() {
      const date = document.getElementById('wl-date')?.value || new Date().toISOString().split('T')[0];
      const sel = document.getElementById('wl-item-sel');
      const selectedOpt = sel ? sel.options[sel.selectedIndex] : null;
      const workItemId = sel ? sel.value : '';
      const itemLabel = selectedOpt ? (selectedOpt.dataset.label || selectedOpt.text.split(' —')[0]) : '';
      const quantity = Number(document.getElementById('wl-qty')?.value || 0);
      const unitPrice = Number(document.getElementById('wl-rate')?.value || 0);
      const note = document.getElementById('wl-note')?.value.trim() || '';

      if (!workItemId) {
        Swal.fire('⚠️ No Item Selected', 'Please select a work item from the dropdown.', 'warning');
        return;
      }
      if (!quantity || isNaN(quantity) || quantity <= 0) {
        Swal.fire('⚠️ Invalid Quantity', 'Please enter a valid quantity greater than zero.', 'warning');
        return;
      }

      await mutate(() => {
        state.data.workLogs = state.data.workLogs || [];
        state.data.workLogs.unshift({
          id: 'wl_' + Math.random().toString(36).substr(2, 9),
          employeeId: EMP_ID,
          workItemId,
          date,
          itemLabel,
          quantity,
          unitPrice,
          amount: quantity * unitPrice,
          note
        });
      });

      if (sel) sel.selectedIndex = 0;
      const rateEl = document.getElementById('wl-rate');
      if (rateEl) rateEl.value = '';
      const qtyEl = document.getElementById('wl-qty');
      if (qtyEl) qtyEl.value = '';
      const noteEl = document.getElementById('wl-note');
      if (noteEl) noteEl.value = '';
      if (document.getElementById('wl-total-preview')) document.getElementById('wl-total-preview').innerText = 'Rs.0.00';
      if (document.getElementById('work-form-wrap')) document.getElementById('work-form-wrap').style.display = 'none';
      Swal.fire({ icon: 'success', title: '🔨 Work Production Saved!', text: `Recorded ${quantity} × ${itemLabel} = ${fmt(quantity * unitPrice)} earned.` });
    }

    async function deleteWorkLog(wlId) {
      const confirm = await Swal.fire({
        title: 'Delete Work Production Log?',
        text: 'Are you sure you want to remove this work log?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF3B30',
        confirmButtonText: 'Yes, Delete'
      });
      if (confirm.isConfirmed) {
        await mutate(() => {
          state.data.workLogs = (state.data.workLogs || []).filter(w => w.id !== wlId);
        });
        Swal.fire({ toast: true, icon: 'success', title: 'Work log deleted!', timer: 1500, showConfirmButton: false });
      }
    }

    async function editWorkLog(wlId) {
      const w = (state.data.workLogs || []).find(log => log.id === wlId);
      if (!w) return;

      const workItems = state.data.workItems || [];
      const itemOptions = workItems.map(wi => 
        `<option value="${wi.id}" data-rate="${wi.unitPrice}" data-label="${esc(wi.name)}" ${w.workItemId === wi.id ? 'selected' : ''}>${esc(wi.name)} — ${fmt(wi.unitPrice)} / ${esc(wi.unit||'piece')}</option>`
      ).join('');

      const { value: formValues } = await Swal.fire({
        title: '✏️ Edit Work Production Entry',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div>
              <label style="font-size:12px;font-weight:600;">Date</label>
              <input id="swal-wl-date" type="date" class="swal2-input" value="${w.date}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Select Work Item *</label>
              <select id="swal-wl-item" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="">— Select item —</option>
                ${itemOptions}
              </select>
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Quantity *</label>
              <input id="swal-wl-qty" type="number" class="swal2-input" value="${w.quantity}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Rate Per Pair/Unit (Rs.) *</label>
              <input id="swal-wl-rate" type="number" class="swal2-input" value="${w.unitPrice}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Note (optional)</label>
              <input id="swal-wl-note" class="swal2-input" value="${esc(w.note || '')}" placeholder="Details..." style="margin:4px 0 0 0;width:100%;">
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        didOpen: () => {
          const sel = document.getElementById('swal-wl-item');
          if (sel) {
            sel.addEventListener('change', () => {
              const opt = sel.options[sel.selectedIndex];
              if (opt && opt.dataset.rate) {
                document.getElementById('swal-wl-rate').value = opt.dataset.rate;
              }
            });
          }
        },
        preConfirm: () => {
          const date = document.getElementById('swal-wl-date').value || w.date;
          const sel = document.getElementById('swal-wl-item');
          const workItemId = sel ? sel.value : w.workItemId;
          const opt = sel ? sel.options[sel.selectedIndex] : null;
          const itemLabel = opt && opt.value ? (opt.dataset.label || opt.text.split(' —')[0]) : w.itemLabel;
          const quantity = Number(document.getElementById('swal-wl-qty').value);
          const unitPrice = Number(document.getElementById('swal-wl-rate').value);
          const note = document.getElementById('swal-wl-note').value.trim();

          if (!quantity || isNaN(quantity) || quantity <= 0) {
            Swal.showValidationMessage('Please enter a valid quantity greater than zero.');
            return false;
          }
          if (!unitPrice || isNaN(unitPrice) || unitPrice <= 0) {
            Swal.showValidationMessage('Please enter a valid rate per unit.');
            return false;
          }
          return { date, workItemId, itemLabel, quantity, unitPrice, amount: quantity * unitPrice, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          const target = (state.data.workLogs || []).find(log => log.id === wlId);
          if (target) {
            target.date = formValues.date;
            if (formValues.workItemId) target.workItemId = formValues.workItemId;
            if (formValues.itemLabel) target.itemLabel = formValues.itemLabel;
            target.quantity = formValues.quantity;
            target.unitPrice = formValues.unitPrice;
            target.amount = formValues.amount;
            target.note = formValues.note;
          }
        });
        Swal.fire({ icon: 'success', title: 'Updated!', text: 'Work production entry updated successfully.' });
      }
    }

    document.addEventListener('DOMContentLoaded', loadData);
  </script>
</body>
</html>

