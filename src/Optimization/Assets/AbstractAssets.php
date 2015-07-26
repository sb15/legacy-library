<?php

namespace Optimization\Assets;

abstract class AbstractAssets
{
    const OPT_ASSETS_CONFIG = 'assets_config';
    const OPT_ASSETS_PATH = 'assets_path';
    const OPT_ASSETS_URL = 'assets_url';
    const OPT_COMPRESSED_ASSETS_URL = 'compressed_assets_url';
    const OPT_COMPRESSED_ASSETS_PATH = 'compressed_assets_path';
    const OPT_USE_COMPRESSED = 'use_compressed';
    const OPT_IS_DEVELOPMENT = 'is_development';

    protected $assets;
    protected $type;
    protected $ext;

    protected static $assetsConfig;
    protected static $assetsPath;
    protected static $assetsUrl;
    protected static $compressedAssetsUrl;
    protected static $compressedAssetsPath;
    protected static $useCompressed;
    protected static $isDevelopment = false;

    public static function setOptions(array $options)
    {
        if(array_key_exists(self::OPT_ASSETS_CONFIG, $options)) {
            self::setAssetsConfig($options[self::OPT_ASSETS_CONFIG]);
        }
        if(array_key_exists(self::OPT_ASSETS_PATH, $options)) {
            self::setAssetsPath($options[self::OPT_ASSETS_PATH]);
        }
        if(array_key_exists(self::OPT_ASSETS_URL, $options)) {
            self::setAssetsUrl($options[self::OPT_ASSETS_URL]);
        }
        if(array_key_exists(self::OPT_COMPRESSED_ASSETS_URL, $options)) {
            self::setCompressedAssetsUrl($options[self::OPT_COMPRESSED_ASSETS_URL]);
        }
        if(array_key_exists(self::OPT_COMPRESSED_ASSETS_PATH, $options)) {
            self::setCompressedAssetsPath($options[self::OPT_COMPRESSED_ASSETS_PATH]);
        }
        if(array_key_exists(self::OPT_USE_COMPRESSED, $options)) {
            self::setUseCompressed($options[self::OPT_USE_COMPRESSED]);
        }
		if(array_key_exists(self::OPT_IS_DEVELOPMENT, $options)) {
            self::$isDevelopment = $options[self::OPT_IS_DEVELOPMENT];
        }
    }

    protected function loadAssets()
    { 
        if(empty($this->assets) && file_exists(self::getAssetsConfig())) {
            $assets = file_get_contents(self::getAssetsConfig());
            if(!empty($assets)) {				
                $this->assets = json_decode($assets, true);
            }
        }
    }

    /**
     * @param string $group
     * @param string $theme
     * @return null|string
     */
    protected function assets($group, $theme = 'default')
    {
        $this->loadAssets();

        /*if ($group == 'main') {
            $asset = $theme;
        } else {
            $asset = $theme . '-' . $group;
        }*/
		$asset = $group;		
		
        if (!$this->isUseCompressed() && $this->hasAssets($asset)) {
            return $this->renderAssetsGroup($asset);
        } else {
            return $this->renderCompressedAssetsGroup($asset);
        }
    }

    /**
     * @param string $asset
     * @return bool
     */
    public function hasAssets($asset)
    {	
        return !empty($this->assets[$this->type][$asset]);
    }

    /**
     * @param string $group
     * @return null|string
     */
    protected function renderAssetsGroup($group)
    {
        $result = null;
        foreach ($this->assets[$this->type][$group] as $file) {
            $fileTime = filemtime($this->getAssetsPath() . $file);
            $url = preg_replace('#^static\\/#', $this->getAssetsUrl() . '/', trim($file)) . '?v=' . $fileTime;			
            $result .= $this->getCodeString($url);			
        }
        return $result;
    }

    /**
     * @param string $group
     * @return string
     */
    protected function renderCompressedAssetsGroup($group)
    {
        $file = self::getCompressedAssetsPath() . $group . '.' . $this->ext;
        $url = self::getCompressedAssetsUrl() . $group . (!self::$isDevelopment ? '.d' . filemtime($file) : '') . '.' . $this->ext;
        return $this->getCodeString($url);
    }

    /**
     * @param string $file
     * @return string
     */
    abstract protected function getCodeString($file);

    /**
     * @return bool
     */
    protected function isUseCompressed()
    {
        return self::$useCompressed;
    }

    /**
     * @param bool $useCompressed
     */
    public static function setUseCompressed($useCompressed)
    {
        self::$useCompressed = (bool) $useCompressed;
    }

    /**
     * @param string $assetsUrl
     * @return void
     */
    public static function setAssetsUrl($assetsUrl)
    {
        self::$assetsUrl = $assetsUrl;
    }

    /**
     * @return string
     */
    public static function getAssetsUrl()
    {
        return self::$assetsUrl;
    }

    /**
     * @param string $compressedAssetsUrl
     * @return void
     */
    public static function setCompressedAssetsUrl($compressedAssetsUrl)
    {
        self::$compressedAssetsUrl = $compressedAssetsUrl;
    }

    /**
     * @return string
     */
    public static function getCompressedAssetsUrl()
    {
        return self::$compressedAssetsUrl;
    }

    /**
     * @param string $compressedAssetsPath
     * @return void
     */
    public static function setCompressedAssetsPath($compressedAssetsPath)
    {
        self::$compressedAssetsPath = $compressedAssetsPath;
    }

    /**
     * @return string
     */
    public static function getCompressedAssetsPath()
    {
        return self::$compressedAssetsPath;
    }

    /**
     * @param string $assetsPath
     * @return void
     */
    public static function setAssetsPath($assetsPath)
    {
        self::$assetsPath = $assetsPath;
    }

    /**
     * @return string
     */
    public static function getAssetsPath()
    {
        return self::$assetsPath;
    }

    /**
     * @param string $assetsConfigFile
     * @return void
     */
    public static function setAssetsConfig($assetsConfigFile)
    {
        self::$assetsConfig = $assetsConfigFile;
    }

    /**
     * @return string
     */
    public static function getAssetsConfig()
    {
        return self::$assetsConfig;
    }
}
