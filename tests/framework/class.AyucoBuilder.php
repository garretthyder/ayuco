<?php
use Ayuco\Commands\Command;

/**
 * Builds an command executable php script for testing purposes.
 *
 * @author Alejandro Mostajo <http://www.10quality.com>
 * @package Ayuco
 * @copyright MIT
 * @version 1.0
 */
class AyucoBuilder
{
    /**
     * Path of the last file built.
     * @since 1.0
     */
    protected static $lastBuilt;

    /**
     * Script head.
     * @since 1.0
     */
    protected $header;

    /**
     * Script content.
     * @since 1.0
     */
    protected $content = '';

    /**
     * Script Footer.
     * @since 1.0
     */
    protected $footer = '$ayuco->interpret();';

    /**
     * File path.
     * @since 1.0
     */
    protected $path;

    /**
     * Default constructor.
     * @since 1.0
     */
    public function __construct()
    {
        $this->header = '#!/usr/bin/env php'."\n".'<?php ';
        $this->header .= 'require_once \''. __DIR__.'/../../vendor/autoload.php\';';
        $this->header .= 'use Ayuco\Listener;';
        $this->header .= '$ayuco = new Listener();';
        $this->path = __DIR__.'/.tmp/';
    }

    /**
     * Default constructor.
     * @since 1.0
     * @param string $command Command class.
     * @param string $args    Arguments.
     */
    public function register($command, $args = null)
    {
        $this->content .= 'use '.$command.';';
        $this->content .= empty($args)
            ? '$ayuco->register(new '.$command.');'
            : '$ayuco->register(new '.$command.'(\''.((string)$args).'\'));';
    }

    /**
     * Clears content.
     * @since 1.0
     */
    public function clear()
    {
        unlink(self::$lastBuilt);
        $this->content = '';
    }

    /**
     * Generates custom temporal build.
     * @since 1.0
     * @param string $command Command class.
     * @param string $args    Arguments.
     */
    public function build()
    {
        if (!is_dir($this->path))
            mkdir($this->path, 0777, true );
        $id = uniqid().'.php';
        $file = fopen($this->path . $id, 'w');
        fwrite($file, $this->header . $this->content . $this->footer);
        fclose($file);
        self::$lastBuilt = $this->path . $id;
        return self::$lastBuilt;
    }

    /**
     * Logs in a file.
     * @since 1.0
     * @param mixed $content
     */
    public function log($content)
    {
        if (!is_dir($this->path))
            mkdir($this->path, 0777, true );
        $id = uniqid().'.txt';
        $file = fopen($this->path . $id, 'w');
        fwrite($file, $content);
        fclose($file);
    }
}