<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/jpeg" href="favicon.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  /* Apple-Style Clean Light Mode Theme */
  :root {
    --paper: #F5F5F7;
    --ink: #1D1D1F;
    --rule: #E5E5EA;
    --panel: #FFFFFF;
    --muted: #86868B;
    --accent: #007AFF;
    --success: #34C759;
    --danger: #FF3B30;
    --warning: #FF9500;
  }
  
  body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
    color: #1D1D1F;
    background-color: #F5F5F7;
  }
  .mono { font-family: 'JetBrains Mono', monospace; }
  .serif { font-family: 'Newsreader', serif; }

  .app-shell {
    display: flex;
    min-height: 100vh;
    background: #F5F5F7;
  }
  .app-sidebar {
    width: 255px;
    background: #FFFFFF;
    color: #1D1D1F;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    height: 100vh;
    box-shadow: 1px 0 10px rgba(0, 0, 0, 0.03);
    z-index: 100;
    border-right: 1px solid #E5E5EA;
  }
  .sidebar-brand {
    padding: 24px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid #E5E5EA;
  }
  .brand-icon {
    font-size: 20px;
    background: #000000;
    color: #FFFFFF;
    font-weight: 800;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
  }
  .brand-title {
    font-size: 18px;
    font-weight: 800;
    letter-spacing: -0.4px;
    color: #1D1D1F;
  }
  .brand-sub {
    font-size: 11px;
    color: #86868B;
    font-weight: 500;
  }
  .sidebar-nav {
    padding: 16px 12px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
    overflow-y: auto;
  }
  .sidebar-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 16px;
    border: none;
    background: transparent;
    color: #515154;
    font-weight: 600;
    font-size: 14px;
    border-radius: 12px;
    cursor: pointer;
    text-align: left;
    width: 100%;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .sidebar-item:hover {
    background: #F2F2F7;
    color: #1D1D1F;
    transform: translateX(3px);
  }
  .sidebar-item.active {
    background: #000000;
    color: #FFFFFF;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
  }
  .sidebar-footer {
    padding: 16px 20px;
    border-top: 1px solid #E5E5EA;
  }
  .logout-btn {
    background: #F2F2F7;
    color: #1D1D1F;
    border: 1px solid #E5E5EA;
    width: 100%;
    padding: 9px 12px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .logout-btn:hover {
    background: #FF3B30;
    color: #FFFFFF;
    border-color: #FF3B30;
  }

  .main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
  }
  .app-topbar {
    background: #FFFFFF;
    padding: 16px 28px;
    border-bottom: 1px solid #E5E5EA;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
  }
  .topbar-title {
    font-size: 20px;
    font-weight: 800;
    margin: 0;
    color: #1D1D1F;
    letter-spacing: -0.4px;
  }
  .topbar-sub {
    font-size: 13px;
    color: #86868B;
    margin: 2px 0 0 0;
  }
  .quick-act-btn {
    background: #000000;
    color: #FFFFFF;
    border: none;
    padding: 9px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 13.5px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transition: transform 0.2s, background 0.2s;
  }
  .quick-act-btn:hover { transform: translateY(-2px); background: #2C2C2E; }

  .view-container {
    padding: 24px 28px;
    max-width: 1500px;
    width: 100%;
    margin: 0 auto;
    animation: fadeIn 0.25s ease-out;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Apple-Style KPI Cards */
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .kpi-card {
    background: #FFFFFF;
    border: 1px solid #E5E5EA;
    border-radius: 18px;
    padding: 22px;
    color: #1D1D1F;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
  }
  .kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
  }
  .kpi-income .kpi-icon { background: #E8F5E9; color: #34C759; }
  .kpi-expense .kpi-icon { background: #FFEBEE; color: #FF3B30; }
  .kpi-profit .kpi-icon { background: #E3F2FD; color: #007AFF; }
  .kpi-due .kpi-icon { background: #FFF3E0; color: #FF9500; }

  .kpi-icon {
    font-size: 24px;
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
  }
  .kpi-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #86868B;
    text-transform: uppercase;
  }
  .kpi-value {
    font-size: 25px;
    font-weight: 800;
    color: #1D1D1F;
    font-family: 'JetBrains Mono', monospace;
    line-height: 1.2;
  }
  .kpi-sub {
    font-size: 11px;
    color: #86868B;
    margin-top: 2px;
  }
  .kpi-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    opacity: 0.9;
  }
  .kpi-value {
    font-size: 24px;
    font-weight: 800;
    font-family: 'JetBrains Mono', monospace;
    line-height: 1.2;
  }
  .kpi-sub {
    font-size: 11px;
    opacity: 0.85;
    margin-top: 2px;
  }

  @media (max-width: 900px) {
    .app-shell { flex-direction: column; }
    .app-sidebar { width: 100%; height: auto; position: static; }
    .sidebar-nav { flex-direction: row; overflow-x: auto; padding: 10px; }
    .sidebar-item { width: auto; font-size: 13px; padding: 8px 12px; }
    .view-container { padding: 16px; }
  }
  
  /* Tabs */
  .tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 24px;
    background: #FFFFFF;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid var(--rule);
    overflow-x: auto;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
  }
  .tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: transparent;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    color: #64748B;
    font-weight: 600;
    font-size: 13.5px;
    white-space: nowrap;
    transition: all 0.18s ease;
  }
  .tab-btn:hover { color: var(--ink); background: #F1F5F9; }
  .tab-btn.active { color: #FFFFFF; background: var(--teal); font-weight: 600; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25); }
  
  /* Panels & Cards */
  .panel {
    background: var(--panel);
    border: 1px solid var(--rule);
    border-radius: 14px;
    padding: 20px 22px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
  }
  .total-box { padding: 22px 24px; }
  .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 8px; }
  .grid3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  @media (max-width: 560px) { .grid2, .grid3 { grid-template-columns: 1fr; } }
  
  .pc-top { display: flex; justify-content: space-between; align-items: baseline; }
  .scale-bar { display: flex; height: 32px; border-radius: 8px; overflow: hidden; border: 1px solid var(--rule); margin-top: 10px; }
  .scale-seg { display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12.5px; font-weight: 600; font-family: 'JetBrains Mono', monospace; }
  
  .settle-box { background: var(--gold-light); border: 1px solid #FCD34D; border-radius: 10px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; font-size: 14px; color: #78350F; }
  .settled { font-size: 14px; color: var(--muted); }
  
  /* Premium Vibrant Buttons & Input Controls */
  .led-input {
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    color: var(--ink);
    outline: none;
    width: 100%;
    font-family: inherit;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }
  .led-input:focus {
    border-color: #0D9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    background: #FFFFFF;
  }
  .led-btn {
    background: linear-gradient(135deg, #0D9488 0%, #10B981 100%);
    color: #FFFFFF;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }
  .led-btn:hover {
    background: linear-gradient(135deg, #0F766E 0%, #059669 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(13, 148, 136, 0.4);
  }
  .led-btn:active {
    transform: translateY(0);
  }
  .led-btn.secondary {
    background: #F1F5F9;
    color: #1E293B;
    border: 1.5px solid #CBD5E1;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .led-btn.secondary:hover {
    background: #E2E8F0;
    color: #0F172A;
    border-color: #94A3B8;
  }
  
  .icon-btn { background: none; border: none; cursor: pointer; color: #94A3B8; font-size: 14px; padding: 4px; border-radius: 6px; transition: all 0.15s ease; }
  .icon-btn:hover { color: var(--ink); background: #F1F5F9; }
  
  /* Form Grids */
  .form-grid-expense { display: grid; grid-template-columns: 105px 1fr 85px 140px 130px 130px auto; gap: 8px; }
  .form-grid-income { display: grid; grid-template-columns: 110px 1fr 50px 85px 85px 130px 120px auto; gap: 6px; }
  .form-grid-emp { display: grid; grid-template-columns: 1fr 1fr 160px auto; gap: 10px; align-items: center; }
  .form-grid-item { display: grid; grid-template-columns: 1fr 120px auto; gap: 10px; margin-bottom: 8px; }
  .form-grid-work { display: grid; grid-template-columns: 120px 1fr 90px 1fr auto; gap: 10px; margin-bottom: 8px; }
  .form-grid-adv, .form-grid-pay { display: grid; grid-template-columns: 120px 100px 140px 1fr auto; gap: 10px; margin-bottom: 8px; }
  .form-grid-partner { display: grid; grid-template-columns: 1fr 100px 140px; gap: 10px; margin-bottom: 12px; }
  @media (max-width: 640px) { .form-grid-expense, .form-grid-income, .form-grid-emp, .form-grid-item, .form-grid-work, .form-grid-adv, .form-grid-pay { grid-template-columns: 1fr; } }
  
  /* Tables */
  .table-panel { padding: 0; overflow: hidden; }
  .table-head { padding: 10px 16px; font-size: 11.5px; font-weight: 700; color: #64748B; border-bottom: 1px solid var(--rule); background: #F8FAFC; text-transform: uppercase; letter-spacing: 0.5px; }
  .expense-row { display: grid; grid-template-columns: 80px 1fr 110px 85px 120px 110px 75px; gap: 6px; align-items: center; }
  .income-row { display: grid; grid-template-columns: 85px 1fr 45px 95px 95px 140px 110px 110px; gap: 8px; align-items: center; }
  @media (max-width: 560px) {
    .table-head { display: none; }
    .expense-row, .income-row { display: flex; flex-wrap: wrap; gap: 6px 10px; justify-content: space-between; }
    .expense-row > div:nth-child(2), .income-row > div:nth-child(2) { width: 100%; font-weight: 600; order: -1; }
  }
  .row-line { border-bottom: 1px solid var(--rule); padding: 12px 16px; font-size: 14px; transition: background 0.15s ease; }
  .row-line:hover { background: #F8FAFC; }
  .table-panel .row-line:last-child { border-bottom: none; }
  .empty-msg { padding: 24px; font-size: 14px; color: var(--muted); text-align: center; }
  
  /* Employee & Vendor Cards */
  .emp-card { padding: 0; overflow: hidden; transition: border-color 0.15s ease; }
  .emp-card:hover { border-color: #CBD5E1; }
  .emp-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; cursor: pointer; gap: 10px; flex-wrap: wrap; background: #FFFFFF; }
  .emp-head-left { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .emp-head-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .card-body { border-top: 1px solid var(--rule); padding: 20px; background: #FAFAFA; }
  
  .small-title { font-weight: 700; font-size: 13.5px; margin-bottom: 10px; color: #1E293B; letter-spacing: -0.2px; }
  .checkbox-row { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 10px; cursor: pointer; }
  .history-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; border-bottom: 1px solid var(--rule); }
  .item-chip { display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 20px; padding: 5px 12px; font-size: 12.5px; margin: 0 6px 6px 0; font-weight: 500; }
  .item-chip button { border: none; background: none; cursor: pointer; color: #64748B; font-size: 11px; }
  .divider { border-top: 1px dashed var(--rule); margin: 16px 0; }
  
  /* Status Tags */
  .tag { font-size: 11px; padding: 3px 8px; border-radius: 20px; font-weight: 600; display: inline-block; }
  .tag-wait { background: var(--gold-light); color: #B45309; }
  .tag-done { background: var(--teal-light); color: var(--teal-dark); }
  .tag-rust { background: var(--rust-light); color: var(--rust); }
  
  .loading { min-height: 60vh; display: flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--muted); }
  ::placeholder { color: #94A3B8; }

  /* Auth Modal */
  .modal-backdrop { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 9999; }
  .modal-card { background: #FFFFFF; border: 1px solid var(--rule); border-radius: 16px; padding: 32px 28px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.25); }
  .pin-input { font-size: 26px; letter-spacing: 8px; text-align: center; padding: 12px; margin: 18px 0; font-family: 'JetBrains Mono', monospace; border-radius: 10px; }

  /* Mobile Navigation & Responsive Overrides */
  .mobile-header {
    display: none;
    background: #000000;
    color: #FFFFFF;
    padding: 12px 16px;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 1050;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
  }
  .sidebar-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index: 1030;
  }
  @media (max-width: 991px) {
    .app-shell { flex-direction: column; }
    .mobile-header { display: flex; }
    .app-sidebar {
      position: fixed;
      top: 0;
      left: -280px;
      width: 280px;
      height: 100vh;
      z-index: 1040;
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .app-sidebar.show { transform: translateX(280px); }
    .sidebar-backdrop.show { display: block; }
    .main-content { width: 100% !important; min-height: calc(100vh - 60px); }
    .app-topbar { flex-direction: column; align-items: flex-start !important; gap: 12px; }
    .kpi-grid { grid-template-columns: 1fr !important; }
    .table-panel { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .view-container { padding: 14px 12px !important; }
  }
  @media (max-width: 576px) {
    .stat-mini-grid { grid-template-columns: 1fr !important; }
    .emp-head { flex-direction: column; align-items: flex-start !important; }
    .emp-head-right { width: 100%; justify-content: space-between; margin-top: 6px; }
  }

  @media print {
    .tabs, .header, button:not(.no-hide-print), input[type="search"], .icon-btn { display: none !important; }
    body { background: #fff !important; }
    .panel { box-shadow: none !important; border: 1px solid #ccc !important; page-break-inside: avoid; }
  }
</style>
</head>
<body>

<div id="auth-modal" class="modal-backdrop" style="display:none">
  <div class="modal-card">
    <h2 class="serif" style="margin:0 0 6px">The Ledger</h2>
    <p class="muted small" style="margin:0 0 16px">Enter Passcode to Access Business Records</p>
    <form id="login-form">
      <input type="password" id="pin-field" class="led-input pin-input" placeholder="••••" autofocus required autocomplete="current-password">
      <div id="auth-err" class="err" style="display:none;margin-bottom:12px"></div>
      <button type="submit" class="led-btn" style="width:100%;padding:10px">Unlock Ledger</button>
    </form>
  </div>
</div>

<div id="app"></div>

<script>
const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
const today = () => new Date().toISOString().slice(0, 10);
function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

const swalConfirm = async (title, text = "This action cannot be undone.") => {
  const res = await Swal.fire({
    title,
    text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#A6472F',
    cancelButtonColor: '#6b6353',
    confirmButtonText: 'Yes, proceed',
    cancelButtonText: 'Cancel',
    background: '#FCFAF4',
    color: '#23302B'
  });
  return res.isConfirmed;
};

const swalAlert = (title, text = "", icon = "info") => {
  return Swal.fire({
    title,
    text,
    icon,
    confirmButtonColor: '#2F6F63',
    background: '#FCFAF4',
    color: '#23302B'
  });
};

const toast = (title) => {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title,
    showConfirmButton: false,
    timer: 2200,
    timerProgressBar: true
  });
};

async function showEditExpenseModal(id) {
  const e = state.expenses.find(x => x.id === id);
  if (!e) return;
  const partners = state.config.partners;
  const employees = state.config.employees;
  const payerVal = e.payerEmployeeId ? `employee:${e.payerEmployeeId}` : `partner:${e.paidBy}`;
  const optionsHtml = `
    <optgroup label="Partner">${partners.map(p=>`<option value="partner:${p.id}" ${payerVal==='partner:'+p.id?'selected':''}>${esc(p.name)}</option>`).join("")}</optgroup>
    ${employees.length ? `<optgroup label="Employee (reimbursable)">${employees.map(emp=>`<option value="employee:${emp.id}" ${payerVal==='employee:'+emp.id?'selected':''}>${esc(emp.name)}</option>`).join("")}</optgroup>` : ""}
  `;
  const { value: formValues } = await Swal.fire({
    title: '✏️ Edit Expense Record',
    html: `
      <div class="text-start d-flex flex-column gap-2">
        <div><label class="form-label fw-bold small text-dark mb-1">Date</label><input id="swal-exp-date" class="form-control" type="date" value="${e.date||today()}"></div>
        <div><label class="form-label fw-bold small text-dark mb-1">Description</label><input id="swal-exp-desc" class="form-control" value="${esc(e.description||'')}"></div>
        <div><label class="form-label fw-bold small text-dark mb-1">Amount</label><input id="swal-exp-amount" type="number" class="form-control" value="${e.amount||''}"></div>
        <div><label class="form-label fw-bold small text-dark mb-1">Paid By</label><select id="swal-exp-payer" class="form-select">${optionsHtml}</select></div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: '💾 Save Changes',
    confirmButtonColor: '#000000',
    cancelButtonColor: '#64748B',
    preConfirm: () => {
      const date = document.getElementById('swal-exp-date').value || today();
      const description = document.getElementById('swal-exp-desc').value.trim();
      const amount = Number(document.getElementById('swal-exp-amount').value);
      const payerVal = document.getElementById('swal-exp-payer').value;
      if (!description || !amount) {
        Swal.showValidationMessage('Please enter description and amount');
        return false;
      }
      return { date, description, amount, payerVal };
    }
  });

  if (formValues) {
    const [payerType, payerId] = formValues.payerVal.split(':');
    mutate(() => {
      e.date = formValues.date;
      e.description = formValues.description;
      e.amount = formValues.amount;
      if (payerType === 'employee') {
        e.payerEmployeeId = payerId;
        e.paidBy = undefined;
      } else {
        e.paidBy = payerId;
        e.payerEmployeeId = undefined;
      }
    });
  }
}

async function showEditIncomeModal(id) {
  const e = state.income.find(x => x.id === id);
  if (!e) return;
  const partners = state.config.partners;
  const employees = state.config.employees;
  const receiverVal = e.receivedByEmployeeId ? `employee:${e.receivedByEmployeeId}` : `partner:${e.receivedBy}`;
  const optionsHtml = `
    <optgroup label="Partner">${partners.map(p=>`<option value="partner:${p.id}" ${receiverVal==='partner:'+p.id?'selected':''}>${esc(p.name)}</option>`).join("")}</optgroup>
    ${employees.length ? `<optgroup label="Worker (holding cash)">${employees.map(emp=>`<option value="employee:${emp.id}" ${receiverVal==='employee:'+emp.id?'selected':''}>${esc(emp.name)}</option>`).join("")}</optgroup>` : ""}
  `;
  const { value: formValues } = await Swal.fire({
    title: '✏️ Edit Sale Record',
    html: `
      <div class="text-start d-flex flex-column gap-2">
        <div><label class="form-label fw-bold small text-dark mb-1">Date</label><input id="swal-inc-date" class="form-control" type="date" value="${e.date||today()}"></div>
        <div><label class="form-label fw-bold small text-dark mb-1">Item Description</label><input id="swal-inc-item" class="form-control" value="${esc(e.item||'')}"></div>
        <div class="row g-2">
          <div class="col-6"><label class="form-label fw-bold small text-dark mb-1">Quantity</label><input id="swal-inc-qty" type="number" class="form-control" value="${e.quantity||''}"></div>
          <div class="col-6"><label class="form-label fw-bold small text-dark mb-1">Total Amount</label><input id="swal-inc-amount" type="number" class="form-control" value="${e.amount||''}"></div>
        </div>
        <div><label class="form-label fw-bold small text-dark mb-1">Received By</label><select id="swal-inc-receiver" class="form-select">${optionsHtml}</select></div>
        <div><label class="form-label fw-bold small text-dark mb-1">Note / Customer</label><input id="swal-inc-note" class="form-control" value="${esc(e.note||'')}"></div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: '💾 Save Changes',
    confirmButtonColor: '#000000',
    cancelButtonColor: '#64748B',
    preConfirm: () => {
      const date = document.getElementById('swal-inc-date').value || today();
      const item = document.getElementById('swal-inc-item').value.trim();
      const quantity = document.getElementById('swal-inc-qty').value ? Number(document.getElementById('swal-inc-qty').value) : null;
      const amount = Number(document.getElementById('swal-inc-amount').value);
      const receiverVal = document.getElementById('swal-inc-receiver').value;
      const note = document.getElementById('swal-inc-note').value.trim();
      if (!item || !amount) {
        Swal.showValidationMessage('Please enter item and total amount');
        return false;
      }
      return { date, item, quantity, amount, receiverVal, note };
    }
  });

  if (formValues) {
    const [receiverType, receiverId] = formValues.receiverVal.split(':');
    mutate(() => {
      e.date = formValues.date;
      e.item = formValues.item;
      e.quantity = formValues.quantity;
      e.amount = formValues.amount;
      e.note = formValues.note;
      if (receiverType === 'employee') {
        e.receivedByEmployeeId = receiverId;
        e.receivedBy = undefined;
      } else {
        e.receivedBy = receiverId;
        e.receivedByEmployeeId = undefined;
      }
    });
  }
}

async function showReceiveIncomePaymentModal(id) {
  const sale = state.income.find(x => x.id === id);
  if (!sale) return;
  const partners = state.config.partners;
  const employees = state.config.employees;
  const paidAmt = getIncomePaid(sale);
  const remaining = getIncomeBalance(sale);
  const totalAmt = Number(sale.amount || 0);

  const optionsHtml = `
    <optgroup label="Partner">${partners.map(p=>`<option value="partner:${p.id}">${esc(p.name)}</option>`).join("")}</optgroup>
    ${employees.length ? `<optgroup label="Worker (holding cash)">${employees.map(emp=>`<option value="employee:${emp.id}">${esc(emp.name)}</option>`).join("")}</optgroup>` : ""}
  `;

  const { value: formValues } = await Swal.fire({
    title: 'Receive Remaining Payment',
    html:
      `<div style="text-align:left;display:flex;flex-direction:column;gap:10px;">` +
      `<div style="background:#F1F5F9;padding:10px 12px;border-radius:6px;font-size:13px;" class="mono">` +
      `<div><strong>Item:</strong> ${esc(sale.item)}</div>` +
      `<div><strong>Total Price:</strong> ${fmt(totalAmt)}</div>` +
      `<div><strong>Paid So Far:</strong> ${fmt(paidAmt)}</div>` +
      `<div style="color:var(--rust);font-weight:700;">Remaining Due: ${fmt(remaining)}</div>` +
      `</div>` +
      `<div><label style="font-size:12px;font-weight:600;">Payment Date</label><input id="swal-pay-date" class="swal2-input" type="date" value="${today()}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Amount Received</label><input id="swal-pay-amount" type="number" class="swal2-input" value="${remaining}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Received By</label><select id="swal-pay-receiver" class="swal2-input" style="margin:4px 0 0 0;width:100%;">${optionsHtml}</select></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Note / Reference (optional)</label><input id="swal-pay-note" class="swal2-input" placeholder="e.g. Order pickup remaining balance" style="margin:4px 0 0 0;width:100%;"></div>` +
      `</div>`,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonColor: '#2F6F63',
    background: '#FCFAF4',
    color: '#23302B',
    preConfirm: () => {
      const date = document.getElementById('swal-pay-date').value || today();
      const amount = Number(document.getElementById('swal-pay-amount').value);
      const receiverVal = document.getElementById('swal-pay-receiver').value;
      const note = document.getElementById('swal-pay-note').value.trim();
      if (!amount || isNaN(amount) || amount <= 0) {
        Swal.showValidationMessage('Please enter a valid amount greater than zero.');
        return false;
      }
      if (amount > remaining) {
        Swal.showValidationMessage(`Payment amount (${state.config.currency}${amount}) cannot exceed remaining balance due (${state.config.currency}${remaining}).`);
        return false;
      }
      return { date, amount, receiverVal, note };
    }
  });

  if (formValues) {
    const [receiverType, receiverId] = formValues.receiverVal.split(':');
    mutate(() => {
      if (!Array.isArray(sale.payments) || sale.payments.length === 0) {
        sale.payments = [{
          id: uid(),
          date: sale.date,
          amount: Number(sale.amount || 0),
          receivedBy: sale.receivedBy,
          receivedByEmployeeId: sale.receivedByEmployeeId,
          note: sale.note || 'Initial payment',
          settled: sale.settled || false
        }];
      }
      sale.payments.push({
        id: uid(),
        date: formValues.date,
        amount: formValues.amount,
        receivedBy: receiverType === 'partner' ? receiverId : undefined,
        receivedByEmployeeId: receiverType === 'employee' ? receiverId : undefined,
        settled: false,
        note: formValues.note
      });
    });
    toast('💵 Payment collected successfully!');
  }
}

async function showEditEmployeeModal(id) {
  const emp = state.config.employees.find(e => e.id === id);
  if (!emp) return;
  const partners = state.config.partners;
  const { value: formValues } = await Swal.fire({
    title: 'Edit Employee Profile',
    html:
      `<div style="text-align:left;display:flex;flex-direction:column;gap:10px;">` +
      `<div><label style="font-size:12px;font-weight:600;">Employee Name</label><input id="swal-emp-name" class="swal2-input" value="${esc(emp.name||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Phone Number</label><input id="swal-emp-phone" class="swal2-input" value="${esc(emp.phone||'')}" placeholder="e.g. 0300-1234567" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Payment Type</label><select id="swal-emp-type" class="swal2-input" style="margin:4px 0 0 0;width:100%;"><option value="weekly" ${emp.type==='weekly'?'selected':''}>Weekly Rate</option><option value="monthly" ${emp.type==='monthly'?'selected':''}>Monthly Base</option><option value="workbased" ${emp.type==='workbased'?'selected':''}>Piece-Rate (Work Based)</option></select></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Weekly Rate (if applicable)</label><input id="swal-emp-weekly" type="number" class="swal2-input" value="${emp.weeklyRate||0}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Monthly Base Rate (if applicable)</label><input id="swal-emp-monthly" type="number" class="swal2-input" value="${emp.monthlyRate||0}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Joining Advance / Peshgi Amount</label><input id="swal-emp-joining-adv" type="number" class="swal2-input" value="${emp.joiningAdvance||0}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Peshgi Paid Source</label><select id="swal-emp-joining-paidby" class="swal2-input" style="margin:4px 0 0 0;width:100%;"><option value="business" ${(emp.joiningPaidBy||'business')==='business'?'selected':''}>🏢 Business Funds (Shared by Ratio)</option>${partners.map(p=>`<option value="${p.id}" ${emp.joiningPaidBy===p.id?'selected':''}>Given by ${esc(p.name)} individually</option>`).join("")}</select></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Working Days / Cycle (6/wk or 26/mo)</label><input id="swal-emp-workdays" type="number" class="swal2-input" value="${emp.workingDays||(emp.type==='monthly'?26:6)}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `</div>`,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonColor: '#2F6F63',
    background: '#FCFAF4',
    color: '#23302B',
    preConfirm: () => {
      const name = document.getElementById('swal-emp-name').value.trim();
      const phone = document.getElementById('swal-emp-phone').value.trim();
      const type = document.getElementById('swal-emp-type').value;
      const weeklyRate = Number(document.getElementById('swal-emp-weekly').value || 0);
      const monthlyRate = Number(document.getElementById('swal-emp-monthly').value || 0);
      const joiningAdvance = Number(document.getElementById('swal-emp-joining-adv').value || 0);
      const joiningPaidBy = document.getElementById('swal-emp-joining-paidby').value || 'business';
      const workingDays = Number(document.getElementById('swal-emp-workdays').value || (type === 'monthly' ? 26 : 6));
      if (!name) {
        Swal.showValidationMessage('Please enter employee name');
        return false;
      }
      return { name, phone, type, weeklyRate, monthlyRate, joiningAdvance, joiningPaidBy, workingDays };
    }
  });

  if (formValues) {
    mutate(() => {
      emp.name = formValues.name;
      emp.phone = formValues.phone;
      emp.type = formValues.type;
      emp.weeklyRate = formValues.weeklyRate;
      emp.monthlyRate = formValues.monthlyRate;
      emp.joiningAdvance = formValues.joiningAdvance;
      emp.joiningPaidBy = formValues.joiningPaidBy;
      emp.workingDays = formValues.workingDays;
      if (emp.type === 'workbased' && !emp.items) emp.items = [];

      const joiningAdvEntry = state.advances.find(a => a.employeeId === emp.id && (a.isJoiningAdvance || (a.note && a.note.toLowerCase().includes("joining"))));
      if (joiningAdvEntry) {
        joiningAdvEntry.amount = formValues.joiningAdvance;
        joiningAdvEntry.paidBy = formValues.joiningPaidBy;
      } else if (formValues.joiningAdvance > 0) {
        state.advances.unshift({
          id: uid(),
          employeeId: emp.id,
          date: today(),
          amount: formValues.joiningAdvance,
          paidBy: formValues.joiningPaidBy,
          note: "Joining Advance (Peshgi)",
          settled: false,
          isJoiningAdvance: true
        });
      }
    });
  }
}

async function deleteEmployee(id) {
  const confirmDelete = await swalConfirm("Delete Employee?", "All work logs, advances, and payment history for this employee will be deleted.");
  if (!confirmDelete) return;
  mutate(() => {
    state.config.employees = state.config.employees.filter(e => e.id !== id);
    state.workLogs = state.workLogs.filter(w => w.employeeId !== id);
    state.salaryPayments = state.salaryPayments.filter(s => s.employeeId !== id);
    state.advances = state.advances.filter(a => a.employeeId !== id);
    state.attendanceLogs = (state.attendanceLogs || []).filter(a => a.employeeId !== id);
    state.expenses = state.expenses.map(x => x.payerEmployeeId === id ? { ...x, payerEmployeeId: undefined } : x);
    state.income = state.income.map(x => x.receivedByEmployeeId === id ? { ...x, receivedByEmployeeId: undefined } : x);
    if (state.expandedEmp === id) state.expandedEmp = null;
  });
}

async function showEditItemModal(empId, itemId) {
  const emp = state.config.employees.find(e => e.id === empId);
  if (!emp || !emp.items) return;
  const item = emp.items.find(it => it.id === itemId);
  if (!item) return;
  const { value: formValues } = await Swal.fire({
    title: 'Edit Piece-Rate Work Item',
    html:
      `<div style="text-align:left;display:flex;flex-direction:column;gap:10px;">` +
      `<div><label style="font-size:12px;font-weight:600;">Item Name (e.g. Upper Stitching)</label><input id="swal-item-label" class="swal2-input" value="${esc(item.label||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Rate Per Pair / Unit</label><input id="swal-item-rate" type="number" class="swal2-input" value="${item.rate||''}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `</div>`,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonColor: '#2F6F63',
    background: '#FCFAF4',
    color: '#23302B',
    preConfirm: () => {
      const label = document.getElementById('swal-item-label').value.trim();
      const rate = Number(document.getElementById('swal-item-rate').value);
      if (!label || !rate) {
        Swal.showValidationMessage('Please enter item name and rate');
        return false;
      }
      return { label, rate };
    }
  });

  if (formValues) {
    mutate(() => {
      item.label = formValues.label;
      item.rate = formValues.rate;
    });
  }
}

async function showEditVendorModal(id) {
  const vendor = state.config.vendors.find(v => v.id === id);
  if (!vendor) return;
  const { value: formValues } = await Swal.fire({
    title: 'Edit Vendor Profile',
    html:
      `<div style="text-align:left;display:flex;flex-direction:column;gap:10px;">` +
      `<div><label style="font-size:12px;font-weight:600;">Vendor / Supplier Name</label><input id="swal-v-name" class="swal2-input" value="${esc(vendor.name||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Phone Number</label><input id="swal-v-phone" class="swal2-input" value="${esc(vendor.phone||'')}" placeholder="e.g. 0300-9876543" style="margin:4px 0 0 0;width:100%;"></div>` +
      `<div><label style="font-size:12px;font-weight:600;">Note / Address</label><input id="swal-v-note" class="swal2-input" value="${esc(vendor.note||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
      `</div>`,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonColor: '#2F6F63',
    background: '#FCFAF4',
    color: '#23302B',
    preConfirm: () => {
      const name = document.getElementById('swal-v-name').value.trim();
      const phone = document.getElementById('swal-v-phone').value.trim();
      const note = document.getElementById('swal-v-note').value.trim();
      if (!name) {
        Swal.showValidationMessage('Please enter vendor name');
        return false;
      }
      return { name, phone, note };
    }
  });

  if (formValues) {
    mutate(() => {
      vendor.name = formValues.name;
      vendor.phone = formValues.phone;
      vendor.note = formValues.note;
    });
  }
}

let state = {
  config: { currency: "$", partners: [{ id: "a", name: "Partner A", ratio: 2 }, { id: "b", name: "Partner B", ratio: 1 }], employees: [], vendors: [] },
  expenses: [],
  income: [],
  workLogs: [],
  salaryPayments: [],
  advances: [],
  vendorPayments: [],
  attendanceLogs: [],
  tab: "overview",
  expandedEmp: null,
  deductAdvance: {},
  includeReimbursement: {},
  deductHeldIncome: {},
  deductAttendance: {},
  expandedVendor: null,
  incomeSearch: "",
  expenseSearch: "",
  authenticated: false,
  loaded: false,
  saveErr: false,
  saveMsg: ""
};

async function apiCall(action, payload = null) {
  const options = { method: payload ? 'POST' : 'GET' };
  if (payload) {
    options.headers = { 'Content-Type': 'application/json' };
    options.body = JSON.stringify(payload);
  }
  const res = await fetch(`api.php?action=${action}`, options);
  return await res.json();
}

async function checkAuthAndLoad() {
  try {
    const authRes = await apiCall('check_auth');
    if (authRes.authenticated) {
      state.authenticated = true;
      document.getElementById('auth-modal').style.display = 'none';
      await loadData();
    } else {
      state.authenticated = false;
      document.getElementById('auth-modal').style.display = 'flex';
    }
  } catch (e) {
    state.saveErr = true;
    state.saveMsg = "Cannot connect to server API.";
    render();
  }
}

async function loadData() {
  try {
    const res = await apiCall('load');
    const defaultConfig = {
      currency: "RS",
      passcode: "1234",
      partners: [{ id: "p1", name: "Owner 1", ratio: 1, openingCapital: 0 }, { id: "p2", name: "Partner 2", ratio: 1, openingCapital: 0 }],
      employees: [],
      vendors: [],
      customers: [],
      budgets: { "Raw Materials": 5000, "Factory Rent": 3000, "Shop Rent": 1000, "Utilities / Bills": 500, "Packaging": 300, "Worker Food & Tea": 300 }
    };

    if (res.success && res.data && typeof res.data === "object") {
      const d = res.data;
      state.config = d.config && typeof d.config === "object" ? { ...defaultConfig, ...d.config } : defaultConfig;
      if (!Array.isArray(state.config.partners) || state.config.partners.length === 0) state.config.partners = defaultConfig.partners;
      if (!Array.isArray(state.config.employees)) state.config.employees = [];
      if (!Array.isArray(state.config.vendors)) state.config.vendors = [];
      if (!Array.isArray(state.config.customers)) state.config.customers = [];
      if (!state.config.budgets) state.config.budgets = defaultConfig.budgets;
      if (!state.config.currency) state.config.currency = "RS";

      state.expenses = Array.isArray(d.expenses) ? d.expenses : [];
      state.income = Array.isArray(d.income) ? d.income : [];
      state.workLogs = Array.isArray(d.workLogs) ? d.workLogs : [];
      state.workItems = Array.isArray(d.workItems) ? d.workItems : [];
      state.salaryPayments = Array.isArray(d.salaryPayments) ? d.salaryPayments : [];
      const loadedAdvances = Array.isArray(d.advances) ? d.advances : [];
      state.advances = loadedAdvances.filter(a => !a.isPurchasingCash);
      
      let loadedCashHandouts = Array.isArray(d.cashHandouts) ? d.cashHandouts : [];
      // Migrate any purchasing cash entries from advances into cashHandouts if missing
      const purchasingFromAdv = loadedAdvances.filter(a => a.isPurchasingCash);
      purchasingFromAdv.forEach(pa => {
        if (!loadedCashHandouts.find(c => c.id === pa.id)) {
          loadedCashHandouts.unshift(pa);
        }
      });
      state.cashHandouts = loadedCashHandouts;

      state.vendorPayments = Array.isArray(d.vendorPayments) ? d.vendorPayments : [];
      state.attendanceLogs = Array.isArray(d.attendanceLogs) ? d.attendanceLogs : [];

      state.config.employees = (state.config.employees || []).map(e => {
        if (e && e.type === "workbased" && !e.items) {
          const items = (e.unitRate) ? [{ id: uid(), label: e.unitLabel || "unit", rate: e.unitRate }] : [];
          return { ...e, items };
        }
        return e;
      });
    } else {
      state.config = defaultConfig;
      state.expenses = [];
      state.income = [];
      state.workLogs = [];
      state.workItems = [];
      state.salaryPayments = [];
      state.advances = [];
      state.vendorPayments = [];
      state.attendanceLogs = [];
      state.cashHandouts = [];
    }
  } catch (e) {
    state.saveErr = true;
  }
  state.loaded = true;
  render();
}

async function persist() {
  try {
    const payload = {
      config: state.config,
      expenses: state.expenses,
      income: state.income,
      workLogs: state.workLogs,
      workItems: state.workItems || [],
      salaryPayments: state.salaryPayments,
      advances: state.advances,
      vendorPayments: state.vendorPayments,
      attendanceLogs: state.attendanceLogs,
      cashHandouts: state.cashHandouts || []
    };
    const res = await apiCall('save', { data: payload });
    state.saveErr = !res.success;
  } catch (e) {
    state.saveErr = true;
  }
}

function mutate(fn) { fn(); persist(); render(); }

// ── Employee Card Collapse ────────────────────────────────────────────────────
function toggleEmpCollapse(safeId) {
  const body = document.getElementById('emp-collapse-' + safeId);
  const chevron = document.getElementById('emp-chevron-' + safeId);
  if (!body) return;
  const isOpen = body.style.display !== 'none';
  body.style.display = isOpen ? 'none' : 'block';
  if (chevron) chevron.textContent = isOpen ? '▶' : '▼';
}

function calcQuickWorkTotal(safeId) {
  const qty = Number(document.getElementById('qwl-qty-' + safeId)?.value || 0);
  const rate = Number(document.getElementById('qwl-rate-' + safeId)?.value || 0);
  const el = document.getElementById('qwl-total-' + safeId);
  if (el) el.textContent = fmt(qty * rate);
}

// ── Work Item Catalog Functions ────────────────────────────────────────────────
function addWorkItem() {
  const name = document.getElementById('wi-name')?.value.trim() || '';
  const rate = Number(document.getElementById('wi-rate')?.value || 0);
  const unit = document.getElementById('wi-unit')?.value.trim() || 'piece';
  const desc = document.getElementById('wi-desc')?.value.trim() || '';

  if (!name) { swalAlert('Missing Name', 'Please enter an item or operation name.', 'warning'); return; }
  if (!rate || rate <= 0) { swalAlert('Invalid Rate', 'Please enter a valid rate per piece.', 'warning'); return; }

  mutate(() => {
    state.workItems = state.workItems || [];
    state.workItems.push({ id: uid(), name, unitPrice: rate, unit, description: desc });
  });

  document.getElementById('wi-name').value = '';
  document.getElementById('wi-rate').value = '';
  document.getElementById('wi-unit').value = '';
  document.getElementById('wi-desc').value = '';
  Swal.fire({ toast: true, icon: 'success', title: `✅ Work item "${name}" added!`, timer: 1800, showConfirmButton: false, position: 'top-end' });
}

async function deleteWorkItem(wiId) {
  const res = await Swal.fire({ title: 'Delete Work Item?', text: 'This will not affect already logged work entries.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#FF3B30', confirmButtonText: 'Delete' });
  if (res.isConfirmed) {
    mutate(() => { state.workItems = (state.workItems || []).filter(wi => wi.id !== wiId); });
    Swal.fire({ toast: true, icon: 'success', title: 'Item deleted from catalog!', timer: 1500, showConfirmButton: false });
  }
}

async function editWorkItem(wiId) {
  const wi = (state.workItems || []).find(w => w.id === wiId);
  if (!wi) return;

  const { value: form } = await Swal.fire({
    title: '✏️ Edit Work Item',
    html: `
      <div style="text-align:left;display:flex;flex-direction:column;gap:10px;">
        <div>
          <label style="font-size:12px;font-weight:600;color:#166534;">Item / Operation Name *</label>
          <input id="swl-wi-name" class="swal2-input" value="${esc(wi.name)}" placeholder="e.g. Leather Upper Stitching" style="margin:4px 0 0 0;width:100%;">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#166534;">Rate per Unit (Rs.) *</label>
          <input id="swl-wi-rate" type="number" class="swal2-input" value="${wi.unitPrice}" placeholder="e.g. 120" style="margin:4px 0 0 0;width:100%;">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#166534;">Unit</label>
          <input id="swl-wi-unit" class="swal2-input" value="${esc(wi.unit || 'piece')}" placeholder="e.g. pair, piece, dozen" style="margin:4px 0 0 0;width:100%;">
        </div>
        <div>
          <label style="font-size:12px;font-weight:600;color:#166534;">Description (optional)</label>
          <input id="swl-wi-desc" class="swal2-input" value="${esc(wi.description || '')}" placeholder="Short description" style="margin:4px 0 0 0;width:100%;">
        </div>
      </div>`,
    showCancelButton: true,
    confirmButtonColor: '#166534',
    confirmButtonText: '💾 Save Changes',
    focusConfirm: false,
    preConfirm: () => {
      const name = document.getElementById('swl-wi-name').value.trim();
      const rate = Number(document.getElementById('swl-wi-rate').value);
      const unit = document.getElementById('swl-wi-unit').value.trim() || 'piece';
      const desc = document.getElementById('swl-wi-desc').value.trim();
      if (!name) { Swal.showValidationMessage('⚠️ Item name is required'); return false; }
      if (!rate || rate <= 0) { Swal.showValidationMessage('⚠️ Rate must be greater than zero'); return false; }
      return { name, rate, unit, desc };
    }
  });

  if (form) {
    mutate(() => {
      const item = (state.workItems || []).find(w => w.id === wiId);
      if (item) {
        item.name = form.name;
        item.unitPrice = form.rate;
        item.unit = form.unit;
        item.description = form.desc;
      }
    });
    Swal.fire({ toast: true, icon: 'success', title: `✅ "${form.name}" updated!`, text: `New rate: ${fmt(form.rate)} / ${form.unit}`, timer: 2500, showConfirmButton: false, position: 'top-end' });
  }
}

function onQuickItemSelect(safeId) {
  const sel = document.getElementById('qwl-item-' + safeId);
  if (!sel) return;
  const opt = sel.options[sel.selectedIndex];
  const rate = opt ? Number(opt.dataset.rate || 0) : 0;
  const rateEl = document.getElementById('qwl-rate-' + safeId);
  if (rateEl) { rateEl.value = rate || ''; }
  calcQuickWorkTotal(safeId);
}

async function quickSaveWorkLog(empId, safeId) {
  const dateEl = document.getElementById('qwl-date-' + safeId);
  const selEl  = document.getElementById('qwl-item-' + safeId);
  const qtyEl  = document.getElementById('qwl-qty-'  + safeId);
  const rateEl = document.getElementById('qwl-rate-' + safeId);
  const noteEl = document.getElementById('qwl-note-' + safeId);

  const date = dateEl?.value || today();
  const selectedOpt = selEl?.options[selEl.selectedIndex];
  const workItemId  = selEl?.value || '';
  const itemLabel   = selectedOpt ? (selectedOpt.dataset.label || selectedOpt.text) : '';
  const quantity    = Number(qtyEl?.value  || 0);
  const unitPrice   = Number(rateEl?.value || 0);
  const note        = noteEl?.value.trim() || '';

  if (!workItemId) { swalAlert('No Item Selected', 'Please select a work item from the dropdown.', 'warning'); return; }
  if (!quantity || quantity <= 0) { swalAlert('Invalid Quantity', 'Enter a valid quantity > 0.', 'warning'); return; }
  if (!unitPrice || unitPrice <= 0) { swalAlert('No Rate', 'The selected item has no rate set. Check the Work Items Catalog.', 'warning'); return; }

  mutate(() => {
    state.workLogs.unshift({
      id: uid(),
      employeeId: empId,
      workItemId,
      date,
      itemLabel,
      quantity,
      unitPrice,
      amount: quantity * unitPrice,
      note
    });
  });

  if (selEl)  selEl.selectedIndex = 0;
  if (rateEl) rateEl.value = '';
  if (qtyEl)  qtyEl.value = '';
  if (noteEl) noteEl.value = '';
  const totEl = document.getElementById('qwl-total-' + safeId);
  if (totEl) totEl.textContent = 'Rs.0.00';

  Swal.fire({ toast: true, icon: 'success', title: `🔨 Work log saved! (${fmt(quantity * unitPrice)})`, timer: 2000, showConfirmButton: false, position: 'top-end' });
}

async function quickDeleteWorkLog(wlId) {
  const res = await Swal.fire({ title: 'Delete Work Log?', text: 'Remove this production entry?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#FF3B30', confirmButtonText: 'Delete' });
  if (res.isConfirmed) {
    mutate(() => { state.workLogs = state.workLogs.filter(w => w.id !== wlId); });
    Swal.fire({ toast: true, icon: 'success', title: 'Work log deleted!', timer: 1500, showConfirmButton: false });
  }
}

function getPaymentMethodLabel(pm) {
  if (pm === 'bank') return '🏦 Bank';
  if (pm === 'wallet') return '📱 Mobile Wallet';
  if (pm === 'card') return '💳 Card';
  return '💵 Cash';
}

function showReceiptModal(expenseId) {
  const exp = state.expenses.find(x => x.id === expenseId);
  if (!exp || !exp.receiptUrl) {
    swalAlert("No Receipt", "No receipt image attached to this expense.", "info");
    return;
  }
  Swal.fire({
    title: 'Receipt Attachment',
    html: `
      <div style="text-align:center;">
        <p><strong>${esc(exp.description)}</strong> — ${fmt(exp.amount)} (${esc(exp.date)})</p>
        <img src="${exp.receiptUrl}" style="max-width:100%;max-height:380px;border-radius:8px;border:1px solid #ccc;margin-bottom:12px;" alt="Receipt">
        <div>
          <a href="${exp.receiptUrl}" download="receipt-${exp.id}.png" class="swal2-confirm swal2-styled" style="text-decoration:none;display:inline-block;">Download Image</a>
        </div>
      </div>
    `,
    showConfirmButton: false,
    showCloseButton: true,
    background: '#FCFAF4',
    color: '#23302B'
  });
}

function sendWhatsAppReminder(saleId) {
  const sale = state.income.find(x => x.id === saleId);
  if (!sale) return;
  const customers = state.config.customers || [];
  const cust = customers.find(c => c.id === sale.customerId);
  let phone = cust?.phone || sale.customerPhone || "";
  let name = cust?.name || sale.customerName || "Valued Customer";
  
  if (!phone) {
    Swal.fire({
      title: '📱 WhatsApp Payment Reminder',
      html: `
        <div class="text-start">
          <p class="small text-muted mb-3">Sending invoice reminder for <strong>${esc(sale.item)}</strong> (Due: <strong>${fmt(getIncomeBalance(sale))}</strong>)</p>
          <div class="mb-2">
            <label class="form-label fw-bold small text-dark mb-1">Customer WhatsApp Number</label>
            <input id="swal-wa-phone" class="form-control" placeholder="e.g. 923001234567" autofocus>
            <div class="form-text small text-muted mt-1">Include country code without '+' (e.g. 923001234567).</div>
          </div>
        </div>
      `,
      showCancelButton: true,
      confirmButtonText: '📲 Send WhatsApp',
      confirmButtonColor: '#000000',
      cancelButtonColor: '#64748B',
      preConfirm: () => {
        const val = document.getElementById('swal-wa-phone').value.trim();
        if (!val) { Swal.showValidationMessage('Please enter a valid phone number'); return false; }
        return val.replace(/[^0-9]/g, '');
      }
    }).then(res => {
      if (res.value) {
        openWa(res.value, name, sale);
      }
    });
  } else {
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    openWa(cleanPhone, name, sale);
  }
}

function openWa(phone, name, sale) {
  const total = Number(sale.amount || 0);
  const paid = getIncomePaid(sale);
  const due = getIncomeBalance(sale);
  const msg = `Assalamu Alaikum ${name},\n\nThis is a payment reminder from *Velto Leather Shoes* regarding your order for "${sale.item}" (Qty: ${sale.quantity||1}).\n\nTotal Amount: ${state.config.currency}${total}\nPaid Amount: ${state.config.currency}${paid}\nRemaining Balance Due: ${state.config.currency}${due}\n\nThank you for choosing Velto Leather Shoes!`;
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;
  window.open(url, '_blank');
}

function getSalePayments(sale) {
  if (Array.isArray(sale.payments) && sale.payments.length > 0) {
    return sale.payments;
  }
  return [{
    id: sale.id + '-p0',
    date: sale.date,
    amount: Number(sale.amount || 0),
    receivedBy: sale.receivedBy,
    receivedByEmployeeId: sale.receivedByEmployeeId,
    note: sale.note || '',
    settled: sale.settled || false
  }];
}

function getIncomePaid(sale) {
  return getSalePayments(sale).reduce((sum, p) => sum + Number(p.amount || 0), 0);
}

function getIncomeBalance(sale) {
  return Math.max(0, Number(sale.amount || 0) - getIncomePaid(sale));
}

function totalRatio() { return state.config.partners.reduce((s, p) => s + Number(p.ratio || 0), 0); }
function totalIncome() { return state.income.reduce((s, x) => s + getIncomePaid(x), 0); }
function totalOutflow() {
  const exp = state.expenses.reduce((s, x) => s + Number(x.amount || 0), 0);
  const sal = state.salaryPayments.reduce((s, x) => s + Number(x.amount || 0), 0);
  const adv = state.advances.reduce((s, x) => s + Number(x.amount || 0), 0);
  return exp + sal + adv;
}
function netProfit() { return totalIncome() - totalOutflow(); }

function partnerStats() {
  const tr = totalRatio();
  const np = netProfit();
  return state.config.partners.map(p => {
    const opCap = Number(p.openingCapital || 0);
    const expPaid = state.expenses.filter(x => x.paidBy === p.id).reduce((s, x) => s + Number(x.amount || 0), 0);
    const salPaid = state.salaryPayments.filter(x => x.paidBy === p.id).reduce((s, x) => s + Number(x.amount || 0) + Number(x.reimbursedAmount || 0), 0);
    const advPaid = state.advances.filter(x => x.paidBy === p.id).reduce((s, x) => s + Number(x.amount || 0), 0);
    const vendPaid = state.vendorPayments.filter(x => x.paidBy === p.id).reduce((s, x) => s + Number(x.amount || 0), 0);
    const paid = opCap + expPaid + salPaid + advPaid + vendPaid;
    const received = state.income.reduce((s, sale) => {
      const pms = getSalePayments(sale);
      return s + pms.filter(x => x.receivedBy === p.id).reduce((sum, x) => sum + Number(x.amount || 0), 0);
    }, 0);
    const fairShare = tr > 0 ? (np * Number(p.ratio || 0)) / tr : 0;
    const balance = paid - received + fairShare;
    return { ...p, paid, received, fairShare, balance, opCap };
  });
}
function fmt(n) { return `${state.config.currency}${Number(n || 0).toFixed(2)}`; }
function empWorkLogs(id) { return state.workLogs.filter(w => w.employeeId === id); }
function empSalaryPayments(id) { return state.salaryPayments.filter(s => s.employeeId === id); }
function empAdvances(id) { return state.advances.filter(a => a.employeeId === id); }
function empOutstandingAdvance(id) { return empAdvances(id).filter(a => !a.settled).reduce((s, a) => s + a.amount, 0); }
function empCoveredExpenses(id) { return state.expenses.filter(x => x.payerEmployeeId === id); }
function empOutstandingReimbursement(id) { return empCoveredExpenses(id).filter(x => !x.settled).reduce((s, x) => s + Number(x.amount || 0), 0); }
function empHeldIncome(id) {
  const list = [];
  state.income.forEach(sale => {
    const pms = getSalePayments(sale);
    pms.forEach(p => {
      if (p.receivedByEmployeeId === id) {
        list.push({
          id: p.id,
          saleId: sale.id,
          item: sale.item,
          date: p.date,
          amount: Number(p.amount || 0),
          settled: p.settled || false
        });
      }
    });
  });
  return list;
}
function empOutstandingHeldIncome(id) { return empHeldIncome(id).filter(x => !x.settled).reduce((s, x) => s + Number(x.amount || 0), 0); }
function empAttendanceLogs(id) { return (state.attendanceLogs || []).filter(a => a.employeeId === id); }
function empUnsettledAbsences(id) { return empAttendanceLogs(id).filter(a => !a.settled); }
function empAbsenceDeduction(emp) {
  const unsettled = empUnsettledAbsences(emp.id);
  const defaultWD = emp.type === 'monthly' ? 26 : 6;
  const wDays = Number(emp.workingDays || defaultWD);
  const baseRate = emp.type === 'monthly' ? Number(emp.monthlyRate || 0) : Number(emp.weeklyRate || 0);
  const dailyRate = baseRate / (wDays > 0 ? wDays : defaultWD);
  return unsettled.reduce((sum, a) => {
    if (a.deductSalary === false) return sum;
    const mult = a.status === 'halfday' ? 0.5 : 1;
    return sum + (dailyRate * mult);
  }, 0);
}
function vendorPurchases(id) { return state.expenses.filter(x => x.vendorId === id); }
function vendorPaymentsFor(id) { return state.vendorPayments.filter(x => x.vendorId === id); }
function vendorCreditTotal(id) { return vendorPurchases(id).filter(x => x.onCredit).reduce((s, x) => s + Number(x.amount || 0), 0); }
function vendorOutstanding(id) { return vendorCreditTotal(id) - vendorPaymentsFor(id).reduce((s, x) => s + Number(x.amount || 0), 0); }
function totalVendorOutstanding() { return state.config.vendors.reduce((s, v) => s + vendorOutstanding(v.id), 0); }

function renderHeader() {
  return `
    <div class="mobile-header">
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-dark btn-sm text-white border px-3 fw-bold" data-act="toggle-mobile-nav">☰ Menu</button>
        <span class="fw-bold fs-5 text-white">Velto LS</span>
      </div>
      <button class="btn btn-sm btn-light fw-bold" data-act="quick-add-modal">⚡ Quick</button>
    </div>
    <header class="app-topbar">
      <div>
        <h2 class="topbar-title">${getTabTitle(state.tab)}</h2>
        <p class="topbar-sub">${getTabSubtitle(state.tab)}</p>
      </div>
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="quick-act-btn" data-act="quick-add-modal">⚡ Quick Add</button>
      </div>
    </header>
    ${state.saveErr ? `<div class="alert alert-danger py-2 px-3 mb-3 small" style="border-radius:10px;">⚠️ Couldn't connect to MySQL database just now. Check database configuration.</div>` : ""}
  `;
}

function getTabTitle(tab) {
  if (tab === "overview") return "Dashboard Overview";
  if (tab === "income") return "Sales & Revenue Ledger";
  if (tab === "expenses") return "Business Expense Tracker";
  if (tab === "employees") return "Workers & Payroll Management";
  if (tab === "vendors") return "Vendor Purchases & Accounts";
  if (tab === "customers") return "Customer Directory & Ledger";
  if (tab === "reports") return "Financial Analytics & Reports";
  if (tab === "settings") return "System Settings & Configuration";
  return "Velto LS";
}

function getTabSubtitle(tab) {
  if (tab === "overview") return "Real-time summary of profit split, income, expenses, and pending balances.";
  if (tab === "income") return "Log sales, track advance payments, and collect due pickup balances.";
  if (tab === "expenses") return "Track operational expenses, budget limits, payment methods, and receipt bills.";
  if (tab === "employees") return "Manage weekly salaries, piece rates, advances, and daily attendance logs.";
  if (tab === "vendors") return "Monitor credit material purchases and record vendor repayments.";
  if (tab === "customers") return "Maintain customer records, order totals, and send 1-click WhatsApp reminders.";
  if (tab === "reports") return "Generate printable financial summaries, revenue breakdowns, and payroll reports.";
  if (tab === "settings") return "Configure partners, profit split ratio, starting capital, and passcode.";
  return "";
}

function renderTabs() {
  const items = [
    ["overview", "🏠 Dashboard"],
    ["income", "💵 Income"],
    ["expenses", "💸 Expenses"],
    ["employees", "👥 Employees"],
    ["vendors", "📦 Vendors"],
    ["customers", "👤 Customers"],
    ["reports", "📊 Reports"],
    ["settings", "⚙️ Settings"]
  ];
  return `
    <div class="sidebar-backdrop ${state.mobileNav ? 'show' : ''}" data-act="close-mobile-nav"></div>
    <aside class="app-sidebar ${state.mobileNav ? 'show' : ''}">
      <div class="sidebar-brand d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <img src="favicon.jpg" style="width:36px;height:36px;border-radius:50%;object-fit:cover;" alt="Logo">
          <div>
            <div class="brand-title">Velto LS</div>
            <div class="brand-sub">Velto LS Suite</div>
          </div>
        </div>
        <button class="btn-close btn-close-white d-lg-none" data-act="close-mobile-nav" aria-label="Close"></button>
      </div>
      <nav class="sidebar-nav">
        ${items.map(([id, label]) => `
          <button class="sidebar-item ${state.tab===id?"active":""}" data-act="switch-tab" data-tab="${id}">
            ${label}
          </button>
        `).join("")}
      </nav>
      <div class="sidebar-footer">
        <button class="logout-btn" data-act="logout">🔒 Lock / Exit</button>
      </div>
    </aside>
  `;
}

function renderCategoryBudgets() {
  const budgets = state.config.budgets || {};
  const currentMonthPrefix = today().slice(0, 7);
  const thisMonthExpenses = state.expenses.filter(e => e.date && e.date.startsWith(currentMonthPrefix));

  const categoryTotals = {};
  thisMonthExpenses.forEach(e => {
    const cat = e.category || "Raw Materials";
    categoryTotals[cat] = (categoryTotals[cat] || 0) + Number(e.amount || 0);
  });

  const entries = Object.entries(budgets);
  if (entries.length === 0) return "";

  const cards = entries.map(([cat, limit]) => {
    const spent = categoryTotals[cat] || 0;
    const pct = Math.min(100, limit > 0 ? Math.round((spent / limit) * 100) : 0);
    const color = pct >= 95 ? "var(--rust)" : (pct >= 75 ? "var(--gold)" : "var(--teal)");
    return `
      <div style="background:#fff;border:1px solid var(--rule);border-radius:10px;padding:10px 14px;flex:1;min-width:160px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
          <span class="strong small">${esc(cat)}</span>
          <span class="mono small" style="color:${color};font-weight:700;">${pct}%</span>
        </div>
        <div style="background:#E2E8F0;border-radius:10px;height:6px;overflow:hidden;margin-bottom:6px;">
          <div style="width:${pct}%;background:${color};height:100%;border-radius:10px;transition:width 0.3s;"></div>
        </div>
        <div class="muted small" style="font-size:11px;">Spent: <span class="mono strong">${fmt(spent)}</span> / ${fmt(limit)}</div>
      </div>
    `;
  }).join("");

  return `<div style="margin-bottom:14px;"><div class="serif small-title" style="margin-bottom:6px;">🎯 Monthly Category Budgets</div><div style="display:flex;gap:10px;flex-wrap:wrap;">${cards}</div></div>`;
}

function renderCustomers() {
  const allCustomers = state.config.customers || [];
  const sales = state.income;
  const q = (state.custSearch || "").toLowerCase().trim();

  const customers = allCustomers.filter(c => {
    if (!q) return true;
    return (c.name || "").toLowerCase().includes(q) || (c.phone || "").includes(q) || (c.address || "").toLowerCase().includes(q);
  });

  const rows = customers.map(c => {
    const custSales = state.income.filter(s => s.customerId === c.id || (c.phone && s.customerPhone === c.phone) || (s.customerName && s.customerName.toLowerCase() === c.name.toLowerCase()));
    let totalOrd = 0, totalPaid = 0;
    custSales.forEach(s => {
      const tot = Number(s.amount || 0);
      const pd = getIncomePaid(s);
      totalOrd += tot;
      totalPaid += pd;
    });
    const pendingDue = Math.max(0, totalOrd - totalPaid);

    return `
      <div class="panel emp-card mb-2" style="padding:14px 18px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="serif strong" style="font-size:16px;">${esc(c.name)}</span>
            ${c.phone ? `<span class="muted small">📞 ${esc(c.phone)}</span>` : ""}
            ${c.address ? `<span class="badge bg-light text-dark border">📍 ${esc(c.address)}</span>` : ""}
            <span class="badge bg-light text-dark border">— ${custSales.length} order${custSales.length !== 1 ? 's' : ''} (${fmt(totalOrd)})</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            ${pendingDue > 0.005 ? `<span class="mono small strong text-danger">Due ${fmt(pendingDue)}</span>` : `<span class="mono small strong text-success">settled</span>`}
            
            <a href="customer_detail.php?id=${c.id}" class="btn btn-sm btn-dark fw-bold px-3 py-1" style="font-size:12px;" title="View Customer Sales & Receivables">👁️ View Details</a>
            ${c.phone ? `<button class="btn btn-sm btn-success fw-bold px-2 py-1" style="font-size:12px;" data-act="wa-direct" data-phone="${esc(c.phone)}" data-name="${esc(c.name)}" data-due="${pendingDue}" title="Send WhatsApp Statement">📱 WA</button>` : ''}
            <button class="icon-btn" data-act="edit-customer" data-id="${c.id}" title="Edit Customer">✏️</button>
            <button class="icon-btn" data-act="delete-customer" data-id="${c.id}" style="color:var(--rust);" title="Delete Customer">🗑️</button>
          </div>
        </div>
      </div>
    `;
  }).join("");

  return `
    <div class="panel">
      <div class="serif strong" style="margin-bottom:10px;">Add Customer Profile</div>
      <div class="row g-2 align-items-center">
        <div class="col-md-4 col-12"><input class="form-control" id="cust-name" placeholder="Customer Name"></div>
        <div class="col-md-3 col-12"><input class="form-control" id="cust-phone" placeholder="WhatsApp / Phone (e.g. 923001234567)"></div>
        <div class="col-md-3 col-12"><input class="form-control" id="cust-address" placeholder="City / Address (optional)"></div>
        <div class="col-md-2 col-12"><button class="btn btn-dark fw-bold w-100" data-act="add-customer">+ Add Customer</button></div>
      </div>
    </div>
    <div class="panel table-panel">
      <div class="d-flex justify-content-between align-items-center p-3 border-bottom flex-wrap gap-2">
        <div class="serif strong" style="font-size:16px;">Customer Database &amp; Ledger (${allCustomers.length})</div>
        <div style="max-width:320px;width:100%;">
          <input class="form-control form-control-sm" id="cust-search" placeholder="🔍 Search by customer name or phone..." value="${esc(state.custSearch || '')}" data-act="search-customer">
        </div>
      </div>
      <div class="p-3">
        ${allCustomers.length === 0 ? `<div class="empty-msg">No customers created yet. Add one above or select when logging sales.</div>` : (rows.length === 0 ? `<div class="empty-msg">No customers match your search query.</div>` : rows)}
      </div>
    </div>
  `;
}

function renderOverview() {
  const stats = partnerStats();
  const tr = totalRatio();
  const inc = totalIncome();
  const out = totalOutflow();
  const np = netProfit();
  let settlementHtml = "";
  if (stats.length >= 2) {
    const sorted = [...stats].sort((a, b) => b.balance - a.balance);
    const creditor = sorted[0], debtor = sorted[sorted.length - 1];
    const amount = Math.abs(creditor.balance);
    if (amount > 0.005) {
      settlementHtml = `<div class="settle-box">💵 <div><strong>${esc(debtor.name)}</strong> owes <strong>${esc(creditor.name)}</strong> <span class="mono strong">${fmt(amount)}</span> to even out the ${state.config.partners.map(p=>p.ratio).join(":")} split.</div></div>`;
    } else {
      settlementHtml = `<div class="panel settled">Everything's settled — both partners are even on the ${state.config.partners.map(p=>p.ratio).join(":")} split.</div>`;
    }
  }
  const totalPendingDue = state.income.reduce((s, x) => s + getIncomeBalance(x), 0);

  const cards = stats.map(p => `
    <div class="panel partner-card">
      <div class="pc-top">
        <span class="strong" style="font-size:16px;">👤 ${esc(p.name)}</span>
        <span class="mono muted small" style="background:#F1F5F9;padding:2px 8px;border-radius:6px;">Ratio ${p.ratio} (${Math.round((p.ratio/tr)*100)}%)</span>
      </div>
      ${p.opCap > 0 ? `<div class="muted small" style="margin-top:6px">Starting capital: <span class="mono strong gold">${fmt(p.opCap)}</span></div>` : ''}
      <div class="stat-mini-grid" style="margin-top:12px;display:grid;grid-template-columns:1fr 1fr;gap:8px;">
        <div style="background:#ECFDF5;padding:8px 10px;border-radius:8px;border:1px solid #A7F3D0;">
          <div class="muted small" style="font-size:11px;color:#047857;">Income Received</div>
          <div class="mono strong" style="font-size:15px;color:#059669;">+${fmt(p.received)}</div>
        </div>
        <div style="background:#FFF1F2;padding:8px 10px;border-radius:8px;border:1px solid #FECDD3;">
          <div class="muted small" style="font-size:11px;color:#BE123C;">Expenses Paid</div>
          <div class="mono strong" style="font-size:15px;color:#E11D48;">-${fmt(p.paid - p.opCap)}</div>
        </div>
      </div>
      <div style="margin-top:12px;padding-top:10px;border-top:1px solid var(--rule);display:flex;justify-content:space-between;align-items:center;">
        <div>
          <div class="muted small" style="font-size:11px;">Fair Profit Share</div>
          <div class="mono strong">${fmt(p.fairShare)}</div>
        </div>
        <div style="text-align:right;">
          <div class="small strong" style="padding:4px 10px;border-radius:6px;font-size:12px;background:${p.balance>=0?"#D1FAE5":"#FFE4E6"};color:${p.balance>=0?"#065F46":"#991B1B"};">
            ${p.balance>=0? `Owed ${fmt(p.balance)}` : `Owes ${fmt(Math.abs(p.balance))}`}
          </div>
        </div>
      </div>
    </div>`).join("");

  const scaleSegs = state.config.partners.map((p,i) => `<div class="scale-seg" style="width:${tr>0?(Number(p.ratio||0)/tr*100):0}%;background:${i===0?"#10B981":"#EF4444"}">${esc(p.name)} (${p.ratio})</div>`).join("");

  return `
    <div class="kpi-grid">
      <div class="kpi-card kpi-income">
        <div class="kpi-icon">📈</div>
        <div>
          <div class="kpi-label">TOTAL INCOME</div>
          <div class="kpi-value">${fmt(inc)}</div>
          <div class="kpi-sub">Collected from sales</div>
        </div>
      </div>
      <div class="kpi-card kpi-expense">
        <div class="kpi-icon">📉</div>
        <div>
          <div class="kpi-label">TOTAL OUTFLOWS</div>
          <div class="kpi-value">${fmt(out)}</div>
          <div class="kpi-sub">Expenses + Payroll</div>
        </div>
      </div>
      <div class="kpi-card kpi-profit">
        <div class="kpi-icon">💰</div>
        <div>
          <div class="kpi-label">NET PROFIT</div>
          <div class="kpi-value">${fmt(np)}</div>
          <div class="kpi-sub">Business net gain</div>
        </div>
      </div>
      <div class="kpi-card kpi-due">
        <div class="kpi-icon">⏳</div>
        <div>
          <div class="kpi-label">PENDING DUES</div>
          <div class="kpi-value">${fmt(totalPendingDue)}</div>
          <div class="kpi-sub">Customer receivables</div>
        </div>
      </div>
    </div>

    <div style="margin-bottom:16px;">
      <div class="serif strong" style="font-size:16px;margin-bottom:8px;">👥 Partner Profit &amp; Expense Shares</div>
      <div class="grid2">${cards}</div>
    </div>

    <div class="panel">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
        <span class="strong">⚖️ Partner Ratio Split Scale</span>
        <span class="mono muted small">Ratio Split ${state.config.partners.map(p=>p.ratio).join(":")}</span>
      </div>
      <div class="scale-bar">${scaleSegs}</div>
    </div>

    ${totalVendorOutstanding() > 0.005 ? `
      <div class="panel" style="display:flex;justify-content:space-between;align-items:center;background:#FFFBEB;border:1px solid #FCD34D;">
        <span class="muted small" style="color:#92400E;font-weight:600;">📦 Owed to Vendors (Credit Material Purchases)</span>
        <span class="mono strong" style="color:#B45309;font-size:16px;">${fmt(totalVendorOutstanding())}</span>
      </div>
    ` : ""}
    ${settlementHtml}
  `;
}

function renderPagination(currentPage, totalItems, pageSize, actName) {
  const totalPages = Math.ceil(totalItems / pageSize);
  if (totalPages <= 1) return "";
  let btns = "";
  for (let i = 1; i <= totalPages; i++) {
    btns += `<li class="page-item ${i === currentPage ? 'active' : ''}"><button class="page-link" data-act="${actName}" data-page="${i}">${i}</button></li>`;
  }
  const start = (currentPage - 1) * pageSize + 1;
  const end = Math.min(currentPage * pageSize, totalItems);
  return `
    <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top bg-light gap-2">
      <span class="small text-muted">Showing ${start}–${end} of ${totalItems} entries</span>
      <ul class="pagination pagination-sm mb-0">
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
          <button class="page-link" data-act="${actName}" data-page="${currentPage - 1}">« Prev</button>
        </li>
        ${btns}
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
          <button class="page-link" data-act="${actName}" data-page="${currentPage + 1}">Next »</button>
        </li>
      </ul>
    </div>
  `;
}

async function showAddSaleModal() {
  const partners = state.config.partners;
  const employees = state.config.employees;
  const receiverOptions = `
    <optgroup label="Partner">${partners.map(p=>`<option value="partner:${p.id}">${esc(p.name)}</option>`).join("")}</optgroup>
    ${employees.length ? `<optgroup label="Worker (owes it back)">${employees.map(e=>`<option value="employee:${e.id}">${esc(e.name)}</option>`).join("")}</optgroup>` : ""}
  `;
  const customerOptions = `<option value="">-- Select Existing Customer (Optional) --</option><option value="__new__">➕ + Create New Customer Profile...</option>${(state.config.customers||[]).map(c=>`<option value="${c.id}">${esc(c.name)}${c.phone?` (${esc(c.phone)})`:''}${c.address?` — ${esc(c.address)}`:''}</option>`).join("")}`;

  const { value: formValues } = await Swal.fire({
    title: '💵 Log New Sale',
    html: `
      <div class="text-start d-flex flex-column gap-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Date</label>
            <input id="swal-inc-date" class="form-control" type="date" value="${today()}">
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Quantity</label>
            <input id="swal-inc-qty" class="form-control" type="number" placeholder="Qty (e.g. 1)">
          </div>
        </div>
        <div>
          <label class="form-label fw-bold small text-dark mb-1">Item Description *</label>
          <input id="swal-inc-item" class="form-control" placeholder="Item name (e.g. Leather shoes)">
        </div>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Total Price *</label>
            <input id="swal-inc-amount" class="form-control" type="number" placeholder="Total amount">
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Advance / Paid</label>
            <input id="swal-inc-paid-amount" class="form-control" type="number" placeholder="Paid amount">
          </div>
        </div>
        <div>
          <label class="form-label fw-bold small text-dark mb-1">Payment Received By</label>
          <select id="swal-inc-receivedby" class="form-select">${receiverOptions}</select>
        </div>
        <div>
          <label class="form-label fw-bold small text-dark mb-1">Customer Account</label>
          <select id="swal-inc-customer" class="form-select" onchange="const f = document.getElementById('swal-new-cust-fields'); if(f) f.style.display = this.value==='__new__' ? 'block' : 'none';">
            ${customerOptions}
          </select>
        </div>
        <div id="swal-new-cust-fields" style="display:none;" class="p-2 border rounded bg-light">
          <div class="fw-bold small mb-1 text-dark">➕ Create New Customer Profile</div>
          <div class="row g-2">
            <div class="col-6"><input id="swal-new-cust-name" class="form-control form-control-sm" placeholder="Customer Name *"></div>
            <div class="col-6"><input id="swal-new-cust-phone" class="form-control form-control-sm" placeholder="WhatsApp / Phone"></div>
          </div>
        </div>
        <div>
          <label class="form-label fw-bold small text-dark mb-1">Order Note / Special Info</label>
          <input id="swal-inc-note" class="form-control" placeholder="Optional notes...">
        </div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: '+ Log Sale',
    confirmButtonColor: '#000000',
    cancelButtonColor: '#64748B',
    preConfirm: () => {
      const date = document.getElementById('swal-inc-date').value || today();
      const item = document.getElementById('swal-inc-item').value.trim();
      const quantity = document.getElementById('swal-inc-qty').value ? Number(document.getElementById('swal-inc-qty').value) : null;
      const totalAmount = Number(document.getElementById('swal-inc-amount').value);
      const paidInput = document.getElementById('swal-inc-paid-amount').value;
      const paidAmount = paidInput !== "" ? Number(paidInput) : totalAmount;
      const receiverVal = document.getElementById('swal-inc-receivedby').value;
      const custSel = document.getElementById('swal-inc-customer').value;
      const note = document.getElementById('swal-inc-note').value.trim();
      const newName = document.getElementById('swal-new-cust-name') ? document.getElementById('swal-new-cust-name').value.trim() : "";
      const newPhone = document.getElementById('swal-new-cust-phone') ? document.getElementById('swal-new-cust-phone').value.trim() : "";

      if (!item) {
        Swal.showValidationMessage('Please enter item description.');
        return false;
      }
      if (!totalAmount || isNaN(totalAmount) || totalAmount <= 0) {
        Swal.showValidationMessage('Total Sale Price must be greater than zero.');
        return false;
      }
      if (paidAmount < 0) {
        Swal.showValidationMessage('Paid Amount cannot be negative.');
        return false;
      }
      if (paidAmount > totalAmount) {
        Swal.showValidationMessage(`Paid Amount (${state.config.currency}${paidAmount}) cannot exceed Total Sale Price (${state.config.currency}${totalAmount}).`);
        return false;
      }

      return { date, item, quantity, totalAmount, paidAmount, receiverVal, custSel, note, newName, newPhone };
    }
  });

  if (formValues) {
    const [receiverType, receiverId] = formValues.receiverVal.split(':');
    let customerId = formValues.custSel;
    state.config.customers = state.config.customers || [];
    if (formValues.custSel === "__new__") {
      if (formValues.newName) {
        const existing = state.config.customers.find(c => c.name.toLowerCase() === formValues.newName.toLowerCase() || (formValues.newPhone && c.phone === formValues.newPhone));
        if (existing) {
          customerId = existing.id;
        } else {
          const newCust = { id: uid(), name: formValues.newName, phone: formValues.newPhone };
          state.config.customers.unshift(newCust);
          customerId = newCust.id;
        }
      } else {
        customerId = "";
      }
    }

    const entry = {
      id: uid(),
      date: formValues.date,
      item: formValues.item,
      quantity: formValues.quantity,
      amount: formValues.totalAmount,
      customerId,
      note: formValues.note,
      payments: [
        {
          id: uid(),
          date: formValues.date,
          amount: formValues.paidAmount,
          receivedBy: receiverType === "partner" ? receiverId : undefined,
          receivedByEmployeeId: receiverType === "employee" ? receiverId : undefined,
          settled: false,
          note: formValues.paidAmount < formValues.totalAmount ? "Advance payment" : "Full payment"
        }
      ]
    };
    mutate(() => state.income.unshift(entry));
    toast(formValues.paidAmount < formValues.totalAmount ? "💵 Advance sale logged!" : "🎉 Sale logged successfully!");
  }
}

function renderIncome() {
  const partners = state.config.partners;
  const employees = state.config.employees;
  const q = (state.incomeSearch || "").toLowerCase().trim();
  const filtered = state.income.filter(e => {
    if (!q) return true;
    const pms = getSalePayments(e);
    const receivers = pms.map(p => p.receivedByEmployeeId ? (employees.find(x => x.id === p.receivedByEmployeeId)?.name || "") : (partners.find(x => x.id === p.receivedBy)?.name || "")).join(" ");
    return (e.item||"").toLowerCase().includes(q) || (e.note||"").toLowerCase().includes(q) || (e.date||"").includes(q) || receivers.toLowerCase().includes(q) || String(e.amount||"").includes(q);
  });
  
  const pageSize = 10;
  const totalItems = filtered.length;
  const totalPages = Math.ceil(totalItems / pageSize) || 1;
  state.incomePage = Math.min(state.incomePage || 1, totalPages);
  const paged = filtered.slice((state.incomePage - 1) * pageSize, state.incomePage * pageSize);

  const rows = paged.map(e => {
    const totalAmt = Number(e.amount || 0);
    const paidAmt = getIncomePaid(e);
    const balance = getIncomeBalance(e);
    const payments = getSalePayments(e);

    let statusTag = "";
    if (balance <= 0) {
      statusTag = `<span class="badge bg-success">Paid</span>`;
    } else if (paidAmt > 0) {
      statusTag = `<span class="badge bg-warning text-dark" title="Paid ${fmt(paidAmt)} / Total ${fmt(totalAmt)}">Partial (${fmt(balance)} due)</span>`;
    } else {
      statusTag = `<span class="badge bg-danger">Unpaid (${fmt(balance)} due)</span>`;
    }

    const receiversList = payments.map(p => {
      if (p.receivedByEmployeeId) {
        const emp = employees.find(x => x.id === p.receivedByEmployeeId);
        return `${esc(emp?.name || "Worker")}${p.settled ? '' : ' (holding)'}`;
      } else {
        const prt = partners.find(x => x.id === p.receivedBy);
        return esc(prt?.name || "Partner");
      }
    });
    const uniqueReceivers = Array.from(new Set(receiversList)).join(", ") || "—";

    return `<div class="row-line income-row">
      <div class="mono small">${esc(e.date)}</div>
      <div>${esc(e.item)}${e.note ? ` <span class="muted small">— ${esc(e.note)}</span>` : ""}</div>
      <div class="mono small">${e.quantity||"—"}</div>
      <div class="mono strong">${fmt(totalAmt)}</div>
      <div class="mono ${paidAmt < totalAmt ? 'text-warning' : 'text-success'}">${fmt(paidAmt)}</div>
      <div class="small">${statusTag}</div>
      <div class="small">${uniqueReceivers}</div>
      <div style="display:flex;align-items:center;gap:4px;justify-content:flex-end">
        <button class="btn btn-sm btn-outline-dark fw-semibold" style="padding:2px 6px;font-size:11.5px;" data-act="wa-sale" data-id="${e.id}" title="Send WhatsApp Payment Reminder">📲 WA</button>
        ${balance > 0 ? `<button class="btn btn-sm btn-outline-success fw-semibold" style="padding:2px 6px;font-size:11.5px;" data-act="pay-income-balance" data-id="${e.id}" title="Collect Remaining Payment">💵 Pay</button>` : ''}
        <button class="icon-btn" data-act="edit-income" data-id="${e.id}" title="Edit Sale">✏️</button>
        <button class="icon-btn" data-act="delete-income" data-id="${e.id}" title="Delete Sale" style="color:var(--rust)">🗑️</button>
      </div>
    </div>`;
  }).join("");

  return `
    <div class="panel d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <div class="serif strong" style="font-size:16px;">Sales &amp; Revenue Transactions</div>
        <div class="muted small">Track all business sales, customer advance deposits, and due pickups.</div>
      </div>
      <button class="btn btn-dark fw-bold px-4 py-2" data-act="open-add-sale-modal">+ Log New Sale</button>
    </div>
    <div class="panel table-panel">
      <div style="padding:8px 14px;border-bottom:1px solid var(--rule);background:#fff">
        <input class="form-control" id="inc-search" placeholder="🔍 Search sales by item, customer, date..." value="${esc(state.incomeSearch)}" data-act="search-income">
      </div>
      <div class="mono table-head income-row"><div>DATE</div><div>ITEM</div><div>QTY</div><div>TOTAL</div><div>PAID</div><div>STATUS</div><div>RECEIVED</div><div></div></div>
      ${state.income.length===0 ? `<div class="empty-msg">No sales logged yet. Click "+ Log New Sale" above to record a transaction.</div>` : (filtered.length===0 ? `<div class="empty-msg">No sales match your search.</div>` : rows)}
      ${renderPagination(state.incomePage, totalItems, pageSize, 'set-income-page')}
    </div>
  `;
}

async function showAddExpenseModal() {
  const partners = state.config.partners;
  const employees = state.config.employees;
  const categories = ["Raw Materials", "Factory Rent", "Shop Rent", "Utilities / Bills", "Packaging", "Worker Food & Tea", "Machinery & Repairs", "Transport", "Miscellaneous"];
  const optionsPayer = `
    <optgroup label="Partner">${partners.map(p=>`<option value="partner:${p.id}">${esc(p.name)}</option>`).join("")}</optgroup>
    ${employees.length ? `<optgroup label="Employee (reimbursable)">${employees.map(emp=>`<option value="employee:${emp.id}">${esc(emp.name)}</option>`).join("")}</optgroup>` : ""}
  `;
  const optionsCat = categories.map(c => `<option value="${esc(c)}">${esc(c)}</option>`).join("");

  const { value: formValues } = await Swal.fire({
    title: '💸 Add Business Expense',
    html: `
      <div class="text-start d-flex flex-column gap-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Date</label>
            <input id="swal-exp-date" class="form-control" type="date" value="${today()}">
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Amount *</label>
            <input id="swal-exp-amount" class="form-control" type="number" placeholder="Amount">
          </div>
        </div>
        <div>
          <label class="form-label fw-bold small text-dark mb-1">Description *</label>
          <input id="swal-exp-desc" class="form-control" placeholder="Description (e.g. Leather soles)">
        </div>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Category</label>
            <select id="swal-exp-category" class="form-select">${optionsCat}</select>
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small text-dark mb-1">Payment Method</label>
            <select id="swal-exp-pm" class="form-select">
              <option value="Cash">Cash</option>
              <option value="Bank / Online">Bank / Online</option>
              <option value="Vendor Credit">Vendor Credit</option>
            </select>
          </div>
        </div>
        <div>
          <label class="form-label fw-bold small text-dark mb-1">Paid By</label>
          <select id="swal-exp-payer" class="form-select">${optionsPayer}</select>
        </div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: '+ Log Expense',
    confirmButtonColor: '#000000',
    cancelButtonColor: '#64748B',
    preConfirm: () => {
      const date = document.getElementById('swal-exp-date').value || today();
      const description = document.getElementById('swal-exp-desc').value.trim();
      const amount = Number(document.getElementById('swal-exp-amount').value);
      const category = document.getElementById('swal-exp-category').value;
      const paymentMethod = document.getElementById('swal-exp-pm').value;
      const payerVal = document.getElementById('swal-exp-payer').value;

      if (!description) {
        Swal.showValidationMessage('Please enter expense description.');
        return false;
      }
      if (!amount || isNaN(amount) || amount <= 0) {
        Swal.showValidationMessage('Expense amount must be greater than zero.');
        return false;
      }

      return { date, description, amount, category, paymentMethod, payerVal };
    }
  });

  if (formValues) {
    const [payerType, payerId] = formValues.payerVal.split(':');
    const entry = {
      id: uid(),
      date: formValues.date,
      description: formValues.description,
      amount: formValues.amount,
      category: formValues.category,
      paymentMethod: formValues.paymentMethod,
      receiptUrl: null
    };
    if (payerType === "employee") { entry.payerEmployeeId = payerId; entry.settled = false; }
    else { entry.paidBy = payerId; }
    mutate(() => state.expenses.unshift(entry));
    toast("💸 Expense logged successfully!");
  }
}

function renderExpenses() {
  const partners = state.config.partners;
  const employees = state.config.employees;
  const q = (state.expenseSearch || "").toLowerCase().trim();
  const filtered = state.expenses.filter(e => {
    if (!q) return true;
    const payer = e.payerEmployeeId ? (employees.find(x => x.id === e.payerEmployeeId)?.name || "") : (partners.find(p => p.id === e.paidBy)?.name || "");
    const cat = e.category || "";
    return (e.description||"").toLowerCase().includes(q) || cat.toLowerCase().includes(q) || (e.date||"").includes(q) || payer.toLowerCase().includes(q) || String(e.amount||"").includes(q);
  });

  const pageSize = 10;
  const totalItems = filtered.length;
  const totalPages = Math.ceil(totalItems / pageSize) || 1;
  state.expensePage = Math.min(state.expensePage || 1, totalPages);
  const paged = filtered.slice((state.expensePage - 1) * pageSize, state.expensePage * pageSize);

  const rows = paged.map(e => {
    let payerLabel, tag = "";
    if (e.payerEmployeeId) {
      const emp = employees.find(x => x.id === e.payerEmployeeId);
      payerLabel = `${esc(emp?.name || "—")}`;
      tag = e.settled ? `<span class="badge bg-success">reimbursed</span>` : `<span class="badge bg-warning text-dark">awaiting reimb.</span>`;
    } else if (e.vendorId && e.onCredit) {
      const vend = state.config.vendors.find(v => v.id === e.vendorId);
      payerLabel = `${esc(vend?.name || "—")}`;
      tag = `<span class="badge bg-warning text-dark">on credit</span>`;
    } else {
      payerLabel = esc(partners.find(p=>p.id===e.paidBy)?.name || "—");
      if (e.vendorId) { const vend = state.config.vendors.find(v => v.id === e.vendorId); tag = vend ? `<span class="badge bg-secondary">${esc(vend.name)}</span>` : ""; }
    }
    const categoryTag = `<span class="badge bg-light text-dark border">${esc(e.category || 'Raw Materials')}</span>`;
    const pmLabel = getPaymentMethodLabel(e.paymentMethod || 'cash');

    return `<div class="row-line expense-row">
      <div class="mono small">${esc(e.date)}</div>
      <div>${esc(e.description)}</div>
      <div>${categoryTag}</div>
      <div class="mono strong">${fmt(e.amount)}</div>
      <div class="small mono">${pmLabel}</div>
      <div class="small">${payerLabel} ${tag}</div>
      <div style="display:flex;align-items:center;gap:4px;justify-content:flex-end">
        ${e.receiptUrl ? `<button class="icon-btn" data-act="view-receipt" data-id="${e.id}" title="View Attached Receipt Bill">🧾</button>` : ''}
        <button class="icon-btn" data-act="edit-expense" data-id="${e.id}" title="Edit Expense">✏️</button>
        <button class="icon-btn" data-act="delete-expense" data-id="${e.id}" title="Delete Expense" style="color:var(--rust)">🗑️</button>
      </div>
    </div>`;
  }).join("");

  return `
    ${renderCategoryBudgets()}
    <div class="panel d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <div class="serif strong" style="font-size:16px;">Business Expense Tracker</div>
        <div class="muted small">Monitor operational costs, vendor receipts, and budget allocations.</div>
      </div>
      <button class="btn btn-dark fw-bold px-4 py-2" data-act="open-add-expense-modal">+ Log New Expense</button>
    </div>
    <div class="panel table-panel">
      <div style="padding:8px 14px;border-bottom:1px solid var(--rule);background:#fff">
        <input class="form-control" id="exp-search" placeholder="🔍 Search expenses by description, category, payer, amount..." value="${esc(state.expenseSearch)}" data-act="search-expense">
      </div>
      <div class="mono table-head expense-row"><div>DATE</div><div>DESCRIPTION</div><div>CATEGORY</div><div>AMOUNT</div><div>PAYMENT</div><div>PAID BY</div><div></div></div>
      ${state.expenses.length===0 ? `<div class="empty-msg">No expenses logged yet. Click "+ Log New Expense" above.</div>` : (filtered.length===0 ? `<div class="empty-msg">No expenses match your search.</div>` : rows)}
      ${renderPagination(state.expensePage, totalItems, pageSize, 'set-expense-page')}
    </div>
  `;
}

function renderEmployees() {
  const partners = state.config.partners;
  const empHtml = state.config.employees.map(emp => renderEmployeeCard(emp, partners)).join("");
  const workItemsHtml = (state.workItems || []).map(wi => `
    <div class="d-flex align-items-center justify-content-between p-2 mb-1 rounded bg-white border" style="font-size:13px;">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <span class="fw-bold">${esc(wi.name)}</span>
        <span class="badge bg-success text-white mono">${fmt(wi.unitPrice)} / ${esc(wi.unit || 'piece')}</span>
        ${wi.description ? `<span class="text-muted small">${esc(wi.description)}</span>` : ''}
      </div>
      <div class="d-flex gap-1">
        <button class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:11px;" onclick="editWorkItem('${wi.id}')" title="Edit Item">✏️ Edit</button>
        <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:11px;" onclick="deleteWorkItem('${wi.id}')" title="Delete Item">🗑️</button>
      </div>
    </div>
  `).join('');

  return `
    <div class="panel">
      <div class="serif strong" style="margin-bottom:4px">Add Employee Profile</div>
      <div class="muted small" style="margin-bottom:12px">Register workers for weekly/monthly salary or piece-rate work, and set their joining advance (Peshgi).</div>
      <div class="row g-2 align-items-center">
        <div class="col-md-4 col-12"><input class="form-control" id="new-emp-name" placeholder="Full Name (e.g. Ali Raza)"></div>
        <div class="col-md-4 col-12"><input class="form-control" id="new-emp-phone" placeholder="Phone (e.g. 0300-1234567)"></div>
        <div class="col-md-4 col-12">
          <select class="form-select" id="new-emp-type" onchange="toggleEmpTypeUI()">
            <option value="weekly">Weekly salary</option>
            <option value="monthly">Monthly salary</option>
            <option value="workbased">Work-based (piece rate)</option>
          </select>
        </div>
        <div class="col-md-6 col-12 mt-2">
          <input class="form-control" id="new-emp-joining-adv" type="number" placeholder="Joining Advance / Peshgi (optional)">
        </div>
        <div class="col-md-6 col-12 mt-2">
          <select class="form-select" id="new-emp-joining-paidby">
            <option value="business" selected>🏢 Business Funds (Shared by Ratio)</option>
            ${partners.map(p=>`<option value="${p.id}">Peshgi Given By ${esc(p.name)} individually</option>`).join("")}
          </select>
        </div>
        <div class="col-12" id="new-emp-weekly-wrap">
          <div class="d-flex gap-2 align-items-center mt-2">
            <input class="form-control" id="new-emp-weekly" type="number" placeholder="Weekly salary amount" style="max-width:240px">
            <button class="btn btn-dark fw-bold px-4" data-act="add-employee">👤 + Add Employee</button>
          </div>
        </div>
        <div class="col-12" id="new-emp-monthly-wrap" style="display:none;">
          <div class="d-flex gap-2 align-items-center mt-2">
            <input class="form-control" id="new-emp-monthly" type="number" placeholder="Monthly salary amount" style="max-width:240px">
            <button class="btn btn-dark fw-bold px-4" data-act="add-employee">👤 + Add Employee</button>
          </div>
        </div>
        <div class="col-12" id="new-emp-work-wrap" style="display:none;">
          <div class="d-flex gap-2 align-items-center mt-2">
            <span class="muted small">Piece-rate work items will be added after profile creation.</span>
            <button class="btn btn-dark fw-bold px-4" data-act="add-employee">👤 + Add Employee</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Work Items Catalog Panel -->
    <div class="panel" style="background:#F0FDF4;border:1px solid #86EFAC;">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
          <div class="serif strong" style="color:#166534;">🛠️ Work Items Catalog (Piece-Rate Items)</div>
          <div class="muted small">Define all work items and their rates here first. Workers select from this list when logging production work.</div>
        </div>
      </div>

      <!-- Add new work item form -->
      <div class="row g-2 align-items-end mb-3 p-3 rounded bg-white border">
        <div class="col-md-4 col-12">
          <label class="form-label small fw-bold mb-1" style="color:#166534;">Item / Operation Name *</label>
          <input class="form-control" id="wi-name" placeholder="e.g. Leather Upper Stitching">
        </div>
        <div class="col-md-2 col-6">
          <label class="form-label small fw-bold mb-1" style="color:#166534;">Rate (Rs.) *</label>
          <input class="form-control" id="wi-rate" type="number" placeholder="e.g. 120">
        </div>
        <div class="col-md-2 col-6">
          <label class="form-label small fw-bold mb-1" style="color:#166534;">Unit</label>
          <input class="form-control" id="wi-unit" placeholder="e.g. pair, piece, dozen">
        </div>
        <div class="col-md-2 col-12">
          <label class="form-label small fw-bold mb-1" style="color:#166534;">Description (optional)</label>
          <input class="form-control" id="wi-desc" placeholder="Short description">
        </div>
        <div class="col-md-2 col-12">
          <button class="btn btn-success fw-bold w-100" onclick="addWorkItem()">+ Add Item</button>
        </div>
      </div>

      <!-- Existing work items list -->
      <div id="work-items-list">
        ${(state.workItems || []).length === 0
          ? `<div class="text-center text-muted p-3" style="font-size:13px;">No work items defined yet. Add your first item above (e.g. "Leather Upper Stitching" at Rs.120/pair).</div>`
          : workItemsHtml}
      </div>
    </div>

    ${state.config.employees.length===0 ? `<div class="empty-msg">No employees yet — add one above.</div>` : empHtml}
  `;
}


function renderEmployeeCard(emp, partners) {
  const workLogs = empWorkLogs(emp.id);
  const payments = empSalaryPayments(emp.id);
  const advances = empAdvances(emp.id);
  const attLogs = empAttendanceLogs(emp.id);
  const unsettledAbs = empUnsettledAbsences(emp.id);
  const absenceDeductionVal = empAbsenceDeduction(emp);
  const totalEarned = workLogs.reduce((s, w) => s + w.amount, 0);
  const totalWagePaid = payments.reduce((s, p) => s + Number(p.amount || 0), 0);
  const outstandingAdv = empOutstandingAdvance(emp.id);
  const joiningAdvEntry = advances.find(a => a.isJoiningAdvance || (a.note && a.note.toLowerCase().includes("joining")));
  const joiningAdvVal = emp.joiningAdvance || (joiningAdvEntry ? joiningAdvEntry.amount : 0);
  const weeklyAdvVal = Math.max(0, outstandingAdv - (joiningAdvEntry && !joiningAdvEntry.settled ? joiningAdvEntry.amount : 0));
  const outstandingReimb = empOutstandingReimbursement(emp.id);
  const outstandingHeld = empOutstandingHeldIncome(emp.id);
  const owed = emp.type === "workbased" ? (totalEarned - totalWagePaid - outstandingAdv - outstandingHeld + outstandingReimb) : null;

  const defaultWD = emp.type === "monthly" ? 26 : 6;
  const wDays = emp.workingDays || defaultWD;
  const baseRate = emp.type === "monthly" ? Number(emp.monthlyRate || 0) : Number(emp.weeklyRate || 0);

  const headSub = emp.type === "weekly"
    ? `weekly · ${fmt(emp.weeklyRate)} (${wDays}d/wk)`
    : (emp.type === "monthly"
      ? `monthly · ${fmt(emp.monthlyRate)} (${wDays}d/mo)`
      : "work-based");

  const safeId = emp.id.replace(/[^a-zA-Z0-9]/g, '_');

  return `
    <div class="panel emp-card mb-2" style="padding:0;overflow:hidden;">
      <!-- Header Row -->
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2" style="padding:14px 18px;cursor:pointer;" onclick="toggleEmpCollapse('${safeId}')">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span style="font-size:13px;color:#86868B;transition:transform 0.2s;" id="emp-chevron-${safeId}">▶</span>
          <span class="serif strong" style="font-size:16px;">${esc(emp.name)}</span>
          ${emp.phone ? `<span class="muted small">📞 ${esc(emp.phone)}</span>` : ""}
          <span class="badge bg-light text-dark border">— ${headSub}</span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          ${emp.type==="workbased" ? `<span class="mono small strong ${owed>0?'text-danger':'text-success'}">${owed>0?`owes ${fmt(owed)}`:"settled"}</span>` : ""}
          ${unsettledAbs.length>0 ? `<span class="mono small strong text-danger">absent ${unsettledAbs.length}d (${fmt(absenceDeductionVal)})</span>` : ""}
          ${joiningAdvVal>0 ? `<span class="mono small strong text-warning" title="Joining Advance (Peshgi)">Peshgi ${fmt(joiningAdvVal)}</span>` : ""}
          ${weeklyAdvVal>0 ? `<span class="mono small strong gold" title="Weekly Advances (Kharcha)">Kharcha ${fmt(weeklyAdvVal)}</span>` : ""}
          
          <a href="employee_detail.php?id=${emp.id}" class="btn btn-sm btn-dark fw-bold px-3 py-1" style="font-size:12px;" title="View Employee Ledger & Details" onclick="event.stopPropagation()">👁️ Full Details</a>
          <button class="icon-btn" onclick="event.stopPropagation(); showEditEmployeeModal('${emp.id}')" title="Edit Profile">✏️</button>
          <button class="icon-btn" onclick="event.stopPropagation(); deleteEmployee('${emp.id}')" title="Delete Employee" style="color:var(--rust);margin-left:2px">🗑️</button>
        </div>
      </div>

      <!-- Collapsible Body -->
      <div id="emp-collapse-${safeId}" style="display:none;border-top:1px solid #E5E5EA;background:#FAFAFA;">

        ${emp.type === 'workbased' ? `
        <!-- Piece-Rate Work Log Breakthrough -->
        <div style="padding:16px 18px;">
          <div style="font-weight:700;font-size:13px;color:#166534;margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;">
            <span>🛠️ Piece-Rate Work Production Log</span>
            <span class="badge bg-success text-white mono" style="font-size:11px;" id="wl-badge-${safeId}">Total: ${fmt(totalEarned)}</span>
          </div>

          <div class="table-responsive mb-3">
            <table class="table table-sm table-hover table-bordered align-middle mb-0 bg-white" style="font-size:11.5px;">
              <thead class="table-dark mono" style="font-size:10.5px;">
                <tr>
                  <th>DATE</th>
                  <th>ITEM / OPERATION</th>
                  <th>QTY</th>
                  <th class="text-end">RATE/PIECE</th>
                  <th class="text-end">EARNED (+)</th>
                  <th>NOTE</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                ${workLogs.length === 0
                  ? `<tr><td colspan="7" class="text-center text-muted py-3" style="font-size:12px;">No work production entries yet. Log one below.</td></tr>`
                  : workLogs.map(w => `
                    <tr>
                      <td class="mono" style="font-size:11px;color:#86868B;">${w.date}</td>
                      <td style="font-weight:600;">${esc(w.itemLabel || 'Production Item')}</td>
                      <td class="mono text-center">${w.quantity}</td>
                      <td class="mono text-end">${fmt(w.unitPrice)}</td>
                      <td class="mono text-end fw-bold text-success">${fmt(w.amount)}</td>
                      <td class="small text-muted">${w.note ? esc(w.note) : '—'}</td>
                      <td><button class="btn btn-sm btn-outline-danger py-0 px-1" style="font-size:10px;" onclick="quickDeleteWorkLog('${w.id}')" title="Delete">🗑️</button></td>
                    </tr>
                  `).join('')}
              </tbody>
            </table>
          </div>

          <!-- Quick Log Work Form (Dropdown-Based) -->
          <div style="background:#F0FDF4;border:1px solid #86EFAC;border-radius:10px;padding:14px;">
            <div style="font-weight:700;font-size:12px;color:#166534;margin-bottom:10px;">🔨 Quick Log Work Production</div>
            ${(state.workItems || []).length === 0 ? `
              <div class="text-muted text-center p-2" style="font-size:12px;">
                ⚠️ No work items defined yet. Go to <strong>🛠️ Work Items Catalog</strong> above to add items first, then come back to log work.
              </div>
            ` : `
            <div class="row g-2">
              <div class="col-md-2 col-6">
                <label class="form-label" style="font-size:10px;margin-bottom:2px;">Date</label>
                <input type="date" id="qwl-date-${safeId}" class="form-control form-control-sm" value="${new Date().toISOString().split('T')[0]}">
              </div>
              <div class="col-md-4 col-12">
                <label class="form-label" style="font-size:10px;margin-bottom:2px;">Select Item *</label>
                <select id="qwl-item-${safeId}" class="form-select form-select-sm" onchange="onQuickItemSelect('${safeId}')">
                  <option value="">— Select a work item —</option>
                  ${(state.workItems || []).map(wi => `<option value="${wi.id}" data-rate="${wi.unitPrice}" data-label="${esc(wi.name)}" data-unit="${esc(wi.unit||'piece')}">${esc(wi.name)} (${fmt(wi.unitPrice)}/${esc(wi.unit||'piece')})</option>`).join('')}
                </select>
              </div>
              <div class="col-md-2 col-6">
                <label class="form-label" style="font-size:10px;margin-bottom:2px;">Quantity *</label>
                <input type="number" id="qwl-qty-${safeId}" class="form-control form-control-sm" placeholder="e.g. 50" oninput="calcQuickWorkTotal('${safeId}')">
              </div>
              <div class="col-md-2 col-6">
                <label class="form-label" style="font-size:10px;margin-bottom:2px;">Rate (auto-filled)</label>
                <input type="number" id="qwl-rate-${safeId}" class="form-control form-control-sm bg-light" placeholder="Auto" readonly>
              </div>
              <div class="col-md-2 col-12">
                <label class="form-label" style="font-size:10px;margin-bottom:2px;">Total Earned</label>
                <div class="form-control form-control-sm mono fw-bold text-success bg-white" id="qwl-total-${safeId}" style="font-size:12px;">Rs.0.00</div>
              </div>
              <div class="col-md-8 col-12">
                <input id="qwl-note-${safeId}" class="form-control form-control-sm" placeholder="Note (optional)">
              </div>
              <div class="col-md-4 col-12">
                <button class="btn btn-success btn-sm fw-bold w-100" onclick="quickSaveWorkLog('${emp.id}','${safeId}')">✅ Save Work Entry</button>
              </div>
            </div>
            `}
          </div>
        </div>
        ` : `
        <!-- Summary for weekly/monthly employees -->
        <div style="padding:16px 18px;">
          <div class="row g-3">
            <div class="col-md-3 col-6 text-center p-2 rounded bg-white border">
              <div style="font-size:10px;font-weight:700;color:#86868B;">JOINING PESHGI</div>
              <div class="mono fw-bold ${joiningAdvVal>0?'text-warning':'text-success'}" style="font-size:14px;">${fmt(joiningAdvVal)}</div>
            </div>
            <div class="col-md-3 col-6 text-center p-2 rounded bg-white border">
              <div style="font-size:10px;font-weight:700;color:#86868B;">WEEKLY KHARCHA</div>
              <div class="mono fw-bold ${weeklyAdvVal>0?'text-danger':'text-success'}" style="font-size:14px;">${fmt(weeklyAdvVal)}</div>
            </div>
            <div class="col-md-3 col-6 text-center p-2 rounded bg-white border">
              <div style="font-size:10px;font-weight:700;color:#86868B;">UNSETTLED ABSENCES</div>
              <div class="mono fw-bold ${unsettledAbs.length>0?'text-danger':'text-success'}" style="font-size:14px;">${unsettledAbs.length > 0 ? `${unsettledAbs.length}d · ${fmt(absenceDeductionVal)}` : 'None'}</div>
            </div>
            <div class="col-md-3 col-6 text-center p-2 rounded bg-white border">
              <div style="font-size:10px;font-weight:700;color:#86868B;">BASE RATE</div>
              <div class="mono fw-bold text-dark" style="font-size:14px;">${fmt(baseRate)}</div>
            </div>
          </div>
          <div class="mt-3 text-center">
            <a href="employee_detail.php?id=${emp.id}" class="btn btn-dark btn-sm fw-bold px-4">📋 Open Full Ledger & Payday Settlement</a>
          </div>
        </div>
        `}

      </div>
    </div>
  `;
}


function renderVendors() {
  const partners = state.config.partners;
  const vendorHtml = state.config.vendors.map(v => renderVendorCard(v, partners)).join("");
  return `
    <div class="panel">
      <div class="serif strong" style="margin-bottom:10px">Add a vendor</div>
      <div class="row g-2 align-items-center">
        <div class="col-md-4 col-12"><input class="form-control" id="new-vendor-name" placeholder="Vendor name (e.g. Leather supplier)"></div>
        <div class="col-md-3 col-12"><input class="form-control" id="new-vendor-phone" placeholder="Phone (e.g. 0300-9876543)"></div>
        <div class="col-md-3 col-12"><input class="form-control" id="new-vendor-note" placeholder="Note / Address (optional)"></div>
        <div class="col-md-2 col-12"><button class="btn btn-dark fw-bold w-100" data-act="add-vendor">+ Add Vendor</button></div>
      </div>
    </div>
    ${state.config.vendors.length===0 ? `<div class="empty-msg">No vendors yet — add one above.</div>` : vendorHtml}
  `;
}

function renderVendorCard(vendor, partners) {
  const purchases = vendorPurchases(vendor.id);
  const payments = vendorPaymentsFor(vendor.id);
  const outstanding = vendorOutstanding(vendor.id);

  return `
    <div class="panel emp-card mb-2" style="padding:14px 18px;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span class="serif strong" style="font-size:16px;">${esc(vendor.name)}</span>
          ${vendor.phone ? `<span class="muted small">📞 ${esc(vendor.phone)}</span>` : ""}
          ${vendor.note ? `<span class="badge bg-light text-dark border">— ${esc(vendor.note)}</span>` : ""}
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          ${outstanding > 0.005 ? `<span class="mono small strong text-danger">Owe ${fmt(outstanding)}</span>` : `<span class="mono small strong text-success">settled</span>`}
          
          <a href="vendor_detail.php?id=${vendor.id}" class="btn btn-sm btn-dark fw-bold px-3 py-1" style="font-size:12px;" title="View Vendor Purchases & Payments">👁️ View Details</a>
          <button class="icon-btn" data-act="edit-vendor" data-id="${vendor.id}" title="Edit Vendor Profile">✏️</button>
          <button class="icon-btn" data-act="delete-vendor" data-id="${vendor.id}" title="Delete Vendor" style="color:var(--rust);margin-left:2px">🗑️</button>
        </div>
      </div>
    </div>
  `;
}

function renderSettings() {
  const partnersHtml = state.config.partners.map(p => `
    <div class="row g-2 align-items-center mb-2">
      <div class="col-md-4 col-12"><label class="form-label small text-muted mb-0">Partner Name</label><input class="form-control" data-act="partner-name" data-id="${p.id}" value="${esc(p.name)}"></div>
      <div class="col-md-4 col-6"><label class="form-label small text-muted mb-0">Ratio Share</label><input class="form-control" type="number" data-act="partner-ratio" data-id="${p.id}" value="${p.ratio}"></div>
      <div class="col-md-4 col-6"><label class="form-label small text-muted mb-0">Starting Capital</label><input class="form-control" type="number" data-act="partner-cap" data-id="${p.id}" value="${p.openingCapital || 0}"></div>
    </div>`).join("");
  return `
    <div class="panel">
      <div class="serif strong" style="margin-bottom:12px">Partners, split ratio &amp; starting capital</div>
      ${partnersHtml}
      <div class="muted small" style="margin-top:8px">Ratio is relative (e.g. 1 and 2 means your friend gets 2/3 of net profit and you get 1/3). Starting capital is money contributed before using the app.</div>
    </div>
    <div class="panel">
      <div class="serif strong" style="margin-bottom:12px">Currency symbol</div>
      <input class="form-control" style="width:100px" data-act="currency" value="${esc(state.config.currency)}">
    </div>
    <div class="panel">
      <div class="serif strong" style="margin-bottom:8px">Passcode & Security</div>
      <div class="muted small" style="margin-bottom:12px">Change your access passcode (default is <code>1234</code>).</div>
      <form id="passcode-form" class="row g-2 align-items-center">
        <div class="col-auto"><input class="form-control" type="password" id="cur-passcode" placeholder="Current passcode" style="width:180px" required></div>
        <div class="col-auto"><input class="form-control" type="password" id="new-passcode" placeholder="New passcode" style="width:180px" required minlength="4"></div>
        <div class="col-auto"><button type="submit" class="btn btn-dark fw-bold px-4">Update Passcode</button></div>
      </form>
      <div id="pass-msg" style="margin-top:6px" class="small"></div>
    </div>
    <div class="panel">
      <div class="serif strong" style="margin-bottom:8px">Data Backup & Restore</div>
      <div class="muted small" style="margin-bottom:12px">Export your ledger data to a JSON file for safe offline backup, or restore data from a backup file.</div>
      <div style="display:flex;gap:10px;flex-wrap:wrap">
        <button class="btn btn-outline-dark fw-bold" data-act="export-data">📥 Export Backup (JSON)</button>
        <button class="btn btn-outline-dark fw-bold" onclick="document.getElementById('import-file-input').click()">📤 Restore from Backup File</button>
        <input type="file" id="import-file-input" style="display:none" accept=".json" onchange="importDataFile(event)">
      </div>
    </div>
  `;
}

function renderReports() {
  const range = state.reportRange || "all";
  let startDate = state.reportStartDate || "";
  let endDate = state.reportEndDate || "";

  if (range === "this_month") {
    const d = new Date();
    startDate = new Date(d.getFullYear(), d.getMonth(), 1).toISOString().slice(0, 10);
    endDate = today();
  } else if (range === "last_month") {
    const d = new Date();
    startDate = new Date(d.getFullYear(), d.getMonth() - 1, 1).toISOString().slice(0, 10);
    endDate = new Date(d.getFullYear(), d.getMonth(), 0).toISOString().slice(0, 10);
  } else if (range === "this_week") {
    const d = new Date();
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    startDate = new Date(d.setDate(diff)).toISOString().slice(0, 10);
    endDate = today();
  }

  const inRange = (dateStr) => {
    if (!dateStr) return true;
    if (range === "all") return true;
    if (startDate && dateStr < startDate) return false;
    if (endDate && dateStr > endDate) return false;
    return true;
  };

  const repSales = state.income.filter(s => inRange(s.date));
  const repExpenses = state.expenses.filter(e => inRange(e.date));
  const repSalaryPayments = state.salaryPayments.filter(p => inRange(p.date));
  const repAdvances = state.advances.filter(a => inRange(a.date));
  const repAttendance = (state.attendanceLogs || []).filter(a => inRange(a.date));

  const totalOrderValue = repSales.reduce((s, x) => s + Number(x.amount || 0), 0);
  const totalIncomeCollected = repSales.reduce((s, sale) => {
    const pms = getSalePayments(sale);
    return s + pms.filter(p => inRange(p.date)).reduce((sum, p) => sum + Number(p.amount || 0), 0);
  }, 0);
  const totalPendingReceivables = Math.max(0, totalOrderValue - totalIncomeCollected);

  const totalDirectExpenses = repExpenses.reduce((s, x) => s + Number(x.amount || 0), 0);
  const totalSalariesPaid = repSalaryPayments.reduce((s, x) => s + Number(x.amount || 0) + Number(x.reimbursedAmount || 0), 0);
  const totalAdvancesGiven = repAdvances.reduce((s, a) => s + Number(a.amount || 0), 0);
  const totalOutflows = totalDirectExpenses + totalSalariesPaid + totalAdvancesGiven;
  const netProfitPeriod = totalIncomeCollected - totalOutflows;

  const itemSummary = {};
  repSales.forEach(s => {
    const key = (s.item || "Unspecified Item").trim();
    if (!itemSummary[key]) itemSummary[key] = { qty: 0, total: 0, paid: 0 };
    itemSummary[key].qty += Number(s.quantity || 1);
    itemSummary[key].total += Number(s.amount || 0);
    itemSummary[key].paid += getIncomePaid(s);
  });
  const sortedItems = Object.entries(itemSummary).sort((a, b) => b[1].total - a[1].total);

  const empReportRows = state.config.employees.map(emp => {
    const empPays = repSalaryPayments.filter(p => p.employeeId === emp.id);
    const empAdvs = repAdvances.filter(a => a.employeeId === emp.id);
    const empAtts = repAttendance.filter(a => a.employeeId === emp.id);
    const wagePaid = empPays.reduce((s, p) => s + Number(p.amount || 0), 0);
    const advGiven = empAdvs.reduce((s, a) => s + Number(a.amount || 0), 0);
    const absCount = empAtts.length;
    const absLoss = empAbsenceDeduction(emp);
    return { emp, wagePaid, advGiven, absCount, absLoss };
  });

  return `
    <div class="panel" style="margin-bottom:16px;">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
          <div class="serif strong" style="font-size:18px;">📊 Financial &amp; Operational Reports</div>
          <div class="muted small">Detailed breakdown of Income, Salaries, Attendance, Expenses, and Profit.</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
          <button class="btn btn-outline-dark btn-sm fw-bold" onclick="window.print()">🖨️ Print / Save PDF Statement</button>
          <select class="form-select" id="rep-range" data-act="set-report-range" style="width:150px;">
            <option value="all" ${range==='all'?'selected':''}>All Time</option>
            <option value="this_month" ${range==='this_month'?'selected':''}>This Month</option>
            <option value="last_month" ${range==='last_month'?'selected':''}>Last Month</option>
            <option value="this_week" ${range==='this_week'?'selected':''}>This Week</option>
            <option value="custom" ${range==='custom'?'selected':''}>Custom Range</option>
          </select>
          ${range === 'custom' ? `
            <input class="form-control" type="date" id="rep-start" value="${startDate}" data-act="set-report-start" style="width:140px;">
            <span class="muted small">to</span>
            <input class="form-control" type="date" id="rep-end" value="${endDate}" data-act="set-report-end" style="width:140px;">
          ` : ''}
        </div>
      </div>
    </div>

    <div class="grid3" style="margin-bottom:16px;">
      <div class="panel total-box">
        <div class="muted serif" style="font-size:13px">Total Revenue Collected</div>
        <div class="mono teal-text" style="font-size:22px;font-weight:700">${fmt(totalIncomeCollected)}</div>
        <div class="muted small" style="margin-top:4px;">Total Sales Value: <span class="mono">${fmt(totalOrderValue)}</span></div>
      </div>
      <div class="panel total-box">
        <div class="muted serif" style="font-size:13px">Total Outflows &amp; Payroll</div>
        <div class="mono rust-text" style="font-size:22px;font-weight:700">${fmt(totalOutflows)}</div>
        <div class="muted small" style="margin-top:4px;">Expenses: <span class="mono">${fmt(totalDirectExpenses)}</span> | Salaries: <span class="mono">${fmt(totalSalariesPaid)}</span></div>
      </div>
      <div class="panel total-box">
        <div class="muted serif" style="font-size:13px">Period Net Profit</div>
        <div class="mono" style="font-size:22px;font-weight:700;color:${netProfitPeriod>=0?'var(--teal)':'var(--rust)'}">${fmt(netProfitPeriod)}</div>
        <div class="muted small" style="margin-top:4px;">Pending Sales Due: <span class="mono gold">${fmt(totalPendingReceivables)}</span></div>
      </div>
    </div>

    <div class="panel" style="margin-bottom:16px;">
      <div class="serif strong" style="margin-bottom:10px;">📦 Sales &amp; Item Breakdown</div>
      ${sortedItems.length === 0 ? `<div class="empty-msg">No sales found in selected date range.</div>` : `
        <div class="table-panel">
          <div class="mono table-head" style="display:grid;grid-template-columns:1fr 80px 110px 110px 110px;gap:8px;">
            <div>ITEM / PRODUCT</div><div>QTY</div><div>TOTAL PRICE</div><div>PAID CASH</div><div>DUE BALANCE</div>
          </div>
          ${sortedItems.map(([item, data]) => {
            const due = Math.max(0, data.total - data.paid);
            return `<div class="row-line" style="display:grid;grid-template-columns:1fr 80px 110px 110px 110px;gap:8px;align-items:center;">
              <div class="strong">${esc(item)}</div>
              <div class="mono small">${data.qty}</div>
              <div class="mono strong">${fmt(data.total)}</div>
              <div class="mono teal-text">${fmt(data.paid)}</div>
              <div class="mono ${due>0?'gold':''}">${fmt(due)}</div>
            </div>`;
          }).join('')}
        </div>
      `}
    </div>

    <div class="panel" style="margin-bottom:16px;">
      <div class="serif strong" style="margin-bottom:10px;">👥 Employee Payroll &amp; Attendance Report</div>
      <div class="table-panel">
        <div class="mono table-head" style="display:grid;grid-template-columns:1fr 110px 110px 110px 130px;gap:8px;">
          <div>EMPLOYEE</div><div>TYPE</div><div>WAGES PAID</div><div>ADVANCES</div><div>ABSENCE DEDUCTIONS</div>
        </div>
        ${empReportRows.map(r => `
          <div class="row-line" style="display:grid;grid-template-columns:1fr 110px 110px 110px 130px;gap:8px;align-items:center;">
            <div class="strong">${esc(r.emp.name)} ${r.emp.phone?`<span class="muted small">(${esc(r.emp.phone)})</span>`:''}</div>
            <div class="small">${r.emp.type==='weekly'?'Weekly Salary':(r.emp.type==='monthly'?'Monthly Salary':'Piece-Rate')}</div>
            <div class="mono strong teal-text">${fmt(r.wagePaid)}</div>
            <div class="mono gold">${fmt(r.advGiven)}</div>
            <div class="mono rust-text">${r.absCount}d (${fmt(r.absLoss)})</div>
          </div>
        `).join('')}
      </div>
    </div>
  `;
}

function render() {
  const app = document.getElementById("app");
  if (!state.loaded && state.authenticated) { app.innerHTML = `<div class="loading">Loading ledger…</div>`; return; }
  if (!state.authenticated) { app.innerHTML = ""; return; }
  let content = "";
  if (state.tab === "overview") content = renderOverview();
  else if (state.tab === "income") content = renderIncome();
  else if (state.tab === "expenses") content = renderExpenses();
  else if (state.tab === "employees") content = renderEmployees();
  else if (state.tab === "vendors") content = renderVendors();
  else if (state.tab === "customers") content = renderCustomers();
  else if (state.tab === "reports") content = renderReports();
  else if (state.tab === "settings") content = renderSettings();

  app.innerHTML = `
    <div class="app-shell">
      ${renderTabs()}
      <main class="main-content">
        ${renderHeader()}
        <div class="view-container">
          ${content}
        </div>
      </main>
    </div>
  `;
}

function toggleEmpTypeUI() {
  const t = document.getElementById("new-emp-type").value;
  const wWrap = document.getElementById("new-emp-weekly-wrap");
  const mWrap = document.getElementById("new-emp-monthly-wrap");
  const kWrap = document.getElementById("new-emp-work-wrap");
  if (wWrap) wWrap.style.display = t === "weekly" ? "block" : "none";
  if (mWrap) mWrap.style.display = t === "monthly" ? "block" : "none";
  if (kWrap) kWrap.style.display = t === "workbased" ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", () => {
  const app = document.getElementById("app");

  // Handle Login Form
  document.getElementById("login-form").addEventListener("submit", async (e) => {
    e.preventDefault();
    const pin = document.getElementById("pin-field").value;
    const errEl = document.getElementById("auth-err");
    errEl.style.display = "none";
    try {
      const res = await apiCall("login", { passcode: pin });
      if (res.success) {
        state.authenticated = true;
        document.getElementById("auth-modal").style.display = "none";
        await loadData();
      } else {
        errEl.innerText = res.error || "Incorrect passcode";
        errEl.style.display = "block";
      }
    } catch (err) {
      errEl.innerText = "Error connecting to backend server.";
      errEl.style.display = "block";
    }
  });

  app.addEventListener("click", async (e) => {
    const el = e.target.closest("[data-act]");
    if (!el) return;
    const act = el.dataset.act;

    if (act === "logout") {
      e.preventDefault();
      await apiCall("logout");
      state.authenticated = false;
      document.getElementById("auth-modal").style.display = "flex";
      render();
      return;
    }

    if (act === "toggle-mobile-nav") {
      state.mobileNav = !state.mobileNav;
      render();
      return;
    }
    if (act === "close-mobile-nav") {
      state.mobileNav = false;
      render();
      return;
    }

    if (act === "switch-tab") {
      state.tab = el.dataset.tab;
      state.mobileNav = false;
      render();
      return;
    }

    if (act === "open-add-sale-modal") {
      showAddSaleModal();
      return;
    }
    if (act === "open-add-expense-modal") {
      showAddExpenseModal();
      return;
    }

    if (act === "quick-add-modal") {
      showAddSaleModal();
      return;
    }

    if (act === "set-income-page") {
      state.incomePage = Number(el.dataset.page);
      render();
      return;
    }
    if (act === "set-expense-page") {
      state.expensePage = Number(el.dataset.page);
      render();
      return;
    }

    if (act === "export-data") {
      const payload = JSON.stringify({ config: state.config, expenses: state.expenses, income: state.income, workLogs: state.workLogs, salaryPayments: state.salaryPayments, advances: state.advances, vendorPayments: state.vendorPayments }, null, 2);
      const blob = new Blob([payload], { type: "application/json" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = `ledger-backup-${today()}.json`;
      a.click();
      URL.revokeObjectURL(url);
      return;
    }
    if (act === "import-trigger") {
      const input = document.getElementById("import-file-input");
      if (input) input.click();
      return;
    }

    if (act === "add-expense") {
      const date = document.getElementById("exp-date").value || today();
      const description = document.getElementById("exp-desc").value.trim();
      const amount = Number(document.getElementById("exp-amount").value);
      const category = document.getElementById("exp-category").value;
      const paymentMethod = document.getElementById("exp-pm").value;
      const payerVal = document.getElementById("exp-payer").value;
      const [payerType, payerId] = payerVal.split(":");
      const fileInput = document.getElementById("exp-receipt-file");
      if (!description || !amount) return;

      const createAndSave = (receiptUrl = null) => {
        const entry = { id: uid(), date, description, amount, category, paymentMethod, receiptUrl };
        if (payerType === "employee") { entry.payerEmployeeId = payerId; entry.settled = false; }
        else { entry.paidBy = payerId; }
        mutate(() => state.expenses.unshift(entry));
        toast("💸 Expense logged successfully!");
      };

      if (fileInput && fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(evt) {
          createAndSave(evt.target.result);
        };
        reader.readAsDataURL(fileInput.files[0]);
      } else {
        createAndSave(null);
      }
      return;
    }
    if (act === "view-receipt") {
      showReceiptModal(el.dataset.id);
      return;
    }
    if (act === "edit-expense") {
      showEditExpenseModal(el.dataset.id);
      return;
    }
    if (act === "delete-expense") {
      const id = el.dataset.id;
      const confirmDelete = await swalConfirm("Delete Expense?", "Are you sure you want to delete this expense entry?");
      if (!confirmDelete) return;
      mutate(() => { state.expenses = state.expenses.filter(x => x.id !== id); });
      return;
    }
    if (act === "wa-sale") {
      sendWhatsAppReminder(el.dataset.id);
      return;
    }
    if (act === "wa-direct") {
      const phone = el.dataset.phone.replace(/[^0-9]/g, '');
      const name = el.dataset.name;
      const due = el.dataset.due;
      const msg = `Assalamu Alaikum ${name},\n\nThis is a friendly payment reminder from *Velto Leather Shoes* regarding your pending balance of ${state.config.currency}${due}.\n\nThank you for choosing Velto Leather Shoes!`;
      window.open(`https://wa.me/${phone}?text=${encodeURIComponent(msg)}`, '_blank');
      return;
    }

    if (act === "add-customer") {
      const name = document.getElementById("cust-name").value.trim();
      const phone = document.getElementById("cust-phone").value.trim();
      const address = document.getElementById("cust-address").value.trim();
      if (!name) return;
      mutate(() => {
        state.config.customers = state.config.customers || [];
        state.config.customers.unshift({ id: uid(), name, phone, address });
      });
      toast("👤 Customer profile created!");
      return;
    }
    if (act === "edit-customer") {
      const id = el.dataset.id;
      const cust = (state.config.customers || []).find(c => c.id === id);
      if (!cust) return;
      const { value: formValues } = await Swal.fire({
        title: 'Edit Customer Profile',
        html:
          `<div style="text-align:left;display:flex;flex-direction:column;gap:10px;">` +
          `<div><label style="font-size:12px;font-weight:600;">Customer Name</label><input id="swal-cust-name" class="swal2-input" value="${esc(cust.name||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
          `<div><label style="font-size:12px;font-weight:600;">Phone / WhatsApp</label><input id="swal-cust-phone" class="swal2-input" value="${esc(cust.phone||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
          `<div><label style="font-size:12px;font-weight:600;">Address</label><input id="swal-cust-address" class="swal2-input" value="${esc(cust.address||'')}" style="margin:4px 0 0 0;width:100%;"></div>` +
          `</div>`,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#2F6F63',
        background: '#FCFAF4',
        color: '#23302B',
        preConfirm: () => {
          const name = document.getElementById('swal-cust-name').value.trim();
          const phone = document.getElementById('swal-cust-phone').value.trim();
          const address = document.getElementById('swal-cust-address').value.trim();
          if (!name) { Swal.showValidationMessage('Please enter customer name'); return false; }
          return { name, phone, address };
        }
      });
      if (formValues) {
        mutate(() => {
          cust.name = formValues.name;
          cust.phone = formValues.phone;
          cust.address = formValues.address;
        });
      }
      return;
    }
    if (act === "delete-customer") {
      const id = el.dataset.id;
      const confirmDelete = await swalConfirm("Delete Customer?", "Are you sure you want to delete this customer profile?");
      if (!confirmDelete) return;
      mutate(() => {
        state.config.customers = (state.config.customers || []).filter(c => c.id !== id);
      });
      return;
    }

    if (act === "toggle-customer") {
      if (e.target.closest("button") || e.target.closest(".icon-btn")) return;
      const id = el.dataset.id;
      state.expandedCust = state.expandedCust === id ? null : id;
      render();
      return;
    }
    if (act === "cust-add-order") {
      const custId = el.dataset.id;
      const cust = (state.config.customers || []).find(c => c.id === custId);
      const item = document.getElementById(`cust-quick-item-${custId}`).value.trim();
      const qty = Number(document.getElementById(`cust-quick-qty-${custId}`).value) || 1;
      const amount = Number(document.getElementById(`cust-quick-amount-${custId}`).value) || 0;
      const paid = Number(document.getElementById(`cust-quick-paid-${custId}`).value) || 0;
      if (!item || !amount) { swalAlert("Required", "Please enter item name and amount", "warning"); return; }
      const entry = {
        id: uid(),
        date: today(),
        item,
        quantity: qty,
        amount,
        customerId: custId,
        note: cust ? `Order for ${cust.name}` : "",
        payments: [
          {
            id: uid(),
            date: today(),
            amount: paid,
            receivedBy: state.config.partners[0]?.id || "p1",
            settled: false,
            note: paid < amount ? "Advance payment" : "Full payment"
          }
        ]
      };
      mutate(() => state.income.unshift(entry));
      toast("📦 Customer order logged successfully!");
      return;
    }

    if (act === "add-income") {
      const date = document.getElementById("inc-date").value || today();
      const item = document.getElementById("inc-item").value.trim();
      const quantity = document.getElementById("inc-qty").value ? Number(document.getElementById("inc-qty").value) : null;
      const totalAmount = Number(document.getElementById("inc-amount").value);
      const paidInput = document.getElementById("inc-paid-amount").value;
      const paidAmount = paidInput !== "" ? Number(paidInput) : totalAmount;
      const receiverVal = document.getElementById("inc-receivedby").value;
      const [receiverType, receiverId] = receiverVal.split(":");
      const custSel = document.getElementById("inc-customer") ? document.getElementById("inc-customer").value : "";
      const note = document.getElementById("inc-note").value.trim();
      if (!item || !totalAmount) return;

      let customerId = custSel;
      state.config.customers = state.config.customers || [];
      if (custSel === "__new__") {
        const newName = document.getElementById("inc-new-cust-name") ? document.getElementById("inc-new-cust-name").value.trim() : "";
        const newPhone = document.getElementById("inc-new-cust-phone") ? document.getElementById("inc-new-cust-phone").value.trim() : "";
        if (newName) {
          const existing = state.config.customers.find(c => c.name.toLowerCase() === newName.toLowerCase() || (newPhone && c.phone === newPhone));
          if (existing) {
            customerId = existing.id;
          } else {
            const newCust = { id: uid(), name: newName, phone: newPhone };
            state.config.customers.unshift(newCust);
            customerId = newCust.id;
          }
        } else {
          customerId = "";
        }
      }

      const entry = {
        id: uid(),
        date,
        item,
        quantity,
        amount: totalAmount,
        customerId,
        note,
        payments: [
          {
            id: uid(),
            date,
            amount: paidAmount,
            receivedBy: receiverType === "partner" ? receiverId : undefined,
            receivedByEmployeeId: receiverType === "employee" ? receiverId : undefined,
            settled: false,
            note: paidAmount < totalAmount ? "Advance payment" : "Full payment"
          }
        ]
      };
      mutate(() => state.income.unshift(entry));
      toast(paidAmount < totalAmount ? "💵 Advance sale logged!" : "🎉 Sale logged successfully!");
      return;
    }
    if (act === "pay-income-balance") {
      showReceiveIncomePaymentModal(el.dataset.id);
      return;
    }
    if (act === "edit-income") {
      showEditIncomeModal(el.dataset.id);
      return;
    }
    if (act === "delete-income") {
      const id = el.dataset.id;
      const confirmDelete = await swalConfirm("Delete Sale Entry?", "Are you sure you want to delete this sale entry?");
      if (!confirmDelete) return;
      mutate(() => { state.income = state.income.filter(x => x.id !== id); });
      return;
    }

    if (act === "add-employee") {
      const name = document.getElementById("new-emp-name").value.trim();
      const phone = document.getElementById("new-emp-phone")?.value.trim() || "";
      const type = document.getElementById("new-emp-type").value;
      if (!name) return;
      const weeklyRate = Number(document.getElementById("new-emp-weekly")?.value || 0);
      const monthlyRate = Number(document.getElementById("new-emp-monthly")?.value || 0);
      const joiningAdv = Number(document.getElementById("new-emp-joining-adv")?.value || 0);
      const joiningPaidBy = document.getElementById("new-emp-joining-paidby")?.value || "business";
      const workingDays = type === 'monthly' ? 26 : 6;
      const emp = { 
        id: uid(), 
        name, 
        phone, 
        type, 
        weeklyRate: type === 'weekly' ? weeklyRate : 0, 
        monthlyRate: type === 'monthly' ? monthlyRate : 0, 
        joiningAdvance: joiningAdv,
        workingDays, 
        items: type === "workbased" ? [] : undefined 
      };
      mutate(() => {
        state.config.employees.push(emp);
        if (joiningAdv > 0) {
          state.advances.unshift({
            id: uid(),
            employeeId: emp.id,
            date: today(),
            amount: joiningAdv,
            paidBy: joiningPaidBy,
            note: "Joining Advance (Peshgi)",
            settled: false,
            isJoiningAdvance: true
          });
        }
      });
      return;
    }
    if (act === "edit-employee") {
      showEditEmployeeModal(el.dataset.id);
      return;
    }
    if (act === "delete-employee") {
      const id = el.dataset.id;
      const confirmDelete = await swalConfirm("Delete Employee?", "All work logs, advances, and payment history for this employee will be deleted.");
      if (!confirmDelete) return;
      mutate(() => {
        state.config.employees = state.config.employees.filter(e => e.id !== id);
        state.workLogs = state.workLogs.filter(w => w.employeeId !== id);
        state.salaryPayments = state.salaryPayments.filter(s => s.employeeId !== id);
        state.advances = state.advances.filter(a => a.employeeId !== id);
        state.attendanceLogs = (state.attendanceLogs || []).filter(a => a.employeeId !== id);
        state.expenses = state.expenses.map(x => x.payerEmployeeId === id ? { ...x, payerEmployeeId: undefined } : x);
        state.income = state.income.map(x => x.receivedByEmployeeId === id ? { ...x, receivedByEmployeeId: undefined } : x);
        if (state.expandedEmp === id) state.expandedEmp = null;
      });
      return;
    }
    if (act === "toggle-employee") {
      const id = el.dataset.id;
      state.expandedEmp = state.expandedEmp === id ? null : id;
      render();
      return;
    }

    if (act === "add-attendance") {
      const empId = el.dataset.emp;
      const date = document.getElementById(`att-date-${empId}`).value || today();
      const status = document.getElementById(`att-status-${empId}`).value;
      const deductVal = document.getElementById(`att-deduct-${empId}`).value;
      const deductSalary = deductVal === "yes";
      const note = document.getElementById(`att-note-${empId}`).value.trim();
      state.cardTab = state.cardTab || {};
      state.cardTab[empId] = "history";
      mutate(() => {
        state.attendanceLogs = state.attendanceLogs || [];
        state.attendanceLogs.unshift({ id: uid(), employeeId: empId, date, status, deductSalary, note, settled: false });
      });
      toast("📅 Absence logged!");
      return;
    }
    if (act === "delete-attendance") {
      const id = el.dataset.id;
      mutate(() => {
        state.attendanceLogs = (state.attendanceLogs || []).filter(a => a.id !== id);
      });
      toast("Deleted absence record.");
      return;
    }

    if (act === "add-item") {
      const empId = el.dataset.emp;
      const label = document.getElementById(`item-label-${empId}`).value.trim();
      const rate = Number(document.getElementById(`item-rate-${empId}`).value);
      if (!label || !rate) return;
      mutate(() => {
        const emp = state.config.employees.find(e => e.id === empId);
        emp.items = emp.items || [];
        emp.items.push({ id: uid(), label, rate });
      });
      return;
    }
    if (act === "edit-item") {
      showEditItemModal(el.dataset.emp, el.dataset.item);
      return;
    }
    if (act === "delete-item") {
      const empId = el.dataset.emp;
      const itemId = el.dataset.item;
      const confirmDelete = await swalConfirm("Delete Work Item?", "Are you sure you want to delete this piece rate work item?");
      if (!confirmDelete) return;
      mutate(() => {
        const emp = state.config.employees.find(e => e.id === empId);
        emp.items = (emp.items || []).filter(it => it.id !== itemId);
      });
      return;
    }

    if (act === "set-card-tab") {
      const id = el.dataset.id;
      const tab = el.dataset.tab;
      state.cardTab = state.cardTab || {};
      state.cardTab[id] = tab;
      render();
      return;
    }

    if (act === "add-work") {
      const empId = el.dataset.emp;
      const emp = state.config.employees.find(e => e.id === empId);
      const date = document.getElementById(`work-date-${empId}`).value || today();
      const itemId = document.getElementById(`work-item-${empId}`).value;
      const item = (emp.items || []).find(it => it.id === itemId);
      const quantity = Number(document.getElementById(`work-qty-${empId}`).value);
      const note = document.getElementById(`work-note-${empId}`).value.trim();
      if (!item) {
        swalAlert("⚠️ No Item Selected", "Please select a piece-rate work item.", "warning");
        return;
      }
      if (!quantity || isNaN(quantity) || quantity <= 0) {
        swalAlert("⚠️ Invalid Quantity", "Please enter a valid quantity greater than zero.", "warning");
        return;
      }
      const amount = quantity * item.rate;
      state.cardTab = state.cardTab || {};
      state.cardTab[empId] = "history";
      mutate(() => state.workLogs.unshift({ id: uid(), employeeId: empId, itemId: item.id, itemLabel: item.label, rate: item.rate, date, quantity, note, amount }));
      toast("🔨 Work logged successfully!");
      return;
    }

    if (act === "add-advance") {
      const empId = el.dataset.emp;
      const date = document.getElementById(`adv-date-${empId}`).value || today();
      const amount = Number(document.getElementById(`adv-amount-${empId}`).value);
      const paidBy = document.getElementById(`adv-paidby-${empId}`).value;
      const note = document.getElementById(`adv-note-${empId}`).value.trim();
      if (!amount || isNaN(amount) || amount <= 0) {
        swalAlert("⚠️ Invalid Advance Amount", "Please enter a valid advance amount greater than zero.", "warning");
        return;
      }
      state.cardTab = state.cardTab || {};
      state.cardTab[empId] = "history";
      mutate(() => state.advances.unshift({ id: uid(), employeeId: empId, date, amount, paidBy, note, settled: false }));
      toast("💵 Advance recorded!");
      return;
    }

    if (act === "add-payment") {
      const empId = el.dataset.emp;
      const emp = state.config.employees.find(e => e.id === empId);
      const outstandingAdv = empOutstandingAdvance(empId);
      const outstandingReimb = empOutstandingReimbursement(empId);
      const outstandingHeld = empOutstandingHeldIncome(empId);
      const unsettledAbs = empUnsettledAbsences(empId);
      const absenceDeductionVal = empAbsenceDeduction(emp);
      const deductAdv = (state.deductAdvance[empId] !== false) && outstandingAdv > 0;
      const includeReimb = (state.includeReimbursement[empId] !== false) && outstandingReimb > 0;
      const deductHeld = (state.deductHeldIncome[empId] !== false) && outstandingHeld > 0;
      const deductAtt = (state.deductAttendance[empId] !== false) && unsettledAbs.length > 0;
      const date = document.getElementById(`pay-date-${empId}`).value || today();
      const amountInput = document.getElementById(`pay-amount-${empId}`).value;

      const workLogs = empWorkLogs(empId);
      const payments = empSalaryPayments(empId);
      const totalEarned = workLogs.reduce((s, w) => s + w.amount, 0);
      const totalWagePaid = payments.reduce((s, p) => s + Number(p.amount || 0), 0);
      const unpaidWorkEarned = Math.max(0, totalEarned - totalWagePaid);

      const baseRate = emp.type === "monthly" ? Number(emp.monthlyRate || 0) : Number(emp.weeklyRate || 0);
      const suggestedWage = (emp.type === "weekly" || emp.type === "monthly")
        ? Math.max(baseRate - (deductAdv ? outstandingAdv : 0) - (deductHeld ? outstandingHeld : 0) - (deductAtt ? absenceDeductionVal : 0), 0)
        : Math.max(unpaidWorkEarned - (deductAdv ? outstandingAdv : 0) - (deductHeld ? outstandingHeld : 0), 0);

      let wageAmount, reimbursedAmount;
      if (amountInput !== "") {
        wageAmount = Number(amountInput);
        reimbursedAmount = includeReimb ? outstandingReimb : 0;
      } else {
        wageAmount = suggestedWage;
        reimbursedAmount = includeReimb ? outstandingReimb : 0;
      }
      const paidBy = document.getElementById(`pay-paidby-${empId}`).value;
      const note = document.getElementById(`pay-note-${empId}`).value.trim();
      if (!wageAmount && wageAmount !== 0 && !reimbursedAmount) return;
      if (wageAmount === 0 && reimbursedAmount === 0 && (emp.type !== "weekly" && emp.type !== "monthly") && unpaidWorkEarned === 0) return;
      const partialAdvInput = document.getElementById(`pay-adv-deduct-amt-${empId}`)?.value;
      let advDeductVal = 0;
      if (deductAdv) {
        const rawPartial = partialAdvInput !== undefined ? Number(partialAdvInput) : outstandingAdv;
        if (isNaN(rawPartial) || rawPartial < 0) {
          swalAlert("⚠️ Invalid Advance Deduction", "Advance deduction amount cannot be negative.", "warning");
          return;
        }
        if (rawPartial > outstandingAdv) {
          swalAlert("⚠️ Advance Deduction Limit Exceeded", `Cannot deduct ${state.config.currency}${rawPartial.toFixed(2)}. The employee's total outstanding advance balance is only ${state.config.currency}${outstandingAdv.toFixed(2)}.`, "warning");
          return;
        }
        advDeductVal = rawPartial;
      }

      state.cardTab = state.cardTab || {};
      state.cardTab[empId] = "history";
      mutate(() => {
        state.salaryPayments.unshift({ id: uid(), employeeId: empId, date, amount: wageAmount, reimbursedAmount, paidBy, note, deductedAdvances: advDeductVal, deductedHeld: deductHeld ? outstandingHeld : 0, deductedAbsences: deductAtt ? absenceDeductionVal : 0 });
        if (advDeductVal > 0) {
          let rem = advDeductVal;
          const empAdv = state.advances
            .filter(a => a.employeeId === empId && !a.settled)
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
        if (includeReimb) state.expenses = state.expenses.map(x => (x.payerEmployeeId === empId && !x.settled ? { ...x, settled: true, settledBy: paidBy } : x));
        if (deductHeld) {
          state.income.forEach(sale => {
            if (sale.payments) {
              sale.payments.forEach(p => { if (p.receivedByEmployeeId === empId) p.settled = true; });
            } else if (sale.receivedByEmployeeId === empId) {
              sale.settled = true;
            }
          });
        }
        if (state.attendanceLogs) {
          state.attendanceLogs = state.attendanceLogs.map(a => (a.employeeId === empId && !a.settled ? { ...a, settled: true } : a));
        }
      });
      toast("🎉 Payment recorded successfully!");
      return;
    }
    if (act === "add-vendor") {
      const name = document.getElementById("new-vendor-name").value.trim();
      const phone = document.getElementById("new-vendor-phone")?.value.trim() || "";
      const note = document.getElementById("new-vendor-note").value.trim();
      if (!name) return;
      mutate(() => { state.config.vendors.push({ id: uid(), name, phone, note }); });
      toast("Vendor profile created!");
      return;
    }
    if (act === "edit-vendor") {
      showEditVendorModal(el.dataset.id);
      return;
    }
    if (act === "delete-vendor") {
      const id = el.dataset.id;
      const confirmDelete = await swalConfirm("Delete Vendor?", "Are you sure you want to delete this vendor?");
      if (!confirmDelete) return;
      mutate(() => {
        state.config.vendors = state.config.vendors.filter(v => v.id !== id);
        state.expenses = state.expenses.map(x => x.vendorId === id ? { ...x, vendorId: undefined } : x);
        state.vendorPayments = state.vendorPayments.filter(x => x.vendorId !== id);
        if (state.expandedVendor === id) state.expandedVendor = null;
      });
      return;
    }
    if (act === "toggle-vendor") {
      const id = el.dataset.id;
      state.expandedVendor = state.expandedVendor === id ? null : id;
      render();
      return;
    }
    if (act === "add-purchase") {
      const vendorId = el.dataset.vendor;
      const date = document.getElementById(`vp-date-${vendorId}`).value || today();
      const description = document.getElementById(`vp-desc-${vendorId}`).value.trim();
      const amount = Number(document.getElementById(`vp-amount-${vendorId}`).value);
      const paymentVal = document.getElementById(`vp-payment-${vendorId}`).value;
      if (!description) {
        swalAlert("⚠️ Missing Description", "Please enter a purchase description.", "warning");
        return;
      }
      if (!amount || isNaN(amount) || amount <= 0) {
        swalAlert("⚠️ Invalid Amount", "Please enter a valid purchase amount greater than zero.", "warning");
        return;
      }
      const entry = { id: uid(), date, description, amount, vendorId };
      if (paymentVal === "credit") { entry.onCredit = true; }
      else { const [, partnerId] = paymentVal.split(":"); entry.paidBy = partnerId; entry.onCredit = false; }
      state.cardTab = state.cardTab || {};
      state.cardTab[vendorId] = "history";
      mutate(() => state.expenses.unshift(entry));
      toast("📦 Purchase logged successfully!");
      return;
    }
    if (act === "pay-vendor") {
      const vendorId = el.dataset.vendor;
      const outstanding = vendorOutstanding(vendorId);
      const date = document.getElementById(`vpay-date-${vendorId}`).value || today();
      const amountInput = document.getElementById(`vpay-amount-${vendorId}`).value;
      const amount = amountInput ? Number(amountInput) : outstanding;
      const paidBy = document.getElementById(`vpay-paidby-${vendorId}`).value;
      const note = document.getElementById(`vpay-note-${vendorId}`).value.trim();
      if (!amount || isNaN(amount) || amount <= 0) {
        swalAlert("⚠️ Invalid Payment Amount", "Please enter a valid vendor payment amount greater than zero.", "warning");
        return;
      }
      if (amount > outstanding && outstanding > 0) {
        swalAlert("⚠️ Exceeds Outstanding Balance", `Payment amount (${state.config.currency}${amount.toFixed(2)}) exceeds the vendor's total outstanding balance of ${state.config.currency}${outstanding.toFixed(2)}.`, "warning");
        return;
      }
      state.cardTab = state.cardTab || {};
      state.cardTab[vendorId] = "history";
      mutate(() => state.vendorPayments.unshift({ id: uid(), vendorId, date, amount, paidBy, note }));
      toast("💰 Vendor payment recorded!");
      return;
    }
  });

  app.addEventListener("input", (e) => {
    const el = e.target.closest("[data-act]");
    if (!el) return;
    const act = el.dataset.act;
    if (act === "search-income") {
      state.incomeSearch = el.value;
      render();
    } else if (act === "search-expense") {
      state.expenseSearch = el.value;
      render();
    }
  });

  app.addEventListener("submit", async (e) => {
    if (e.target && e.target.id === "passcode-form") {
      e.preventDefault();
      const cur = document.getElementById("cur-passcode").value;
      const newP = document.getElementById("new-passcode").value;
      const msgEl = document.getElementById("pass-msg");
      msgEl.innerText = "Updating passcode...";
      msgEl.style.color = "var(--ink)";
      try {
        const res = await apiCall("update_passcode", { current_passcode: cur, new_passcode: newP });
        if (res.success) {
          msgEl.innerText = "";
          swalAlert("Passcode Updated!", "Your new passcode has been saved successfully.", "success");
          document.getElementById("passcode-form").reset();
        } else {
          msgEl.innerText = "";
          swalAlert("Passcode Error", res.error || "Failed to update passcode.", "error");
        }
      } catch (err) {
        msgEl.innerText = "";
        swalAlert("Server Error", "Server error updating passcode.", "error");
      }
    }
  });

  app.addEventListener("change", (e) => {
    const el = e.target.closest("[data-act]");
    if (el) {
      const act = el.dataset.act;
      if (act === "partner-name") {
        const id = el.dataset.id;
        mutate(() => { state.config.partners = state.config.partners.map(p => p.id === id ? { ...p, name: el.value } : p); });
      } else if (act === "partner-ratio") {
        const id = el.dataset.id;
        mutate(() => { state.config.partners = state.config.partners.map(p => p.id === id ? { ...p, ratio: Number(el.value) } : p); });
      } else if (act === "partner-cap") {
        const id = el.dataset.id;
        mutate(() => { state.config.partners = state.config.partners.map(p => p.id === id ? { ...p, openingCapital: Number(el.value) } : p); });
      } else if (act === "currency") {
        mutate(() => { state.config.currency = el.value; });
      } else if (act === "toggle-deduct") {
        const empId = el.dataset.emp;
        state.deductAdvance[empId] = el.checked;
        render();
      } else if (act === "toggle-reimb") {
        const empId = el.dataset.emp;
        state.includeReimbursement[empId] = el.checked;
        render();
      } else if (act === "toggle-held") {
        const empId = el.dataset.emp;
        state.deductHeldIncome[empId] = el.checked;
        render();
      } else if (act === "toggle-attendance") {
        const empId = el.dataset.emp;
        state.deductAttendance[empId] = el.checked;
        render();
      } else if (act === "inc-cust-select") {
        const fields = document.getElementById("inc-new-cust-fields");
        if (fields) fields.style.display = el.value === "__new__" ? "block" : "none";
      } else if (act === "set-report-range") {
        state.reportRange = el.value;
        render();
      } else if (act === "set-report-start") {
        state.reportStart = el.value;
        render();
      } else if (act === "set-report-end") {
        state.reportEnd = el.value;
        render();
      }
    }

    if (e.target && e.target.id === "import-file-input") {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = async (evt) => {
        try {
          const d = JSON.parse(evt.target.result);
          if (d && (d.config || d.expenses || d.income)) {
            const confirmImport = await swalConfirm("Overwrite Ledger Data?", "Importing this backup will overwrite your current live ledger data.");
            if (confirmImport) {
              mutate(() => {
                if (d.config) state.config = d.config;
                if (d.expenses) state.expenses = d.expenses;
                if (d.income) state.income = d.income;
                if (d.workLogs) state.workLogs = d.workLogs;
                if (d.salaryPayments) state.salaryPayments = d.salaryPayments;
                if (d.advances) state.advances = d.advances;
                if (d.vendorPayments) state.vendorPayments = d.vendorPayments;
                if (d.attendanceLogs) state.attendanceLogs = d.attendanceLogs;
              });
              swalAlert("Import Successful!", "Backup restored successfully.", "success");
            }
          } else {
            swalAlert("Invalid File", "Invalid backup file structure.", "error");
          }
        } catch (err) {
          swalAlert("Parse Error", "Error reading or parsing JSON file.", "error");
        }
      };
      reader.readAsText(file);
    }
  });

  app.addEventListener("input", (e) => {
    const el = e.target.closest("[data-act]");
    if (el) {
      const act = el.dataset.act;
      if (act === "search-customer") {
        state.custSearch = el.value;
        render();
        const input = document.getElementById("cust-search");
        if (input) { input.focus(); input.setSelectionRange(input.value.length, input.value.length); }
      } else if (act === "search-income") {
        state.incomeSearch = el.value;
        render();
        const input = document.getElementById("inc-search");
        if (input) { input.focus(); input.setSelectionRange(input.value.length, input.value.length); }
      } else if (act === "search-expense") {
        state.expenseSearch = el.value;
        render();
        const input = document.getElementById("exp-search");
        if (input) { input.focus(); input.setSelectionRange(input.value.length, input.value.length); }
      }
    }
  });

  checkAuthAndLoad();
});
</script>
</body>
</html>
