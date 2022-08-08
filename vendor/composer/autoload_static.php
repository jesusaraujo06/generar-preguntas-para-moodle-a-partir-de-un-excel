<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit17584e6f15690dca8ffd00109bdf6732
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '2cffec82183ee1cea088009cef9a6fc3' => __DIR__ . '/..' . '/ezyang/htmlpurifier/library/HTMLPurifier.composer.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'ZipStream\\' => 10,
        ),
        'T' => 
        array (
            'Tesla\\GeneratePreguntasMoodle\\' => 30,
            'TdTrung\\OSRecognizer\\' => 21,
            'TdTrung\\Chalk\\' => 14,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'PhpOffice\\PhpSpreadsheet\\' => 25,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
            'Matrix\\' => 7,
        ),
        'C' => 
        array (
            'Complex\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ZipStream\\' => 
        array (
            0 => __DIR__ . '/..' . '/maennchen/zipstream-php/src',
        ),
        'Tesla\\GeneratePreguntasMoodle\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'TdTrung\\OSRecognizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/tdtrung17693/php-os-recognizer/src',
        ),
        'TdTrung\\Chalk\\' => 
        array (
            0 => __DIR__ . '/..' . '/tdtrung17693/php-chalk/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'PhpOffice\\PhpSpreadsheet\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
        'Matrix\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/matrix/classes/src',
        ),
        'Complex\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/complex/classes/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'HTMLPurifier' => 
            array (
                0 => __DIR__ . '/..' . '/ezyang/htmlpurifier/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit17584e6f15690dca8ffd00109bdf6732::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit17584e6f15690dca8ffd00109bdf6732::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit17584e6f15690dca8ffd00109bdf6732::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit17584e6f15690dca8ffd00109bdf6732::$classMap;

        }, null, ClassLoader::class);
    }
}
