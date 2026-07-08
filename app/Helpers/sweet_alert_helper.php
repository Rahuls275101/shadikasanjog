<?php

/**
 * FILE 1:
 * app/Helpers/sweet_alert_helper.php
 *
 * ✅ Is helper ko alag file me rakho (controller ke neeche mat rakho)
 * ✅ Controller me: helper('sweet_alert'); call karo
 */

if (!function_exists('showSweetAlert')) {

    /**
     * showSweetAlert (CI4)
     * - $redirect diya to OK/close ke baad redirect
     * - custom buttons (confirm/cancel/deny) + multiple redirect ke liye:
     *   'redirects' => ['confirm' => url1, 'cancel' => url2, 'deny' => url3]
     * - 'timer' do to auto-close + auto redirect bhi .then() se ho jayega
     */
    function showSweetAlert(
        string $title,
        string $message,
        string $type = 'info',
        ?string $redirect = null,
        array $options = []
    ): string {

        $allowedTypes = ['success', 'error', 'warning', 'info', 'question'];
        $icon = in_array($type, $allowedTypes, true) ? $type : 'info';

        // Multi-redirect map (confirm/cancel/deny)
        $redirects = $options['redirects'] ?? [];
        unset($options['redirects']);

        // Defaults
        $defaultOptions = [
            'title' => $title,
            'html'  => $message,
            'icon'  => $icon,

            'confirmButtonText' => 'OK',
            'showConfirmButton' => true,

            'allowOutsideClick' => false,
            'allowEscapeKey'    => false,
        ];

        // Merge
        $opts = array_merge($defaultOptions, $options);

        // Remove JS function keys if someone passed them (json_encode will break them)
        unset($opts['willClose'], $opts['didClose'], $opts['preConfirm']);

        $jsonOptions = json_encode($opts, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Build redirect JS
        $redirectJs = "";

        if (!empty($redirects) && is_array($redirects)) {
            $confirmUrl = isset($redirects['confirm']) ? addslashes($redirects['confirm']) : '';
            $cancelUrl  = isset($redirects['cancel'])  ? addslashes($redirects['cancel'])  : '';
            $denyUrl    = isset($redirects['deny'])    ? addslashes($redirects['deny'])    : '';

            $redirectJs = "
            .then(function(result){
                if (result.isConfirmed && '{$confirmUrl}') {
                    window.location.href = '{$confirmUrl}';
                    return;
                }
                if (result.isDenied && '{$denyUrl}') {
                    window.location.href = '{$denyUrl}';
                    return;
                }
                if (result.dismiss === Swal.DismissReason.cancel && '{$cancelUrl}') {
                    window.location.href = '{$cancelUrl}';
                    return;
                }
            });";
        } elseif (!empty($redirect)) {
            $redirectUrl = addslashes($redirect);
            $redirectJs = "
            .then(function(){
                window.location.href = '{$redirectUrl}';
            });";
        } else {
            $redirectJs = ";";
        }

        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $safeTitle . '</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<script>
    Swal.fire(' . $jsonOptions . ')' . $redirectJs . '
</script>
</body>
</html>';
    }
}
