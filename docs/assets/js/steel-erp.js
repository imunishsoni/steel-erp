/**
 * STEEL ERP - Main Application JavaScript
 * Indian Heavy Steel Fabrication Management
 */

$(document).ready(function () {
    // 1. Sidebar Toggle
    $('#sidebarToggle').on('click', function (e) {
        e.preventDefault();
        $('#sidebar').toggleClass('collapsed');
    });

    // 2. Initialize DataTables
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            pageLength: 25,
            responsive: true,
            order: [],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
    }

    // 3. Confirm Delete Helper
    $(document).on('click', '.confirm-delete', function (e) {
        if (!confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    });

    // 4. Number to Indian Words in JS
    window.numberToWordsIndian = function (amount) {
        amount = parseFloat(amount) || 0;
        if (amount === 0) return 'Rupees Zero Only';

        const words = {
            0: '', 1: 'One', 2: 'Two', 3: 'Three', 4: 'Four', 5: 'Five', 6: 'Six', 7: 'Seven',
            8: 'Eight', 9: 'Nine', 10: 'Ten', 11: 'Eleven', 12: 'Twelve', 13: 'Thirteen',
            14: 'Fourteen', 15: 'Fifteen', 16: 'Sixteen', 17: 'Seventeen', 18: 'Eighteen',
            19: 'Nineteen', 20: 'Twenty', 30: 'Thirty', 40: 'Forty', 50: 'Fifty',
            60: 'Sixty', 70: 'Seventy', 80: 'Eighty', 90: 'Ninety'
        };

        function convertGroup(n) {
            let s = '';
            if (n >= 100) {
                s += words[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }
            if (n > 0) {
                if (n < 20) {
                    s += words[n] + ' ';
                } else {
                    s += words[Math.floor(n / 10) * 10] + ' ';
                    if (n % 10 > 0) s += words[n % 10] + ' ';
                }
            }
            return s.trim();
        }

        let rupees = Math.floor(amount);
        let paise = Math.round((amount - rupees) * 100);
        let out = '';

        if (rupees >= 10000000) {
            let cr = Math.floor(rupees / 10000000);
            out += convertGroup(cr) + ' Crore ';
            rupees %= 10000000;
        }
        if (rupees >= 100000) {
            let lk = Math.floor(rupees / 100000);
            out += convertGroup(lk) + ' Lakh ';
            rupees %= 100000;
        }
        if (rupees >= 1000) {
            let th = Math.floor(rupees / 1000);
            out += convertGroup(th) + ' Thousand ';
            rupees %= 1000;
        }
        if (rupees > 0) {
            out += convertGroup(rupees) + ' ';
        }

        out = out.trim() || 'Zero';
        let res = 'Rupees ' + out;
        if (paise > 0) {
            res += ' and ' + convertGroup(paise) + ' Paise';
        }
        res += ' Only';
        return res;
    };

    // 5. Dynamic Calculations for Quotations, SOs, POs, Invoices
    window.recalculateTotals = function () {
        let subtotal = 0;

        $('.item-row').each(function () {
            let $row = $(this);
            let qty = parseFloat($row.find('.item-qty').val()) || 0;
            let rate = parseFloat($row.find('.item-rate').val()) || 0;
            let amount = qty * rate;
            $row.find('.item-amount').val(amount.toFixed(2));
            $row.find('.item-amount-display').text(amount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            subtotal += amount;
        });

        $('#subtotal_input').val(subtotal.toFixed(2));
        $('#subtotal_display').text(subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

        let discount_pct = parseFloat($('#discount_pct_input').val()) || 0;
        let discount_amt = (subtotal * discount_pct) / 100;
        $('#discount_amt_input').val(discount_amt.toFixed(2));

        let taxable = subtotal - discount_amt;
        $('#taxable_amount_input').val(taxable.toFixed(2));
        $('#taxable_display').text(taxable.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

        // Check if Inter-State (IGST) or Intra-State (CGST + SGST)
        let is_igst = $('#is_igst_checkbox').is(':checked') || $('#is_igst_input').val() === '1';

        let cgst = 0, sgst = 0, igst = 0;
        if (is_igst) {
            igst = taxable * 0.18;
            $('#cgst_row').hide();
            $('#sgst_row').hide();
            $('#igst_row').show();
        } else {
            cgst = taxable * 0.09;
            sgst = taxable * 0.09;
            $('#cgst_row').show();
            $('#sgst_row').show();
            $('#igst_row').hide();
        }

        $('#cgst_amount_input').val(cgst.toFixed(2));
        $('#sgst_amount_input').val(sgst.toFixed(2));
        $('#igst_amount_input').val(igst.toFixed(2));
        $('#cgst_display').text(cgst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#sgst_display').text(sgst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#igst_display').text(igst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

        let total_tax = cgst + sgst + igst;
        $('#total_tax_input').val(total_tax.toFixed(2));

        let freight = parseFloat($('#freight_input').val()) || 0;
        let grand_total = taxable + total_tax + freight;

        $('#grand_total_input').val(grand_total.toFixed(2));
        $('#grand_total_display').text(grand_total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

        let words = window.numberToWordsIndian(grand_total);
        $('#amount_in_words_input').val(words);
        $('#amount_in_words_display').text(words);
    };

    // Events on dynamic form
    $(document).on('input change', '.item-qty, .item-rate, #discount_pct_input, #freight_input, #is_igst_checkbox', function () {
        recalculateTotals();
    });

    // Add Row
    $('#addRowBtn').on('click', function () {
        let rowCount = $('#itemsTable tbody tr').length + 1;
        let template = $('#rowTemplate').html();
        if (template) {
            let newRow = template.replace(/{INDEX}/g, rowCount);
            $('#itemsTable tbody').append(newRow);
            recalculateTotals();
        }
    });

    // Remove Row
    $(document).on('click', '.remove-row-btn', function () {
        if ($('#itemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            // Re-index sl_no
            $('#itemsTable tbody tr').each(function (idx) {
                $(this).find('.sl-no').text(idx + 1);
            });
            recalculateTotals();
        } else {
            alert('At least one item is required.');
        }
    });

    // Material Select Auto-fill description, grade, unit, rate
    $(document).on('change', '.material-select', function () {
        let $row = $(this).closest('tr');
        let $opt = $(this).find('option:selected');
        let code = $opt.data('code') || '';
        let spec = $opt.data('spec') || '';
        let unit = $opt.data('unit') || 'kg';
        let rate = $opt.data('rate') || 0;
        let hsn = $opt.data('hsn') || '73089090';

        if (code) {
            $row.find('.item-spec').val(spec);
            $row.find('.item-unit').val(unit);
            $row.find('.item-hsn').val(hsn);
            if (!$row.find('.item-rate').val() || $row.find('.item-rate').val() == 0) {
                $row.find('.item-rate').val(rate);
            }
            recalculateTotals();
        }
    });

    // Auto State Check (Intra vs Inter state for GST)
    $(document).on('change', '#client_select', function () {
        let state = $(this).find('option:selected').data('state') || '';
        let companyState = ($('#company_state').val() || 'Rajasthan').toLowerCase();
        let clientState = state.toLowerCase();

        let isInterState = (clientState && clientState !== companyState);
        $('#is_igst_checkbox').prop('checked', isInterState);
        $('#is_igst_input').val(isInterState ? '1' : '0');
        recalculateTotals();
    });

    // Run initial calculations if form present
    if ($('#itemsTable').length > 0) {
        recalculateTotals();
    }
});
