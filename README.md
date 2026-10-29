# Bharat Heavy Steel ERP 🏗️⚙️
### Enterprise Resource Management System for Heavy Steel Engineering & Fabrication

> **Tailored for Indian Heavy Structural Steel Fabricators supplying Power, Sugar, and Cement Plants.**  
> Built with **XAMPP, PHP 8.1+, CodeIgniter 4, MySQL, JavaScript, jQuery, Bootstrap 5, CSS & HTML**.

---

## 🏭 Domain & Plant Focus

Heavy steel fabrication is a specialized engineering sector characterized by high-tonnage structural components, stringent quality assurance (ASME, IBR, IS 2062, ASTM A516 Gr 70), strict raw-material traceability (Heat Numbers & Mill Test Certificates), multi-stage fabrication workflows, and heavy-trailer logistics.

This ERP is purpose-built to manage the end-to-end lifecycle for fabricators serving:
* ⚡ **Thermal & Hydro Power Plants**: Boiler support structures, electrostatic precipitator (ESP) casings, flue gas ducting, coal bunker hoppers, penstocks, heavy columns.
* 🍬 **Sugar Mills & Distilleries**: Cane carrier frames, juice clarifiers, vacuum pans, evaporator vessels, molasses storage tanks, structural roofing trusses.
* 🏢 **Cement Plants**: Kiln shells, clinker cooler casings, preheater towers, raw mill ducting, bag house filter bodies, bucket elevator casings.

---

## 🛠️ Complete Tech Stack

* **Server / Runtime**: Apache 2.4, PHP 8.1+ (Tested on PHP 8.2 & PHP 8.5 via XAMPP / Homebrew)
* **Backend Framework**: CodeIgniter 4.4+ (MVC architecture, PSR-4 autoloading, Query Builder, DB session handler, Auth Filter)
* **Database**: MySQL 8.0+ / MariaDB 10.4+ (InnoDB engine, foreign keys, UTF8mb4 charset)
* **Frontend UI**: Bootstrap 5.3.3, Bootstrap Icons 1.11.3 (fully bundled offline assets)
* **Interactive Libraries**: jQuery 3.7.1, DataTables 1.13.7 (with Bootstrap 5 integration), Chart.js 4.4.2, Select2
* **Taxation & Compliance**: Indian Goods & Services Tax (GST) Engine (CGST 9% + SGST 9% vs IGST 18%), HSN Code classification (73089090 for structural steel), E-Way Bill generation, Security Gate Pass (Rule 55 CGST Rules), TDS calculation (Sec 194C / 194Q).

---

## 📦 System Modules & Capabilities

```
+-----------------------------------------------------------------------------------+
|                            BHARAT HEAVY STEEL ERP                                 |
+-----------------------------------------------------------------------------------+
|  [CRM & Clients]          [Estimations & Sales]         [Procurement & Mills]     |
|  - NTPC, Adani, UltraTech - Enquiries & Tenders         - SAIL, JSPL, JSW, ESAB   |
|  - Industry sector tags   - Quotations (CGST/SGST/IGST) - Purchase Orders (POs)   |
|  - GSTIN & PAN registry   - Sales Orders (Client PO)    - Mill TC & Heat No specs |
+-----------------------------------------------------------------------------------+
|  [Yard & Inventory]       [Shop Floor Production]       [Quality & Inspection]    |
|  - Raw plates (5-100mm)   - Job Orders & Drawing Nos    - Ultrasonic (UT) testing |
|  - Heavy beams & ISMB     - Process Tracking (Stages)   - DP tests on weld roots  |
|  - Bays & Racks location  - Steel Issue Slips (Requis.) - SA 2.5 grit blasting    |
+-----------------------------------------------------------------------------------+
|  [Logistics & Dispatches] [Invoicing & Receivables]     [Compliance & Analytics]  |
|  - Trailer Consignments   - GST Tax Invoices            - Power/Sugar/Cement KPIs |
|  - E-Way Bill (12-digit)  - HSN 7308 Rate per MT        - GSTR-1 Outward Register |
|  - Rule 55 Gate Pass      - RTGS/NEFT Payment Receipts  - Steel Stock Valuation   |
+-----------------------------------------------------------------------------------+
```

