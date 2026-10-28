<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($docTitle) ? esc($docTitle) : 'Document' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <style>
        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            background: #ffffff;
            color: #111827;
            font-size: 10pt;
            line-height: 1.4;
        }
        .print-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
        }
        .header-box {
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .doc-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .table-print th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 8.5pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .table-print td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 9pt;
            vertical-align: top;
        }
        .font-mono {
            font-family: "Courier New", Courier, monospace;
        }
        .stamp-box {
            border: 1px dashed #94a3b8;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 8pt;
            text-transform: uppercase;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .print-container {
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="no-print bg-light border-bottom p-3 text-center">
    <button class="btn btn-primary btn-sm me-2" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Document</button>
    <button class="btn btn-secondary btn-sm" onclick="window.close()"><i class="bi bi-x-circle me-1"></i> Close</button>
</div>

<div class="print-container">
    <?= $mainContent ?>
</div>

</body>
</html>
