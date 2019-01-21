<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit32f9b52cc0346f396a92ca4b31d5c5ed
{
    public static $classMap = array (
        'Datamatrix' => __DIR__ . '/../..' . '/include/barcodes/datamatrix.php',
        'PDF417' => __DIR__ . '/../..' . '/include/barcodes/pdf417.php',
        'QRcode' => __DIR__ . '/../..' . '/include/barcodes/qrcode.php',
        'TCPDF' => __DIR__ . '/../..' . '/tcpdf.php',
        'TCPDF2DBarcode' => __DIR__ . '/../..' . '/tcpdf_barcodes_2d.php',
        'TCPDFBarcode' => __DIR__ . '/../..' . '/tcpdf_barcodes_1d.php',
        'TCPDF_COLORS' => __DIR__ . '/../..' . '/include/tcpdf_colors.php',
        'TCPDF_FILTERS' => __DIR__ . '/../..' . '/include/tcpdf_filters.php',
        'TCPDF_FONTS' => __DIR__ . '/../..' . '/include/tcpdf_fonts.php',
        'TCPDF_FONT_DATA' => __DIR__ . '/../..' . '/include/tcpdf_font_data.php',
        'TCPDF_IMAGES' => __DIR__ . '/../..' . '/include/tcpdf_images.php',
        'TCPDF_IMPORT' => __DIR__ . '/../..' . '/tcpdf_import.php',
        'TCPDF_PARSER' => __DIR__ . '/../..' . '/tcpdf_parser.php',
        'TCPDF_STATIC' => __DIR__ . '/../..' . '/include/tcpdf_static.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit32f9b52cc0346f396a92ca4b31d5c5ed::$classMap;

        }, null, ClassLoader::class);
    }
}