### 1. Dashboard & Executive Analytics
* Real-time KPIs: Active sales orders, order book value, steel inventory tonnage, shop floor progress.
* Tonnage breakdown across Power, Sugar, and Cement industry clients.
* Monthly sales and invoicing revenue charts via Chart.js.
* Low stock alerts and recent system audit trails.

### 2. Client Management (CRM)
* Client master records categorized by industry type (`power_plant`, `sugar_mill`, `cement_plant`, `steel_plant`, etc.).
* Billing address, factory delivery site address, GSTIN, PAN, credit limit, and credit period tracking.

### 3. Approved Vendors (Steel Mills & Consumables)
* Vendor master for primary steel producers (SAIL, JSPL, Tata Steel, JSW), welding consumable manufacturers (ESAB, Ador), and paint vendors (Asian Paints PPG).
* Mill test certificate (MTC) tracking and vendor ratings.

### 4. Materials & Yard Inventory
* Heavy steel specifications: IS 2062 E250 / E350 Structural Steel, ASTM A516 Gr 70 Boiler Quality Plates, E7018 low-hydrogen welding electrodes, zinc phosphate primer.
* Physical yard tracking: Bay 1, Bay 2, Plate Yard, Consumables Store.
* Physical stock adjustment and stock transaction audit ledger (GRN, Issue to Job, Return, Physical Audit).

### 5. Enquiries & Estimations
* Tender leads, client drawing references, estimated steel tonnage, and bid due date tracking.
* Win/Loss conversion tracking.

### 6. Dynamic Quotations (With Indian GST Calculator)
* Cost estimation per MT or Lump Sum.
* Auto-detection of Intra-State (CGST 9% + SGST 9%) vs Inter-State (IGST 18%) based on client location.
* Indian number-to-words converter in JavaScript and PHP.
* Print-ready Quotation Proposal.
* One-click conversion from Quotation to confirmed Sales Order.

### 7. Sales Orders (SO)
* Client PO reference, delivery schedule, payment terms, and structural drawing references.
* Print-ready Sales Order Confirmation.

### 8. Procurement & Purchase Orders (PO)
* Direct or SO-linked raw steel procurement from primary steel mills.
* Mill Test Certificate (MTC) 3.1 / 3.2 requirements clause and payment terms.
* Print-ready Purchase Order document.

### 9. Goods Receipt Notes (GRN)
* Material inward inspection at factory gate.
* Heat Number, Mill TC verification, Challan Qty vs Received Qty vs Accepted Qty vs Rejected Qty.
* Automatic stock yard increment upon acceptance.

### 10. Shop Floor Production & Job Cards
* Work orders linked to Sales Orders and engineering drawing numbers.
* Six fabrication process stages:
  1. *Profile Cutting & Edge Preparation*
  2. *Fit-Up & Pre-Weld Assembly*
  3. *Submerged Arc & Manual Metal Arc Welding*
  4. *Non-Destructive Testing (NDT / Radiography / Ultrasonic)*
  5. *Abrasive Grit Blasting (SA 2.5) & Epoxy Painting*
  6. *Trial Assembly & Final Dimension Verification*
* Steel requisition and issue slip: deducts raw plates/sections from yard stock and logs stock movement against the job card.

### 11. Delivery Challans & Dispatch Logistics
* Heavy trailer dispatch documentation (Lorry Receipt, Transporter, Driver Phone, Vehicle Number).
* Government 12-digit E-Way Bill Number logging.
* Print-ready Delivery Challan & Security Gate Pass (issued under Rule 55 of CGST Rules, 2017).

### 12. GST Tax Invoices & Accounts Receivable
* Indian GST Tax Invoice with HSN Code 73089090 classification.
* Intra-State vs Inter-State tax split, round off, grand total in INR words.
* Outstanding receivables balance tracking and due date alerts.
* Print-ready Tax Invoice (Original for Recipient, Duplicate for Transporter, Triplicate for Supplier).

### 13. Payments & Disbursals
* **Payments Received**: Customer remittances via RTGS/NEFT/Cheque with TDS (Sec 194C / 194Q) adjustment, updating invoice paid balance in real time.
* **Payments Made**: Vendor payment vouchers to steel mills and transporters.

