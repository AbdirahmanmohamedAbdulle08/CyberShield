<?php
namespace App\Helpers;

/**
 * CyberShield Safe CSV Importer & Exporter
 * Implements defenses against CSV / Formula Injection (CWE-1236)
 */
class CsvHelper {
    /**
     * Sanitize field value to prevent CSV Formula Injection in Excel/Calc
     * Neutralizes characters: =, +, -, @, \t, \r
     */
    public static function sanitizeForCsv($value): string {
        $str = (string)($value ?? '');
        // If string begins with dangerous formula triggers, prepend a single quote
        if (preg_match('/^[=\+\-@\t\r]/', $str)) {
            return "'" . $str;
        }
        return $str;
    }

    /**
     * Send HTTP headers and stream CSV download safely
     */
    public static function download(string $filename, array $headers, array $rows): void {
        if (headers_sent()) {
            throw new \RuntimeException("Headers already sent, cannot generate CSV download.");
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename) . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // Add UTF-8 BOM for accurate character display in Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write sanitized headers
        $sanitizedHeaders = array_map([self::class, 'sanitizeForCsv'], $headers);
        fputcsv($output, $sanitizedHeaders);

        // Write sanitized rows
        foreach ($rows as $row) {
            $sanitizedRow = array_map([self::class, 'sanitizeForCsv'], array_values($row));
            fputcsv($output, $sanitizedRow);
        }

        fclose($output);
        exit;
    }

    /**
     * Parse and validate uploaded CSV for participant imports
     */
    public static function parseParticipantsCsv(string $filePath): array {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['success' => false, 'error' => 'Uploaded file could not be read.'];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['success' => false, 'error' => 'Failed to open uploaded CSV.'];
        }

        $header = fgetcsv($handle, 2048, ',');
        if (!$header) {
            fclose($handle);
            return ['success' => false, 'error' => 'CSV file is empty.'];
        }

        // Normalize header names (lowercase, trim)
        $header = array_map(function($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        $nameIndex = array_search('name', $header);
        if ($nameIndex === false) $nameIndex = array_search('display_name', $header);

        $emailIndex = array_search('email', $header);

        $idIndex = array_search('student_id', $header);
        if ($idIndex === false) $idIndex = array_search('institutional_id', $header);

        if ($nameIndex === false || $emailIndex === false) {
            fclose($handle);
            return [
                'success' => false,
                'error'   => 'CSV must contain at least "name" (or "display_name") and "email" columns.',
            ];
        }

        $participants = [];
        $rowNum = 1;
        $invalidRows = [];

        while (($row = fgetcsv($handle, 2048, ',')) !== false) {
            $rowNum++;
            if (empty(array_filter($row))) {
                continue; // Skip empty rows
            }

            $name = trim($row[$nameIndex] ?? '');
            $email = trim($row[$emailIndex] ?? '');
            $institutionalId = ($idIndex !== false && isset($row[$idIndex])) ? trim($row[$idIndex]) : null;

            if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalidRows[] = "Row {$rowNum}: invalid name or email ({$email})";
                continue;
            }

            // Generate pseudonymous token & display pseudonym
            $token = 'tok_' . bin2hex(random_bytes(16));
            $pseudonym = 'Student-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

            $participants[] = [
                'participant_token' => $token,
                'display_name'      => substr($name, 0, 100),
                'pseudonym'         => $pseudonym,
                'email'             => substr($email, 0, 120),
                'institutional_id'  => $institutionalId ? substr($institutionalId, 0, 50) : null,
                'consent_status'    => 'consented',
            ];
        }

        fclose($handle);

        return [
            'success'      => true,
            'participants' => $participants,
            'invalid_rows' => $invalidRows,
            'total_parsed' => count($participants),
        ];
    }
}
