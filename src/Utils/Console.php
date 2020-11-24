<?php
/**
 * Console.
 *
 * @since     November 2016
 *
 * @author    Cengizhan Ç. <cengizhancaliskan@gmail.com>
 * @copyright Copyright (c) 2016 Zingat.com (https://www.zingat.com)
 *
 */

namespace Utils;

/**
 * Handling output data to the console.
 */
class Console
{
    public static $foregroundColors = [
        'black'        => '0;30', 'dark_gray'         => '1;30',
        'red'          => '0;31', 'light_red'         => '1;31',
        'green'        => '0;32', 'light_green'       => '1;32',
        'brown'        => '0;33', 'yellow'            => '1;33',
        'blue'         => '0;34', 'light_blue'        => '1;34',
        'purple'       => '0;35', 'light_purple'      => '1;35',
        'cyan'         => '0;36', 'light_cyan'        => '1;36',
        'light_gray'   => '0;37', 'white'             => '1;37',
    ];

    public static $backgroundColors = [
        'black'        => '40', 'red'          => '41',
        'green'        => '42', 'yellow'       => '43',
        'blue'         => '44', 'magenta'      => '45',
        'cyan'         => '46', 'light_gray'   => '47',
    ];


    public static $logLevelSilent = 0;
    public static $logLevelNormal = 1;
    public static $logLevelVerbose = 2;


    /**
     * @param $message
     * @param int $level
     * @throws \ErrorException
     */
    public static function writeLineNormal($message, $level = 1)
    {
        if($level <= self::$logLevelVerbose && $level > self::$logLevelSilent) {
            self::writeLine($message);
        }
    }

    /**
     * @param $message
     * @param int $level
     * @throws \ErrorException
     */
    public static function writeLineVerbose($message, $level = 2)
    {
        if($level === self::$logLevelVerbose) {
            self::writeLine($message);
        }
    }

    /**
     * @param $message
     * @param int $level
     * @throws \ErrorException
     */
    public static function writeTitleNormal($message, $level = 2)
    {
        if($level === self::$logLevelNormal) {
            self::writeTitle($message);
        }
    }

    /**
     * @param $message
     * @param int $level
     * @throws \ErrorException
     */
    public static function writeTitleVerbose($message, $level = 3)
    {
        if($level === self::$logLevelVerbose) {
            self::writeTitle($message);
        }
    }


    /**
     * Output a messages and adds a newline at the end.
     *
     * @param $messages
     * @param null $color
     * @param null $background
     * @throws \ErrorException
     */
    public static function writeLine($messages, $color = null, $background = null)
    {
        self::write($messages, $color, $background, true);
    }

    /**
     * Output a messages.
     *
     * @param $messages
     * @param null $color
     * @param null $background
     * @param bool $linebreak
     *
     * @return bool
     * @throws \ErrorException
     */
    public static function write($messages, $color = null, $background = null, $linebreak = true)
    {
        if ('cli' !== php_sapi_name()) {
            return false;
        }

        $messages = (array) $messages;

        $stream = @fopen('php://stdout', 'w') ?: fopen('php://output', 'w');

        foreach ($messages as $message) {
            $message = date('Y-m-d H:i:s - ').$message;

            if ($color) {
                $message = self::getColoredString($message, $color, $background);
            }

            if ($linebreak) {
                $message .= PHP_EOL;
            }
            if (false === @fwrite($stream, $message)) {
                throw new \RuntimeException('Unable to write output.');
            }
        }

        fflush($stream);
    }

    /**
     * @param $title
     * @param null $color
     * @param null $background
     * @throws \ErrorException
     */
    public static function writeTitle($title, $color = null, $background = null)
    {
        self::writeLine(str_pad('-', strlen($title), '-'), $color);
        self::writeLine($title, $color, $background);
        self::writeLine(str_pad('-', strlen($title), '-'), $color);
    }

    /**
     * Adds a new line with page break.
     *
     * @param null|mixed $color
     * @throws \ErrorException
     */
    public static function pageBreak($color = null)
    {
        self::writeLine('========================================================', $color);
    }

    /**
     * Returns colored string.
     *
     * @param $string
     * @param null $foregroundColor
     * @param null $backgroundColor
     *
     * @return string
     */
    public static function getColoredString($string, $foregroundColor = null, $backgroundColor = null)
    {
        $coloredString = '';

        // Check if given foreground color found
        if (isset(self::$foregroundColors[$foregroundColor])) {
            $coloredString .= "\033[".self::$foregroundColors[$foregroundColor].'m';
        }

        // Check if given background color found
        if (isset(self::$backgroundColors[$backgroundColor])) {
            $coloredString .= "\033[".self::$backgroundColors[$backgroundColor].'m';
        }

        // Add string and end coloring
        $coloredString .= $string."\033[0m";

        return $coloredString;
    }

    /**
     * Returns all foreground color names.
     *
     * @return array
     */
    public static function getForegroundColors()
    {
        return array_keys(self::$foregroundColors);
    }

    /**
     * Returns all background color names.
     *
     * @return array
     */
    public static function getBackgroundColors()
    {
        return array_keys(self::$backgroundColors);
    }
}
