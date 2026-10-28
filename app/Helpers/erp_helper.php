<?php

if (!function_exists('format_inr')) {
    /**
     * Format number into Indian Rupee format (e.g., ₹ 12,34,567.89)
     */
    function format_inr($number, $show_symbol = true, $decimals = 2)
    {
        if ($number === null || $number === '') {
            return $show_symbol ? '₹ 0.00' : '0.00';
        }

        $num = (float)$number;
        $is_negative = $num < 0;
        $num = abs($num);

        $parts = explode('.', number_format($num, $decimals, '.', ''));
        $int_part = $parts[0];
        $dec_part = isset($parts[1]) ? '.' . $parts[1] : '';

        // Indian numbering system: last 3 digits, then pairs of 2 digits
        if (strlen($int_part) > 3) {
            $last3 = substr($int_part, -3);
            $rest = substr($int_part, 0, -3);
            $rest_chunks = [];
            while (strlen($rest) > 2) {
                $rest_chunks[] = substr($rest, -2);
                $rest = substr($rest, 0, -2);
            }
            if (strlen($rest) > 0) {
                $rest_chunks[] = $rest;
            }
            $formatted_int = implode(',', array_reverse($rest_chunks)) . ',' . $last3;
        } else {
            $formatted_int = $int_part;
        }

        $result = ($is_negative ? '-' : '') . $formatted_int . $dec_part;
        return $show_symbol ? '₹ ' . $result : $result;
    }
}

if (!function_exists('number_to_words_indian')) {
    /**
     * Convert number to words in Indian numbering style (Crores, Lakhs, Thousands)
     */
    function number_to_words_indian($number)
    {
        $number = (float)$number;
        if ($number == 0) {
            return 'Zero Rupees Only';
        }

        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];

        $convert_group = function($num) use ($words) {
            $str = '';
            if ($num >= 100) {
                $str .= $words[(int)($num / 100)] . ' Hundred ';
                $num %= 100;
            }
            if ($num > 0) {
                if ($num < 20) {
                    $str .= $words[$num] . ' ';
                } else {
                    $str .= $words[(int)($num / 10) * 10] . ' ';
                    if ($num % 10 > 0) {
                        $str .= $words[$num % 10] . ' ';
                    }
                }
            }
            return trim($str);
        };

        $rupees = (int)$number;
        $paise = (int)round(($number - $rupees) * 100);

        $out = '';

        // Crores (>= 1,00,00,000)
        if ($rupees >= 10000000) {
            $crores = (int)($rupees / 10000000);
            $out .= $convert_group($crores) . ' Crore ';
            $rupees %= 10000000;
        }

        // Lakhs (>= 1,00,000)
        if ($rupees >= 100000) {
            $lakhs = (int)($rupees / 100000);
            $out .= $convert_group($lakhs) . ' Lakh ';
            $rupees %= 100000;
        }

        // Thousands (>= 1,000)
        if ($rupees >= 1000) {
            $thousands = (int)($rupees / 1000);
            $out .= $convert_group($thousands) . ' Thousand ';
            $rupees %= 1000;
        }

        // Remaining hundreds, tens, units
        if ($rupees > 0) {
            $out .= $convert_group($rupees) . ' ';
        }

        $out = trim($out);
        if ($out === '') {
            $out = 'Zero';
        }

        $res = 'Rupees ' . $out;
        if ($paise > 0) {
            $res .= ' and ' . $convert_group($paise) . ' Paise';
        }
        $res .= ' Only';

        return $res;
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd M Y')
    {
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return '-';
        }
        $timestamp = strtotime($date);
        return $timestamp ? date($format, $timestamp) : '-';
    }
}

