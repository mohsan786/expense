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

$cust_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$data = get_ledger_data() ?: [
    'config' => ['currency' => 'Rs.', 'partners' => [], 'customers' => [], 'employees' => []],
    'income' => []
];

$customers = $data['config']['customers'] ?? [];
$partners = $data['config']['partners'] ?? [];
$employees = $data['config']['employees'] ?? [];
$currency = $data['config']['currency'] ?? 'Rs.';

$customer = null;
foreach ($customers as $c) {
    if ($c['id'] === $cust_id) {
        $customer = $c;
        break;
    }
}

if (!$customer) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($customer['name']); ?> — Customer Sales &amp; Ledger | Velto LS</title>
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
  </style>
</head>
<body>

  <!-- Top Header Navigation -->
  <header class="navbar-custom d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
      <a href="index.php" class="btn btn-outline-light btn-sm fw-bold px-3">← Back to Main Ledger</a>
      <div>
        <h4 class="mb-0 fw-bold serif" style="letter-spacing:-0.5px;"><?php echo htmlspecialchars($customer['name']); ?></h4>
        <small class="text-light opacity-75">
          <?php echo $customer['phone'] ? '📞 ' . htmlspecialchars($customer['phone']) . ' · ' : ''; ?>
          Customer Profile &amp; Order Ledger
        </small>
      </div>
    </div>
    <div class="d-flex gap-2">
      <?php if ($customer['phone']): ?>
        <button class="btn btn-sm btn-success fw-bold px-3" onclick="sendWaReminder()">📱 WhatsApp Reminder</button>
      <?php endif; ?>
      <button class="btn btn-sm btn-light fw-bold px-3" onclick="showEditCustomerModal()">✏️ Edit Profile</button>
    </div>
  </header>

  <div class="container-fluid px-md-5">

    <!-- KPI Dashboard Summary Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-4 col-12">
        <div class="kpi-card">
          <div class="kpi-title">Total Orders Value</div>
          <div class="kpi-value text-dark" id="kpi-total-sales">—</div>
          <div class="small text-muted">Total Sales Logged</div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="kpi-card">
          <div class="kpi-title">Total Cash Collected</div>
          <div class="kpi-value text-success" id="kpi-paid-sales">—</div>
          <div class="small text-muted">Payments Received So Far</div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="kpi-card" style="border-color:var(--rust);">
          <div class="kpi-title">Net Outstanding Balance Due</div>
          <div class="kpi-value text-danger" id="kpi-due-sales">—</div>
          <div class="small text-muted">Remaining Customer Receivable</div>
        </div>
      </div>
    </div>

    <!-- Main Content Panels -->
    <div class="row g-4">
      
      <!-- Left Column: Sales Breakdown Table -->
      <div class="col-lg-8 col-12">
        <div class="content-panel">
          <div class="panel-header">
            <span>🛒 Sales &amp; Receivables Ledger</span>
            <span class="badge bg-light text-dark border mono fs-6" id="badge-due">—</span>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered align-middle mb-0" style="font-size:12.5px;">
              <thead class="table-dark mono" style="font-size:11px;">
                <tr>
                  <th>DATE</th>
                  <th>ITEM DESCRIPTION</th>
                  <th>QTY</th>
                  <th class="text-end">TOTAL PRICE</th>
                  <th class="text-end">PAID</th>
                  <th class="text-end">BALANCE DUE</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody id="sales-breakdown-body">
                <tr><td colspan="7" class="text-center py-3 text-muted">Loading customer sales…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Right Column: Log New Sale Form -->
      <div class="col-lg-4 col-12">
        <div class="content-panel">
          <div class="panel-header">
            <span>💵 Log New Sale for Customer</span>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6"><label class="form-label small mb-1">Date</label><input type="date" id="ns-date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
            <div class="col-6"><label class="form-label small mb-1">Quantity</label><input type="number" id="ns-qty" class="form-control form-control-sm" placeholder="Qty"></div>
            <div class="col-12"><label class="form-label small mb-1">Item Description *</label><input id="ns-item" class="form-control form-control-sm" placeholder="e.g. Leather shoes (Model #A1)"></div>
            <div class="col-6"><label class="form-label small mb-1">Total Price *</label><input type="number" id="ns-total" class="form-control form-control-sm" placeholder="Total amount"></div>
            <div class="col-6"><label class="form-label small mb-1">Paid Amount</label><input type="number" id="ns-paid" class="form-control form-control-sm" placeholder="Paid amount"></div>
            <div class="col-12"><label class="form-label small mb-1">Payment Received By</label>
              <select id="ns-receiver" class="form-select form-select-sm">
                <optgroup label="Partners">
                  <?php foreach ($partners as $p): ?>
                    <option value="partner:<?php echo htmlspecialchars($p['id']); ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php if (!empty($employees)): ?>
                  <optgroup label="Employees (Holding Cash)">
                    <?php foreach ($employees as $emp): ?>
                      <option value="employee:<?php echo htmlspecialchars($emp['id']); ?>"><?php echo htmlspecialchars($emp['name']); ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
              </select>
            </div>
            <div class="col-12"><label class="form-label small mb-1">Order Note (optional)</label><input id="ns-note" class="form-control form-control-sm" placeholder="Optional order details..."></div>
          </div>

          <button class="btn btn-dark btn-lg w-100 fw-bold shadow-sm" onclick="saveNewSale()">+ Log Sale Record</button>
        </div>
      </div>

    </div>

  </div>

  <script>
    const CUST_ID = <?php echo json_encode($cust_id); ?>;
    let state = { data: null };

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
      const customer = state.data.config.customers.find(c => c.id === CUST_ID);
      if (!customer) return;

      const sales = (state.data.income || []).filter(inc => {
        if (inc.customerId === CUST_ID) return true;
        if (customer.phone && inc.customerPhone === customer.phone) return true;
        if (inc.customerName && inc.customerName.toLowerCase() === customer.name.toLowerCase()) return true;
        return false;
      });

      let totalSales = 0;
      let paidSales = 0;

      sales.forEach(s => {
        const total = Number(s.amount || 0);
        const paid = s.paidAmount !== undefined ? Number(s.paidAmount) : total;
        totalSales += total;
        paidSales += paid;
      });

      const dueSales = Math.max(0, totalSales - paidSales);

      // Render KPIs
      document.getElementById('kpi-total-sales').innerText = fmt(totalSales);
      document.getElementById('kpi-paid-sales').innerText = fmt(paidSales);
      document.getElementById('kpi-due-sales').innerText = fmt(dueSales);
      document.getElementById('badge-due').innerText = `Total Receivable Due: ${fmt(dueSales)}`;

      // Render Sales Table
      const tbody = document.getElementById('sales-breakdown-body');
      if (sales.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">No sales logged for this customer yet.</td></tr>`;
      } else {
        tbody.innerHTML = sales.map(s => {
          const total = Number(s.amount || 0);
          const paid = s.paidAmount !== undefined ? Number(s.paidAmount) : total;
          const due = Math.max(0, total - paid);
          const isSettled = due <= 0;

          return `
            <tr>
              <td class="mono muted">${s.date}</td>
              <td class="fw-bold">${s.item}</td>
              <td class="mono text-center">${s.quantity || 1}</td>
              <td class="mono text-end fw-bold">${fmt(total)}</td>
              <td class="mono text-end text-success">${fmt(paid)}</td>
              <td class="mono text-end fw-bold ${due > 0 ? 'text-danger' : 'text-muted'}">${fmt(due)}</td>
              <td>
                ${due > 0 ? `<button class="btn btn-sm btn-success fw-bold px-2 py-0" style="font-size:11px;" onclick="receivePaymentModal('${s.id}')">💰 Receive Pay</button>` : `<span class="badge bg-light text-dark border">Paid</span>`}
              </td>
            </tr>
          `;
        }).join('');
      }
    }

    async function saveNewSale() {
      const customer = state.data.config.customers.find(c => c.id === CUST_ID);
      const date = document.getElementById('ns-date').value || new Date().toISOString().split('T')[0];
      const item = document.getElementById('ns-item').value.trim();
      const quantity = document.getElementById('ns-qty').value ? Number(document.getElementById('ns-qty').value) : null;
      const totalAmount = Number(document.getElementById('ns-total').value);
      const paidInput = document.getElementById('ns-paid').value;
      const paidAmount = paidInput !== "" ? Number(paidInput) : totalAmount;
      const receiverVal = document.getElementById('ns-receiver').value;
      const note = document.getElementById('ns-note').value.trim();

      if (!item) {
        Swal.fire('⚠️ Missing Item', 'Please enter item description.', 'warning');
        return;
      }
      if (!totalAmount || isNaN(totalAmount) || totalAmount <= 0) {
        Swal.fire('⚠️ Invalid Total Price', 'Total Sale Price must be greater than zero.', 'warning');
        return;
      }
      if (paidAmount < 0) {
        Swal.fire('⚠️ Invalid Paid Amount', 'Paid Amount cannot be negative.', 'warning');
        return;
      }
      if (paidAmount > totalAmount) {
        Swal.fire('⚠️ Paid Amount Exceeds Total', `Paid Amount (${fmt(paidAmount)}) cannot exceed Total Price (${fmt(totalAmount)}).`, 'warning');
        return;
      }

      const [receiverType, receiverId] = receiverVal.split(':');

      await mutate(() => {
        state.data.income.unshift({
          id: 'inc_' + Math.random().toString(36).substr(2, 9),
          date,
          item,
          quantity,
          amount: totalAmount,
          paidAmount,
          receivedBy: receiverId,
          receivedByType: receiverType,
          customerId: CUST_ID,
          customerName: customer.name,
          customerPhone: customer.phone || '',
          note,
          payments: paidAmount > 0 ? [{ id: 'pm_' + Math.random().toString(36).substr(2, 9), date, amount: paidAmount, receiverVal, note: 'Initial payment' }] : []
        });
      });

      document.getElementById('ns-item').value = '';
      document.getElementById('ns-total').value = '';
      document.getElementById('ns-paid').value = '';
      Swal.fire({ icon: 'success', title: '💵 Sale Logged!', text: 'Customer order logged successfully.' });
    }

    async function receivePaymentModal(saleId) {
      const sale = state.data.income.find(s => s.id === saleId);
      if (!sale) return;
      const partners = state.data.config.partners || [];
      const employees = state.data.config.employees || [];

      const totalAmt = Number(sale.amount || 0);
      const paidAmt = sale.paidAmount !== undefined ? Number(sale.paidAmount) : totalAmt;
      const remaining = Math.max(0, totalAmt - paidAmt);

      const optionsHtml = `
        <optgroup label="Partners">${partners.map(p=>`<option value="partner:${p.id}">${p.name}</option>`).join("")}</optgroup>
        ${employees.length ? `<optgroup label="Worker (holding cash)">${employees.map(emp=>`<option value="employee:${emp.id}">${emp.name}</option>`).join("")}</optgroup>` : ""}
      `;

      const { value: formValues } = await Swal.fire({
        title: 'Receive Due Payment',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div style="background:#F1F5F9;padding:10px 12px;border-radius:6px;font-size:13px;" class="mono">
              <div><strong>Item:</strong> ${sale.item}</div>
              <div><strong>Total Price:</strong> ${fmt(totalAmt)}</div>
              <div><strong>Paid So Far:</strong> ${fmt(paidAmt)}</div>
              <div style="color:var(--rust);font-weight:700;">Remaining Due: ${fmt(remaining)}</div>
            </div>
            <div><label style="font-size:12px;font-weight:600;">Payment Date</label><input id="swal-pay-date" class="swal2-input" type="date" value="${new Date().toISOString().split('T')[0]}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Amount Received *</label><input id="swal-pay-amount" type="number" class="swal2-input" value="${remaining}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">Received By</label><select id="swal-pay-receiver" class="swal2-input" style="margin:4px 0 0 0;width:100%;">${optionsHtml}</select></div>
            <div><label style="font-size:12px;font-weight:600;">Note (optional)</label><input id="swal-pay-note" class="swal2-input" placeholder="e.g. Balance settlement" style="margin:4px 0 0 0;width:100%;"></div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const date = document.getElementById('swal-pay-date').value || new Date().toISOString().split('T')[0];
          const amount = Number(document.getElementById('swal-pay-amount').value);
          const receiverVal = document.getElementById('swal-pay-receiver').value;
          const note = document.getElementById('swal-pay-note').value.trim();

          if (!amount || isNaN(amount) || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount greater than zero.');
            return false;
          }
          if (amount > remaining) {
            Swal.showValidationMessage(`Payment amount (${fmt(amount)}) cannot exceed remaining due balance (${fmt(remaining)}).`);
            return false;
          }
          return { date, amount, receiverVal, note };
        }
      });

      if (formValues) {
        await mutate(() => {
          sale.paidAmount = (sale.paidAmount || 0) + formValues.amount;
          sale.payments = sale.payments || [];
          sale.payments.push({
            id: 'pm_' + Math.random().toString(36).substr(2, 9),
            date: formValues.date,
            amount: formValues.amount,
            receiverVal: formValues.receiverVal,
            note: formValues.note
          });
        });
        Swal.fire({ icon: 'success', title: '🎉 Payment Received!', text: 'Customer balance updated successfully.' });
      }
    }

    function sendWaReminder() {
      const customer = state.data.config.customers.find(c => c.id === CUST_ID);
      if (!customer || !customer.phone) return;
      const sales = (state.data.income || []).filter(s => s.customerId === CUST_ID);
      let totalDue = 0;
      sales.forEach(s => {
        const total = Number(s.amount || 0);
        const paid = s.paidAmount !== undefined ? Number(s.paidAmount) : total;
        totalDue += Math.max(0, total - paid);
      });

      const cleanPhone = customer.phone.replace(/[^0-9]/g, "");
      const formattedPhone = cleanPhone.startsWith("0") ? "92" + cleanPhone.slice(1) : cleanPhone;
      const msg = `Dear ${customer.name},\n\nThis is a polite payment reminder from *Velto Leather Shoes* regarding your account.\n\nYour total outstanding balance due is: *${fmt(totalDue)}*.\n\nPlease arrange for payment at your earliest convenience.\n\nThank you!`;
      window.open(`https://wa.me/${formattedPhone}?text=${encodeURIComponent(msg)}`, '_blank');
    }

    async function showEditCustomerModal() {
      const customer = state.data.config.customers.find(c => c.id === CUST_ID);
      const { value: formValues } = await Swal.fire({
        title: 'Edit Customer Profile',
        html: `
          <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
            <div><label style="font-size:12px;font-weight:600;">Customer Name</label><input id="swal-c-name" class="swal2-input" value="${customer.name||''}" style="margin:4px 0 0 0;width:100%;"></div>
            <div><label style="font-size:12px;font-weight:600;">WhatsApp / Phone</label><input id="swal-c-phone" class="swal2-input" value="${customer.phone||''}" style="margin:4px 0 0 0;width:100%;"></div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        preConfirm: () => {
          const name = document.getElementById('swal-c-name').value.trim();
          const phone = document.getElementById('swal-c-phone').value.trim();
          return { name, phone };
        }
      });

      if (formValues) {
        await mutate(() => {
          customer.name = formValues.name;
          customer.phone = formValues.phone;
        });
      }
    }

    document.addEventListener('DOMContentLoaded', loadData);
  </script>
</body>
</html>
