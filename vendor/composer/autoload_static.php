<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit969384c0c4434f4fa5a974a8b4e3fe4a
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Services\\' => 9,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/services',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit969384c0c4434f4fa5a974a8b4e3fe4a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit969384c0c4434f4fa5a974a8b4e3fe4a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit969384c0c4434f4fa5a974a8b4e3fe4a::$classMap;

        }, null, ClassLoader::class);
    }
}
