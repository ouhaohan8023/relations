<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7811576b2e5e8c64dfa1cc474a490577
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Ouhaohan8023\\Relation\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ouhaohan8023\\Relation\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit7811576b2e5e8c64dfa1cc474a490577::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7811576b2e5e8c64dfa1cc474a490577::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7811576b2e5e8c64dfa1cc474a490577::$classMap;

        }, null, ClassLoader::class);
    }
}
