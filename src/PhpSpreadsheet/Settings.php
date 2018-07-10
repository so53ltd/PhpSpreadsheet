<?php

namespace PhpOffice\PhpSpreadsheet;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Chart\Renderer\IRenderer;
use PhpOffice\PhpSpreadsheet\Collection\Memory;
use Psr\SimpleCache\CacheInterface;

class Settings
{
    /**
     * Class name of the chart renderer used for rendering charts
     * eg: PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph.
     *
     * @var string
     */
    private static $chartRenderer;

    /**
     * Default options for libxml loader.
     *
     * @var int
     */
    private static $libXmlLoaderOptions = null;

    /**
     * The cache implementation to be used for cell collection.
     *
     * @var CacheInterface
     */
    private static $cache;

    /**
     * This will allow to provide custom FQCN for the project
     *
     * @var string[]
     */
    private static $customFqcn = [];

    /**
     * Set the locale code to use for formula translations and any special formatting.
     *
     * @param string $locale The locale code to use (e.g. "fr" or "pt_br" or "en_uk")
     *
     * @return bool Success or failure
     */
    public static function setLocale($locale)
    {
        return Calculation::getInstance()->setLocale($locale);
    }

    /**
     * Identify to PhpSpreadsheet the external library to use for rendering charts.
     *
     * @param string $rendererClass Class name of the chart renderer
     *    eg: PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph
     *
     * @throws Exception
     */
    public static function setChartRenderer($rendererClass)
    {
        if (!is_a($rendererClass, IRenderer::class, true)) {
            throw new Exception('Chart renderer must implement ' . IRenderer::class);
        }

        self::$chartRenderer = $rendererClass;
    }

    /**
     * Return the Chart Rendering Library that PhpSpreadsheet is currently configured to use.
     *
     * @return null|string Class name of the chart renderer
     *    eg: PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph
     */
    public static function getChartRenderer()
    {
        return self::$chartRenderer;
    }

    /**
     * Set default options for libxml loader.
     *
     * @param int $options Default options for libxml loader
     */
    public static function setLibXmlLoaderOptions($options)
    {
        if ($options === null && defined('LIBXML_DTDLOAD')) {
            $options = LIBXML_DTDLOAD | LIBXML_DTDATTR;
        }
        self::$libXmlLoaderOptions = $options;
    }

    /**
     * Get default options for libxml loader.
     * Defaults to LIBXML_DTDLOAD | LIBXML_DTDATTR when not set explicitly.
     *
     * @return int Default options for libxml loader
     */
    public static function getLibXmlLoaderOptions()
    {
        if (self::$libXmlLoaderOptions === null && defined('LIBXML_DTDLOAD')) {
            self::setLibXmlLoaderOptions(LIBXML_DTDLOAD | LIBXML_DTDATTR);
        } elseif (self::$libXmlLoaderOptions === null) {
            self::$libXmlLoaderOptions = true;
        }

        return self::$libXmlLoaderOptions;
    }

    /**
     * Sets the implementation of cache that should be used for cell collection.
     *
     * @param CacheInterface $cache
     */
    public static function setCache(CacheInterface $cache)
    {
        self::$cache = $cache;
    }

    /**
     * Gets the implementation of cache that should be used for cell collection.
     *
     * @return CacheInterface
     */
    public static function getCache()
    {
        if (!self::$cache) {
            self::$cache = new Memory();
        }

        return self::$cache;
    }

    /**
     * Allow to overwrite default class names using their original names
     *
     * @param string $originalFqcn
     * @param string $newFqcn
     */
    public static function setCustomClassName($originalFqcn, $newFqcn)
    {
        self::$customFqcn[$originalFqcn] = $newFqcn;
    }

    /**
     * Get custom class name if such was provided
     *
     * Otherwise we're going to return original (from argument) class name.
     *
     * @param string $fqcn
     *
     * @return string
     */
    public static function getCustomClassName($fqcn)
    {
        return isset(self::$customFqcn[$fqcn]) ? self::$customFqcn[$fqcn] : $fqcn;
    }
}
