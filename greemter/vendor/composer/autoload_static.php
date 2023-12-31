<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdd753f146bf11706ccfcf4be09fcc85d
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'G' => 
        array (
            'Greenter\\XMLSecLibs\\' => 20,
            'Greenter\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Greenter\\XMLSecLibs\\' => 
        array (
            0 => __DIR__ . '/..' . '/greenter/xmldsig/src',
        ),
        'Greenter\\' => 
        array (
            0 => __DIR__ . '/..' . '/greenter/core/src/Core',
            1 => __DIR__ . '/..' . '/greenter/greenter/src/Greenter',
            2 => __DIR__ . '/..' . '/greenter/ws/src',
            3 => __DIR__ . '/..' . '/greenter/xml/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdd753f146bf11706ccfcf4be09fcc85d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdd753f146bf11706ccfcf4be09fcc85d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitdd753f146bf11706ccfcf4be09fcc85d::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
