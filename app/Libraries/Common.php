<?php

namespace App\Libraries;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Common
{
    // Clear XSS
    public static function clearXssInput($input): array
    {
        return array_map(static function ($value) {
            return self::clearXSS($value);
        }, $input);
    }

    /**
     * Remove XSS vulnerabilities from string
     */
    public static function clearXSS($string): string
    {
        if (!is_string($string)) {
            return $string;
        }
        $string = nl2br($string);
        $string = trim(strip_tags($string));
        return self::removeScripts($string);
    }

    /**
     * Remove scripts and styles from HTML content
     */
    public static function removeScripts($content): string
    {
        if (!is_string($content)) {
            return $content;
        }

        $patterns = [
            '@<script[^>]*?>.*?</script>@si',
            '@<style[^>]*?>.*?</style>@siU',
            '@<[/\s]*(link|meta|iframe|frame|form|input|button|textarea|style|script|object|embed|applet)[^>]*?>@i',
            '/(<link[^>]+rel="[^"]*stylesheet"[^>]*>)|' .
            '<script[^>]*>.*?<\/script>|' .
            '<style[^>]*>.*?<\/style>|' .
            '<!--.*?-->/is'
        ];

        return preg_replace($patterns, '', $content);
    }

    /**
     * Sanitize input by removing XSS and trimming
     */
    public static function sanitizeInput($input): array|string|null
    {
        if (is_array($input)) {
            return array_map(static fn($value) => self::sanitizeInput($value), $input);
        }

        return self::clearXSS($input);
    }

    /**
     * Generate a random string of specified length
     */
    public static function randomString(int $length = 10): string
    {
        return Str::random($length);
    }

    /**
     * Format date to day-month-year format (e.g., 04-06-2025)
     *
     * @param mixed $date Date string, Carbon instance, or null
     * @param string $format Default format is 'd-m-Y' (day-month-year)
     * @return string Formatted date or empty string if invalid
     */
    public static function formatDate(mixed $date, string $format = 'd-m-Y'): string
    {
        if (!$date) {
            return '';
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            return $date->format($format);
        } catch (Exception $e) {
            Log::error('Date formatting error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Upload file to storage
     */
    public static function uploadFile(UploadedFile $file, string $path = 'uploads', string $disk = 'public'): ?string
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(10) . '.' . $extension;
            return $file->storeAs($path, $filename, $disk);
        } catch (Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file from storage
     */
    public static function deleteFile(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    /**
     * Get file URL from storage
     */
    public static function getFileUrl(?string $path, string $disk = 'public'): ?string
    {
        if (!$path) {
            return null;
        }
        return Storage::disk($disk)->url($path);
    }

    /**
     * Convert bytes to human-readable format
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Generate a unique token
     */
    public static function generateToken(int $length = 32): string
    {
        return hash('sha256', Str::random($length) . time());
    }

    /**
     * Check if string is JSON
     */
    public static function isJson($string): bool
    {
        if (!is_string($string)) {
            return false;
        }

        return json_validate($string, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Convert array to CSV and force download
     */
    public static function downloadCsv(array $data, string $filename = 'export.csv'): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Use Laravel's streamDownload response
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'wb');

            if (!empty($data)) {
                // Ensure $data[0] exists before calling array_keys
                if (isset($data[0]) && is_array($data[0])) {
                    fputcsv($handle, array_keys($data[0]));
                }

                foreach ($data as $row) {
                    if (is_array($row)) { // Ensure $row is an array for fputcsv
                        fputcsv($handle, $row);
                    }
                }
            }
            fclose($handle);
        }, $filename, $headers);
    }

    /**
     * Paginate a collection
     */
    public static function paginate(Collection $items, int $perPage = 15, ?int $page = null, array $options = []): LengthAwarePaginator
    {
        $page = $page ?? (Paginator::resolveCurrentPage() ?? 1);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }

    /**
     * Check if string is a valid URL
     * @param string|null $url
     * @return bool
     */
    public static function isValidUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Format price with currency symbol
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public static function formatPrice(float $amount, string $currency = '$'): string
    {
        return $currency . number_format($amount, 2);
    }

    /**
     * Log user activity
     * @param string $action
     * @param array $data
     * @return void
     */
    public static function logActivity(string $action, array $data = []): void
    {
        $user = auth()->user();

        Log::info('Activity Log:', [
            'user_id' => $user?->id,
            'action' => $action,
            'data' => $data,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Generate a unique slug
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function generateSlug(string $string, string $separator = '-'): string
    {
        return Str::slug($string, $separator);
    }
}
