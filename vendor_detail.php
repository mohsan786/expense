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

$vendor_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$data = get_ledger_data() ?: [
    'config' => ['currency' => 'Rs.', 'partners' => [], 'vendors' => []],
    'expenses' => [],
    'vendorPayments' => []
];

$vendors = $data['config']['vendors'] ?? [];
$partners = $data['config']['partners'] ?? [];
$currency = $data['config']['currency'] ?? 'Rs.';

$vendor = null;
foreach ($vendors as $v) {
    if ($v['id'] === $vendor_id) {
        $vendor = $v;
        break;
    }
}

if (!$vendor) {
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
  <title><?php echo htmlspecialchars($vendor['name']); ?> — Vendor Purchases &amp; Payments | Velto LS</title>
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
  </style>
</head>
<body>

  <!-- Top Header Navigation -->
  <header class="navbar-custom d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
      <a href="index.php" class="btn btn-outline-light btn-sm fw-bold px-3">← Back to Main Ledger</a>
      <div>
        <h4 class="mb-0 fw-bold serif" style="letter-spacing:-0.5px;"><?php echo htmlspecialchars($vendor['name']); ?></h4>
        <small class="text-light opacity-75">
          <?php echo $vendor['phone'] ? '📞 ' . htmlspecialchars($vendor['phone']) . ' · ' : ''; ?>
          Vendor Credit &amp; Raw Material Ledger
        </small>
      </div>
    </div>
    <div>
      <button class="btn btn-sm btn-light fw-bold px-3" onclick="showEditVendorModal()">✏️ Edit Vendor Profile</button>
    </div>
  </header>

  <div class="container-fluid px-md-5">

    <!-- KPI Dashboard Summary Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-4 col-12">
        <div class="kpi-card">
          <div class="kpi-title">Total Credit Purchases</div>
          <div class="kpi-value text-danger" id="kpi-credit">—</div>
          <div class="small text-muted">Purchases Made On Credit</div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="kpi-card">
          <div class="kpi-title">Total Payments Paid</div>
          <div class="kpi-value text-success" id="kpi-paid">—</div>
          <div class="small text-muted">Payments Made to Vendor</div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="kpi-card" style="border-color:var(--rust);">
          <div class="kpi-title">Net Outstanding Credit Due</div>
          <div class="kpi-value text-danger" id="kpi-outstanding">—</div>
          <div class="small text-muted">Remaining Balance Owed</div>
        </div>
      </div>
    </div>

    <!-- Main Content & Forms -->
    <div class="row g-4">
      
      <!-- Left Column: Purchases & Payment Breakthrough Table -->
      <div class="col-lg-7 col-12">
        
        <!-- Breakthrough Ledger Table -->
        <div class="content-panel">
          <div class="panel-header">
            <span>📦 Purchase &amp; Payment Ledger Breakthrough</span>
            <span class="badge bg-light text-dark border mono fs-6" id="badge-due">—</span>
          </div>

          <div class="table-responsive mb-3">
            <table class="table table-sm table-hover table-bordered align-middle mb-0" style="font-size:12.5px;">
              <thead class="table-dark mono" style="font-size:11px;">
                <tr>
                  <th>DATE</th>
                  <th>TYPE</th>
                  <th>DESCRIPTION / NOTE</th>
                  <th class="text-end">PURCHASE (+)</th>
                  <th class="text-end">PAID (-)</th>
                  <th>PAYMENT METHOD</th>
                  <th class="text-center" style="width:90px;">ACTION</th>
                </tr>
              </thead>
              <tbody id="vendor-breakdown-body">
                <tr><td colspan="7" class="text-center py-3 text-muted">Loading vendor ledger…</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Form 1: Log New Purchase -->
        <div class="content-panel">
          <div class="panel-header">
            <span>📦 Log New Material Purchase</span>
          </div>
          <div class="row g-2">
            <div class="col-md-3 col-6"><label class="form-label small mb-1">Date</label><input type="date" id="vp-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
            <div class="col-md-3 col-6"><label class="form-label small mb-1">Amount *</label><input type="number" id="vp-amount" class="form-control form-control-sm" placeholder="Amount"></div>
            <div class="col-md-6 col-12"><label class="form-label small mb-1">Item Description *</label><input id="vp-desc" class="form-control form-control-sm" placeholder="e.g. Leather soles, Thread rolls"></div>
            <div class="col-12"><label class="form-label small mb-1">Payment Method</label>
              <select id="vp-payment" class="form-select form-select-sm">
                <option value="credit" selected>💳 On Credit (Add to Vendor Balance)</option>
                <option value="business">💼 Business Funds (Shared by Ratio)</option>
                <option value="split_ratio">💸 Split by Partners (Ratio)</option>
                <?php foreach ($partners as $p): ?>
                  <option value="partner:<?php echo htmlspecialchars($p['id']); ?>">Paid by <?php echo htmlspecialchars($p['name']); ?> directly</option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <button class="btn btn-dark btn-sm fw-bold mt-3 w-100" onclick="savePurchase()">+ Log Purchase</button>
        </div>

      </div>

      <!-- Right Column: Record Vendor Payment -->
      <div class="col-lg-5 col-12">

        <!-- Form 2: Pay Vendor -->
        <div class="content-panel" style="background:#F0FDF4;border-color:#BBF7D0;">
          <div class="panel-header" style="color:#15803D;">
            <span>💰 Record Vendor Payment</span>
          </div>

          <div class="mb-3">
            <div class="small text-muted mb-1">Outstanding Vendor Balance:</div>
            <div class="mono strong text-danger fs-4" id="box-outstanding">—</div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6"><label class="form-label small mb-1">Payment Date</label><input type="date" id="vpay-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
            <div class="col-6"><label class="form-label small mb-1">Amount Paid *</label><input type="number" id="vpay-amount" class="form-control form-control-sm" placeholder="Amount"></div>
            <div class="col-12"><label class="form-label small mb-1">Paid By Partner</label>
              <select id="vpay-paidby" class="form-select form-select-sm"><option value="business">&#x1F4BC; Business Funds (Shared by Ratio)</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                <?php foreach ($partners as $p): ?>
                  <option value="<?php echo htmlspecialchars($p['id']); ?>">Paid by <?php echo htmlspecialchars($p['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12"><label class="form-label small mb-1">Note / Cheque Ref (optional)</label><input id="vpay-note" class="form-control form-control-sm" placeholder="e.g. Bank transfer ref #1234"></div>
          </div>

          <button class="btn btn-success btn-lg w-100 fw-bold shadow-sm" onclick="savePayment()">💰 Record Vendor Payment</button>
        </div>

      </div>

    </div>

  </div>

  <script>
    const VENDOR_ID = <?php echo json_encode($vendor_id); ?>;
    let state = { data: null };

    function esc(s) {
      return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function fmt(n) {
      const c = state.data ? state.data.config.currency : 'Rs.';
      return `${c}${Number(n || 0).toFixed(2)}`;
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
      const vendor = state.data.config.vendors.find(v => v.id === VENDOR_ID);
      if (!vendor) return;

      const partners = state.data.config.partners || [];
      const purchases = (state.data.expenses || []).filter(e => e.vendorId === VENDOR_ID);
      const vPayments = (state.data.vendorPayments || []).filter(p => p.vendorId === VENDOR_ID);

      const totalCreditPurchases = purchases.filter(e => e.onCredit).reduce((s, e) => s + Number(e.amount || 0), 0);
      const totalPaid = vPayments.reduce((s, p) => s + Number(p.amount || 0), 0);
      const outstanding = totalCreditPurchases - totalPaid;

      // Render KPIs
      document.getElementById('kpi-credit').innerText = fmt(totalCreditPurchases);
      document.getElementById('kpi-paid').innerText = fmt(totalPaid);
      document.getElementById('kpi-outstanding').innerText = fmt(outstanding);
      document.getElementById('box-outstanding').innerText = fmt(outstanding);
      document.getElementById('badge-due').innerText = `Net Balance: ${fmt(outstanding)}`;

      // Render Breakthrough Table
      const historyItems = [
        ...purchases.map(e => {
          let pm = "Cash / Direct";
          if (e.onCredit) pm = "Credit Purchase";
          else if (e.paidBy) {
            const p = partners.find(pt => pt.id === e.paidBy);
            pm = `Paid by ${p ? p.name : 'Partner'}`;
          }
          return {
            id: e.id,
            kind: 'purchase',
            date: e.date,
            type: e.onCredit ? 'Credit Purchase (+)' : 'Direct Purchase',
            note: e.description,
            purchase: Number(e.amount || 0),
            paid: 0,
            pm
          };
        }),
        ...vPayments.map(p => {
          const pt = partners.find(x => x.id === p.paidBy);
          return {
            id: p.id,
            kind: 'payment',
            date: p.date,
            type: 'Payment to Vendor (-)',
            note: p.note ? `Vendor Payment: ${p.note}` : 'Vendor Payment',
            purchase: 0,
            paid: Number(p.amount || 0),
            pm: `Paid by ${pt ? pt.name : 'Partner'}`
          };
        })
      ].sort((a, b) => b.date.localeCompare(a.date));

      const tbody = document.getElementById('vendor-breakdown-body');
      if (historyItems.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">No purchases or payments logged for this vendor yet.</td></tr>`;
      } else {
        tbody.innerHTML = historyItems.map(ev => `
          <tr>
            <td class="mono muted">${ev.date}</td>
            <td><span class="badge ${ev.purchase > 0 ? 'bg-secondary' : 'bg-success'}">${ev.type}</span></td>
            <td>${esc(ev.note)}</td>
            <td class="mono text-end ${ev.purchase > 0 ? 'fw-bold text-danger' : 'text-muted'}">${ev.purchase > 0 ? fmt(ev.purchase) : '—'}</td>
            <td class="mono text-end ${ev.paid > 0 ? 'fw-bold text-success' : 'text-muted'}">${ev.paid > 0 ? fmt(ev.paid) : '—'}</td>
            <td class="small">${esc(ev.pm)}</td>
            <td class="text-center">
              ${ev.kind === 'purchase'
                ? `<button class="btn btn-sm btn-outline-primary py-0 px-1 border-0" onclick="editVendorPurchase('${ev.id}')" title="Edit Purchase">✏️</button>
                   <button class="btn btn-sm btn-outline-danger py-0 px-1 border-0" onclick="deleteVendorPurchase('${ev.id}')" title="Delete Purchase">🗑️</button>`
                : `<button class="btn btn-sm btn-outline-primary py-0 px-1 border-0" onclick="editVendorPayment('${ev.id}')" title="Edit Payment">✏️</button>
                   <button class="btn btn-sm btn-outline-danger py-0 px-1 border-0" onclick="deleteVendorPayment('${ev.id}')" title="Delete Payment">🗑️</button>`
              }
            </td>
          </tr>
        `).join('');
      }
    }

    async function savePurchase() {
      const date = document.getElementById('vp-date').value || new Date().toISOString().split('T')[0];
      const amount = Number(document.getElementById('vp-amount').value);
      const description = document.getElementById('vp-desc').value.trim();
      const paymentVal = document.getElementById('vp-payment').value;

      if (!description) {
        Swal.fire('⚠️ Missing Description', 'Please enter a purchase item description.', 'warning');
        return;
      }
      if (!amount || isNaN(amount) || amount <= 0) {
        Swal.fire('⚠️ Invalid Amount', 'Please enter a valid purchase amount greater than zero.', 'warning');
        return;
      }

      const entry = {
        id: 'exp_' + Math.random().toString(36).substr(2, 9),
        date,
        description,
        amount,
        vendorId: VENDOR_ID
      };

      if (paymentVal === 'credit') {
        entry.onCredit = true;
      } else if (paymentVal === 'business') {
        entry.onCredit = false;
        entry.paidBy = 'business';
      } else if (paymentVal === 'split_ratio') {
        entry.onCredit = false;
        entry.paidBy = 'split_ratio';
      } else {
        const [, partnerId] = paymentVal.split(':');
        entry.paidBy = partnerId;
        entry.onCredit = false;
      }

      await mutate(() => {
        state.data.expenses.unshift(entry);
      });

      document.getElementById('vp-amount').value = '';
      document.getElementById('vp-desc').value = '';
      Swal.fire({ toast: true, icon: 'success', title: '📦 Purchase logged!', timer: 2000, showConfirmButton: false });
    }

    async function savePayment() {
      const purchases = (state.data.expenses || []).filter(e => e.vendorId === VENDOR_ID);
      const vPayments = (state.data.vendorPayments || []).filter(p => p.vendorId === VENDOR_ID);
      const totalCreditPurchases = purchases.filter(e => e.onCredit).reduce((s, e) => s + Number(e.amount || 0), 0);
      const totalPaid = vPayments.reduce((s, p) => s + Number(p.amount || 0), 0);
      const outstanding = Math.max(0, totalCreditPurchases - totalPaid);

      const date = document.getElementById('vpay-date').value || new Date().toISOString().split('T')[0];
      const amountInput = document.getElementById('vpay-amount').value;
      const amount = amountInput ? Number(amountInput) : outstanding;
      const paidBy = document.getElementById('vpay-paidby').value;
      const note = document.getElementById('vpay-note').value.trim();

      if (!amount || isNaN(amount) || amount <= 0) {
        Swal.fire('⚠️ Invalid Payment Amount', 'Please enter a valid vendor payment amount greater than zero.', 'warning');
        return;
      }
      if (amount > outstanding) {
        Swal.fire('⚠️ Exceeds Outstanding Balance', `Payment amount (${fmt(amount)}) exceeds the vendor's total outstanding balance of ${fmt(outstanding)}.`, 'warning');
        return;
      }

      await mutate(() => {
        state.data.vendorPayments = state.data.vendorPayments || [];
        state.data.vendorPayments.unshift({
          id: 'vpay_' + Math.random().toString(36).substr(2, 9),
          vendorId: VENDOR_ID,
          date,
          amount,
          paidBy,
          note
        });
      });

      document.getElementById('vpay-amount').value = '';
      document.getElementById('vpay-note').value = '';
      Swal.fire({ icon: 'success', title: '💰 Payment Recorded!', text: 'Vendor payment logged successfully.' });
    }

    async function showEditVendorModal() {
      const vendor = state.data.config.vendors.find(v => v.id === VENDOR_ID);
      const { value: formValues } = await Swal.fire({
        title: 'Edit Vendor Profile',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div><label style="font-size:12px;font-weight:600;">Vendor Name</label><input id="swal-v-name" class="swal2-input" value="${vendor.name||''}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Phone Number</label><input id="swal-v-phone" class="swal2-input" value="${vendor.phone||''}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Address / Note</label><input id="swal-v-note" class="swal2-input" value="${vendor.note||''}" style="margin:4px 0 0 0;width:100%;"></div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const name = document.getElementById('swal-v-name').value.trim();
          const phone = document.getElementById('swal-v-phone').value.trim();
          const note = document.getElementById('swal-v-note').value.trim();
          return { name, phone, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          vendor.name = formValues.name;
          vendor.phone = formValues.phone;
          vendor.note = formValues.note;
        });
      }
    }

    async function deleteVendorPurchase(id) {
      const confirm = await Swal.fire({
        title: 'Delete Purchase Record?',
        text: "Are you sure you want to delete this purchase record?",
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
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Purchase record deleted.' });
      }
    }

    async function editVendorPurchase(id) {
      const exp = (state.data.expenses || []).find(e => e.id === id);
      if (!exp) return;

      const partners = state.data.config.partners || [];
      const partnerOptions = partners.map(p => 
        `<option value="partner:${p.id}" ${exp.paidBy === p.id ? 'selected' : ''}>Paid by ${esc(p.name)} directly</option>`
      ).join('');

      const { value: formValues } = await Swal.fire({
        title: '✏️ Edit Material Purchase',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div>
              <label style="font-size:12px;font-weight:600;">Date</label>
              <input id="swal-vp-date" type="date" class="swal2-input" value="${exp.date}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Amount *</label>
              <input id="swal-vp-amount" type="number" class="swal2-input" value="${exp.amount}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Item Description *</label>
              <input id="swal-vp-desc" class="swal2-input" value="${esc(exp.description || '')}" placeholder="e.g. Leather soles" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Payment Method</label>
              <select id="swal-vp-payment" class="swal2-input" style="margin:4px 0 0 0;width:100%;">
                <option value="credit" ${exp.onCredit ? 'selected' : ''}>💳 On Credit (Add to Vendor Balance)</option>
                <option value="business" ${exp.paidBy === 'business' ? 'selected' : ''}>💼 Business Funds (Shared by Ratio)</option>
                <option value="split_ratio" ${exp.paidBy === 'split_ratio' ? 'selected' : ''}>💸 Split by Partners (Ratio)</option>
                ${partnerOptions}
              </select>
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const date = document.getElementById('swal-vp-date').value || exp.date;
          const amount = Number(document.getElementById('swal-vp-amount').value);
          const description = document.getElementById('swal-vp-desc').value.trim();
          const paymentVal = document.getElementById('swal-vp-payment').value;

          if (!description) {
            Swal.showValidationMessage('Please enter a description.');
            return false;
          }
          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          return { date, amount, description, paymentVal };
        }
      });

      if (formValues) {
        await mutate(() => {
          const target = (state.data.expenses || []).find(e => e.id === id);
          if (target) {
            target.date = formValues.date;
            target.amount = formValues.amount;
            target.description = formValues.description;
            if (formValues.paymentVal === 'credit') {
              target.onCredit = true;
              delete target.paidBy;
            } else if (formValues.paymentVal === 'business') {
              target.onCredit = false;
              target.paidBy = 'business';
            } else if (formValues.paymentVal === 'split_ratio') {
              target.onCredit = false;
              target.paidBy = 'split_ratio';
            } else {
              target.onCredit = false;
              const [, partnerId] = formValues.paymentVal.split(':');
              target.paidBy = partnerId;
            }
          }
        });
        Swal.fire({ icon: 'success', title: 'Updated!', text: 'Purchase record updated successfully.' });
      }
    }

    async function deleteVendorPayment(id) {
      const confirm = await Swal.fire({
        title: 'Delete Payment Record?',
        text: "Are you sure you want to delete this vendor payment record?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      });

      if (confirm.isConfirmed) {
        await mutate(() => {
          if (state.data.vendorPayments) {
            state.data.vendorPayments = state.data.vendorPayments.filter(p => p.id !== id);
          }
        });
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Vendor payment record deleted.' });
      }
    }

    async function editVendorPayment(id) {
      const pmt = (state.data.vendorPayments || []).find(p => p.id === id);
      if (!pmt) return;

      const partners = state.data.config.partners || [];
      const partnerOptions = partners.map(p => 
        `<option value="${p.id}" ${pmt.paidBy === p.id ? 'selected' : ''}>Paid by ${esc(p.name)}</option>`
      ).join('');

      const { value: formValues } = await Swal.fire({
        title: '✏️ Edit Vendor Payment',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div>
              <label style="font-size:12px;font-weight:600;">Payment Date</label>
              <input id="swal-vpay-date" type="date" class="swal2-input" value="${pmt.date}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Amount Paid *</label>
              <input id="swal-vpay-amount" type="number" class="swal2-input" value="${pmt.amount}" style="margin:4px 0 0 0;width:100%;">
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Paid By Partner</label>
              <select id="swal-vpay-paidby" class="swal2-input" style="margin:4px 0 0 0;width:100%;"><option value="business">&#x1F4BC; Business Funds (Shared by Ratio)</option><option value="split_ratio">&#x1F4B8; Split by Partners (Ratio)</option>
                ${partnerOptions}
              </select>
            </div>
            <div>
              <label style="font-size:12px;font-weight:600;">Note / Cheque Ref</label>
              <input id="swal-vpay-note" class="swal2-input" value="${esc(pmt.note || '')}" style="margin:4px 0 0 0;width:100%;">
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const date = document.getElementById('swal-vpay-date').value || pmt.date;
          const amount = Number(document.getElementById('swal-vpay-amount').value);
          const paidBy = document.getElementById('swal-vpay-paidby').value;
          const note = document.getElementById('swal-vpay-note').value.trim();

          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          return { date, amount, paidBy, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          const target = (state.data.vendorPayments || []).find(p => p.id === id);
          if (target) {
            target.date = formValues.date;
            target.amount = formValues.amount;
            target.paidBy = formValues.paidBy;
            target.note = formValues.note;
          }
        });
        Swal.fire({ icon: 'success', title: 'Updated!', text: 'Vendor payment updated successfully.' });
      }
    }

    document.addEventListener('DOMContentLoaded', loadData);
  </script>
</body>
</html>

