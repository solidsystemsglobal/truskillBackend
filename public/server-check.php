<?php

$versionReqs = array(
    'php' => '8.0',
    'mcrypt' => false,
    'openssl' => true,
    'pdo' => true,
    'mbstring' => true,
    'tokenizer' => true,
    'xml' => true,
    'ctype' => true,
    'json' => true,
    'bcmath' => false,
    'gd' => true,
    'mod_rewrite' => true,
    'Redis' => true,
    'fileInfo' => true,
    'obs' => ''
);

$strOk = '<i class="fa fa-check icon-ok"></i>';
$strFail = '<i class="fa fa-times icon-remove"></i>';
$strUnknown = '<i class="fa fa-question"></i>';

$requirements = array();

// PHP Version
$requirements['php_version'] = version_compare(PHP_VERSION, $versionReqs['php'], '>=');

// OpenSSL PHP Extension
$requirements['openssl_enabled'] = extension_loaded('openssl');

// PDO PHP Extension
$requirements['pdo_enabled'] = defined('PDO::ATTR_DRIVER_NAME');

// Mbstring PHP Extension
$requirements['mbstring_enabled'] = extension_loaded('mbstring');

// Tokenizer PHP Extension
$requirements['tokenizer_enabled'] = extension_loaded('tokenizer');

// XML PHP Extension
$requirements['xml_enabled'] = extension_loaded('xml');

// CTYPE PHP Extension
$requirements['ctype_enabled'] = extension_loaded('ctype');

// JSON PHP Extension
$requirements['json_enabled'] = extension_loaded('json');

// Mcrypt PHP Extension
$requirements['mcrypt_enabled'] = extension_loaded('mcrypt_encrypt');

// BCMath PHP Extension
$requirements['bcmath_enabled'] = extension_loaded('bcmath');

// GD PHP Extension
$requirements['gd_enabled'] = extension_loaded('gd');

// Redis PHP Extension
$requirements['redis'] = extension_loaded('redis');

// Fileinfo PHP Extension
$requirements['fileInfo'] = extension_loaded('fileInfo');

// Apache mod_rewrite
$requirements['mod_rewrite_enabled'] = null;

if (function_exists('apache_get_modules')) {
    $requirements['mod_rewrite_enabled'] = in_array('mod_rewrite', apache_get_modules());
}

$magicQuotesGpcIni = ini_get('magic_quotes_gpc');
$registerGlobalsIni = ini_get('register_globals');
$sessionAutoStartIni = ini_get('session.auto_start');
$mbstringFuncOverloadIni = ini_get('mbstring.func_overload');
$allowUrlFopen = ini_get('allow_url_fopen');

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Requirements &dash; PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }

        .wrapper {
            margin: .625rem auto;
            max-width: calc(100vw - 1.25rem);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper > table {
            text-align: left;
            border-collapse: collapse;
        }

        .wrapper > table > thead {
            text-align: center;
            font-size: 1.5em;
        }

        .wrapper > table > thead th {
            padding-bottom: .625rem;
        }

        .wrapper > table > tbody td {
            border: 1px groove;
            padding: .3125rem;
        }

        .wrapper > table > tbody td:first-child {
            padding-right: .625rem;
            font-weight: 700;
        }

        .icon-ok {
            color: #27ae60;
        }

        .icon-remove {
            color: #c0392b;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <table>
        <thead>
            <tr>
                <th colspan="2">Server Requirements</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PHP >= <?php echo $versionReqs['php']; ?></td>
                <td><?php echo ($requirements['php_version'] ? $strOk : $strFail) . ' ' . PHP_VERSION; ?></td>
            </tr>

            <?php if ($versionReqs['openssl']) : ?>
                <tr>
                    <td>OpenSSL PHP Extension</td>
                    <td><?php echo $requirements['openssl_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($versionReqs['pdo']) : ?>
                <tr>
                    <td>PDO PHP Extension</td>
                    <td><?php echo $requirements['pdo_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if ($versionReqs['mbstring']) : ?>
                <tr>
                    <td>Mbstring PHP Extension</td>
                    <td><?php echo $requirements['mbstring_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if ($versionReqs['tokenizer']) : ?>
                <tr>
                    <td>Tokenizer PHP Extension</td>
                    <td><?php echo $requirements['tokenizer_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>


            <?php if ($versionReqs['xml']) : ?>
                <tr>
                    <td>XML PHP Extension</td>
                    <td><?php echo $requirements['xml_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if ($versionReqs['ctype']) : ?>
                <tr>
                    <td>CTYPE PHP Extension</td>
                    <td><?php echo $requirements['ctype_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if ($versionReqs['json']) : ?>
                <tr>
                    <td>JSON PHP Extension</td>
                    <td><?php echo $requirements['json_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if ($versionReqs['mcrypt']) : ?>
                <tr>
                    <td>Mcrypt PHP Extension</td>
                    <td><?php echo $requirements['mcrypt_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if (isset($versionReqs['bcmath']) && $versionReqs['bcmath']) : ?>
                <tr>
                    <td>BCmath PHP Extension</td>
                    <td><?php echo $requirements['bcmath_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if (isset($versionReqs['gd']) && $versionReqs['gd']) : ?>
                <tr>
                    <td>GD PHP Extension</td>
                    <td><?php echo $requirements['gd_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if (isset($versionReqs['mod_rewrite']) && $versionReqs['mod_rewrite']) : ?>
                <tr>
                    <td>Rewrite mod enabled</td>
                    <td><?php echo $requirements['mod_rewrite_enabled'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if (isset($versionReqs['Redis']) && $versionReqs['Redis']) : ?>
                <tr>
                    <td>Redis PHP Extension</td>
                    <td><?php echo $requirements['redis'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if (isset($versionReqs['fileInfo']) && $versionReqs['fileInfo']) : ?>
                <tr>
                    <td>Fileinfo PHP Extension</td>
                    <td><?php echo $requirements['fileInfo'] ? $strOk : $strFail; ?></td>
                </tr>
            <?php endif ?>

            <?php if (!empty($versionReqs['obs'])) : ?>
                <tr>
                    <td colspan="2"><?php echo $versionReqs['obs'] ?></td>
                </tr>
            <?php endif; ?>

            <tr>
                <td>magic_quotes_gpc</td>
                <td><?php if ($magicQuotesGpcIni) {
                    echo $strFail . ' (value: ' . $magicQuotesGpcIni . ')';
                    } else {
                        echo $strOk;
                    } ?></td>
            </tr>

            <tr>
                <td>register_globals</td>
                <td><?php if ($registerGlobalsIni) {
                    echo $strFail . ' (value: ' . $registerGlobalsIni . ')';
                    } else {
                        echo $strOk;
                    } ?></td>
            </tr>

            <tr>
                <td>session.auto_start</td>
                <td><?php if ($sessionAutoStartIni) {
                    echo $strFail . ' (value: ' . $sessionAutoStartIni . ')';
                    } else {
                        echo $strOk;
                    } ?></td>
            </tr>

            <tr>
                <td>mbstring.func_overload</td>
                <td><?php if ($mbstringFuncOverloadIni) {
                    echo $strFail . ' (value: ' . $mbstringFuncOverloadIni . ')';
                    } else {
                        echo $strOk;
                    } ?></td>
            </tr>

            <tr>
                <td>allow_url_fopen</td>
                <td><?php if ($allowUrlFopen) {
                    echo $strOk . ' (value: ' . $allowUrlFopen . ')';
                    } else {
                        echo $strFail;
                    } ?></td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
