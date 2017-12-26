<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit30c930fc12886778dbed95f4acd675e3
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AmoCRM\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AmoCRM\\' => 
        array (
            0 => __DIR__ . '/..' . '/nabarabane/amocrm/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit30c930fc12886778dbed95f4acd675e3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit30c930fc12886778dbed95f4acd675e3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
