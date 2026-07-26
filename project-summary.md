# The Ledger — Project Summary

A single-file web app for a two-partner leather shoe business, built to handle shared expenses, income, payroll, advances, vendor credit — and split everything fairly between partners by a fixed ratio.

**File:** `partner-ledger.html` — open it directly, no install needed. Works on mobile and desktop.
**Storage:** Data is saved automatically and shared between both partners (shared cloud storage tied to this file).

---

## 1. Partner split (Overview tab)

- Two partners, each with a **ratio** (default 2:1).
- The app tracks total income, total expenses, and **net profit** (income − expenses).
- Each partner's **fair share** of net profit is calculated from the ratio.
- Compares fair share against what each partner actually paid out and received, and tells you exactly **who owes whom, and how much**, to even things out.
- A visual split-scale bar shows the ratio at a glance.

## 2. Income

- Log each sale: item, quantity, total amount, date, note/customer.
- Choose who **received the payment** — either partner, or an **employee** if they collected cash directly from a customer.
- Income collected by an employee shows as cash they're "holding" until it's settled (see Employees below).

## 3. Expenses

- Log any business expense: description, amount, date.
- Choose who **paid** — either partner, or an **employee** if they covered it out of pocket.
- Employee-paid expenses show as "awaiting reimbursement" until settled.

## 4. Employees

Two salary types, set per employee:

- **Weekly salary** — a fixed weekly amount.
- **Work-based (piece rate)** — supports **multiple work items with different prices** (e.g. Cutting $1.50/pc, Stitching $2/pc, Assembly $3/pc). You log work by item and quantity, and it's totaled automatically.

**Advances (money given mid-week):**
- Log any cash given to an employee ahead of payday.
- On payday, a checkbox lets you **deduct it automatically** from what's due — or leave it unchecked if they asked to pay it back "next time," and it carries forward untouched.

**Expenses they've covered (reimbursement):**
- If an employee paid a business expense from their own pocket, it shows on their card as owed to them.
- On payday, a checkbox **adds it on top of their wage** in the same payment — or you can leave it out and settle it later.

**Cash they've collected from customers:**
- If an employee received a sale payment directly, it shows as money they're holding.
- On payday, a checkbox **deducts it from what they're paid**, since they already have that cash.

All of this is visible per-employee with running totals, outstanding badges, and a full history log (work done, advances, reimbursements, payments).

## 5. Vendors

- Add suppliers (e.g. your leather supplier).
- Log purchases as either **paid immediately** by a partner, or **on credit** (pay later).
- Credit purchases build an outstanding balance per vendor.
- Pay vendors anytime — in full or partially — and the balance updates automatically.
- Vendor debt paid by a partner counts correctly toward that partner's side of the ratio split.

## 6. Settings

- Edit partner names and ratio.
- Set the currency symbol.

---

## How the math stays fair

Everything — income, expenses, salaries, advances, reimbursements, vendor payments — feeds into one running calculation:

> **Fair share of profit (by ratio) vs. actual cash each partner put in or took out**

The difference is the exact amount owed between partners at any moment, shown clearly on the Overview tab.

---

## Notes

- Built as a single HTML file (no app install) so it previews reliably on mobile.
- Data is shared — both partners see the same live numbers.
- Nothing here is sent anywhere else; it's stored privately for this app only.