### 14. Plant Workforce & HR
* Employee master: Welding inspectors (ASME/CSWIP), fitters, quality assurance engineers, store officers, sales managers.
* Statutory details: Aadhaar, PAN, bank IFSC, salary structure.

### 15. Reports & Intelligence
* Industry Sector Breakdown (Power, Sugar, Cement) with order value and tonnage analytics.
* Steel Stock Yard Valuation by category.
* GSTR-1 Outward Tax Statement (taxable value, CGST, SGST, IGST).

### 16. Company Settings
* Company legal name, registered works address, GSTIN, PAN, CIN, Bank remittance details, document numbering prefixes.

---

## 🚀 Installation & Setup Guide

### Option 1: XAMPP Setup (Windows / macOS / Linux)

1. **Clone or copy the project** into your XAMPP `htdocs` directory:
   ```bash
   git clone https://github.com/imunishsoni/steel-erp.git
   cd steel-erp
   ```
   *(e.g., `C:\xampp\htdocs\steel-erp` on Windows or `/Applications/XAMPP/xamppfiles/htdocs/steel-erp` on macOS)*

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Create the Database and Import Schema**:
   * Open phpMyAdmin (`http://localhost/phpmyadmin/`).
   * Create a new database named `steel_erp` with collation `utf8mb4_unicode_ci`.
   * Import the file `database/steel_erp.sql` into `steel_erp`.
   * *Alternatively, from the command line:*
     ```bash
     mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS steel_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
     mysql -u root -p steel_erp < database/steel_erp.sql
     ```

4. **Configure Environment (`.env`)**:
   * Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   * Set your `app.baseURL` according to your XAMPP path:
     ```ini
     app.baseURL = 'http://localhost/steel-erp/public/'
     ```
   * If running through `php spark serve`, keep:
     ```ini
     app.baseURL = 'http://localhost:8080/'
     ```
   * Ensure database credentials match:
     ```ini
     database.default.hostname = localhost
     database.default.database = steel_erp
     database.default.username = root
     database.default.password =
     ```

5. **Ensure Write Permissions**:
   Make sure `writable/` is writable by the web server:
   ```bash
   chmod -R 777 writable/
   ```

6. **Access the System**:
   * Using XAMPP Apache: `http://localhost/steel-erp/public/`
   * Using PHP Spark: Run `php spark serve` and open `http://localhost:8080/`

---

## 🔐 Default Login Credentials

All accounts come pre-configured with the default password: **`Admin@123`**

| Role | Username | Email | Department |
| :--- | :--- | :--- | :--- |
| **System Administrator** | `admin` | `admin@steelerp.com` | Executive Management |
| **Sales Head** | `sales_head` | `sales@steelerp.com` | Marketing & Commercial |
| **Purchase Head** | `purchase_head` | `purchase@steelerp.com` | Procurement & Vendor Relations |
| **Store Keeper** | `store_incharge` | `store@steelerp.com` | Raw Steel Yard & Warehouse |
| **Production Head** | `prod_head` | `production@steelerp.com` | Shop Floor & Fabrication Bays |
| **Accounts Head** | `accounts_head` | `accounts@steelerp.com` | Finance & Taxation |
| **Dispatch Incharge** | `dispatch_incharge` | `dispatch@steelerp.com` | Logistics & Security Gate |

---

## 🛡️ Security & Architecture Best Practices

* **CSRF Protection**: Native CSRF token verification on all POST forms.
* **Authentication & RBAC Filter**: Global `AuthFilter` interceptor preventing unauthorized access to any module.
* **Database Session Handling**: Secure session persistence in MySQL `ci_sessions` table with automatic garbage collection.
* **Password Hashing**: Industry-standard `PASSWORD_BCRYPT` encryption.
* **Prepared Statements & Query Builder**: 100% parameter binding preventing SQL injection vulnerabilities.
* **Offline Bundled Assets**: Zero external CDN dependencies; all CSS, fonts, and JS libraries load locally from `public/assets/plugins/`.

---

## 📄 License
This project is open-source software licensed under the [MIT License](LICENSE).