if (!function_exists('status_badge')) {
    function status_badge($status)
    {
        $status_lower = strtolower(trim((string)$status));
        $color = 'secondary';
        $icon = 'bi-circle';

        switch ($status_lower) {
            case 'confirmed':
            case 'converted':
            case 'accepted':
            case 'approved':
            case 'completed':
            case 'delivered':
            case 'cleared':
            case 'paid':
                $color = 'success';
                $icon = 'bi-check-circle-fill';
                break;

            case 'in_production':
            case 'in_progress':
            case 'in_transit':
            case 'assembly':
            case 'welding':
            case 'cutting':
            case 'testing':
            case 'painting':
            case 'material_issued':
            case 'partially_dispatched':
            case 'partially_paid':
            case 'partial':
            case 'partially_received':
                $color = 'info';
                $icon = 'bi-gear-fill';
                break;

            case 'sent':
            case 'quoted':
            case 'new':
            case 'in_review':
            case 'planned':
            case 'acknowledged':
            case 'inspected':
            case 'received':
            case 'ready':
                $color = 'primary';
                $icon = 'bi-arrow-right-circle-fill';
                break;

            case 'draft':
            case 'pending':
            case 'on_hold':
            case 'low':
                $color = 'secondary';
                $icon = 'bi-clock-fill';
                break;

            case 'urgent':
            case 'high':
            case 'overdue':
            case 'bounced':
                $color = 'warning text-dark';
                $icon = 'bi-exclamation-triangle-fill';
                break;

            case 'rejected':
            case 'cancelled':
            case 'lost':
            case 'returned':
                $color = 'danger';
                $icon = 'bi-x-circle-fill';
                break;
        }

        $label = ucwords(str_replace('_', ' ', $status_lower));
        return '<span class="badge bg-' . $color . '"><i class="bi ' . $icon . ' me-1"></i>' . esc($label) . '</span>';
    }
}

if (!function_exists('plant_type_badge')) {
    function plant_type_badge($type)
    {
        $type_lower = strtolower(trim((string)$type));
        $badge = 'secondary';
        $icon = 'bi-building';
        $label = ucwords(str_replace('_', ' ', $type_lower));

        if (str_contains($type_lower, 'power')) {
            $badge = 'danger';
            $icon = 'bi-lightning-charge-fill';
            $label = 'Power Plant';
        } elseif (str_contains($type_lower, 'sugar')) {
            $badge = 'success';
            $icon = 'bi-cup-hot-fill';
            $label = 'Sugar Mill';
        } elseif (str_contains($type_lower, 'cement')) {
            $badge = 'warning text-dark';
            $icon = 'bi-buildings-fill';
            $label = 'Cement Plant';
        } elseif (str_contains($type_lower, 'steel')) {
            $badge = 'dark';
            $icon = 'bi-hammer';
            $label = 'Steel Plant';
        }

        return '<span class="badge bg-' . $badge . '"><i class="bi ' . $icon . ' me-1"></i>' . esc($label) . '</span>';
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        $session = service('session');
        return $session->get('user') ?? null;
    }
}

if (!function_exists('has_role')) {
    function has_role($roles)
    {
        $user = current_user();
        if (!$user) {
            return false;
        }
        if ($user['role'] === 'admin') {
            return true;
        }
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return in_array($user['role'], $roles, true);
    }
}

if (!function_exists('company_settings')) {
    function company_settings($key = null, $default = null)
    {
        static $settings = null;
        if ($settings === null) {
            $db = \Config\Database::connect();
            $settings = $db->table('company_settings')->get()->getRowArray() ?: [
                'company_name' => 'Bharat Heavy Steel Fabricators Ltd.',
                'tagline'      => 'Quality Steel Fabrication for Power, Sugar & Cement Plants',
                'gstin'        => '27AABCB1234F1Z5',
                'pan'          => 'AABCB1234F',
                'city'         => 'Nagpur',
                'state'        => 'Maharashtra',
                'phone'        => '0712-2849100',
                'email'        => 'info@bharatsteel.com',
            ];
        }

        if ($key !== null) {
            return $settings[$key] ?? $default;
        }

        return $settings;
    }
}

if (!function_exists('log_activity')) {
    function log_activity($module, $action, $record_id = null, $description = '')
    {
        try {
            $db = \Config\Database::connect();
            $user = current_user();
            $request = service('request');

            $db->table('activity_log')->insert([
                'user_id'     => $user ? $user['id'] : null,
                'module'      => $module,
                'action'      => $action,
                'record_id'   => $record_id,
                'description' => $description,
                'ip_address'  => $request->getIPAddress(),
                'user_agent'  => substr((string)$request->getUserAgent(), 0, 250),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Non-blocking
        }
    }
}
