<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/30/2018
 * Time: 12:10 PM
 */
namespace App\iPolitic\NawpCore\Components;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Logger
 * @package App\iPolitic\NawpCore\components
 */
class Logger implements LoggerInterface
{
    const
        FOREGROUND = 38,
        BACKGROUND = 48,
        COLOR256_REGEXP = '~^(bg_)?color_([0-9]{1,3})$~',
        RESET_STYLE = 0,
        STYLES = [
            'none' => null,
            'bold' => '1',
            'dark' => '2',
            'italic' => '3',
            'underline' => '4',
            'blink' => '5',
            'reverse' => '7',
            'concealed' => '8',
            'default' => '39',
            'black' => '30',
            'red' => '31',
            'green' => '32',
            'yellow' => '33',
            'blue' => '34',
            'magenta' => '35',
            'cyan' => '36',
            'light_gray' => '37',
            'dark_gray' => '90',
            'light_red' => '91',
            'light_green' => '92',
            'light_yellow' => '93',
            'light_blue' => '94',
            'light_magenta' => '95',
            'light_cyan' => '96',
            'white' => '97',
            'bg_default' => '49',
            'bg_black' => '40',
            'bg_red' => '41',
            'bg_green' => '42',
            'bg_yellow' => '43',
            'bg_blue' => '44',
            'bg_magenta' => '45',
            'bg_cyan' => '46',
            'bg_light_gray' => '47',
            'bg_dark_gray' => '100',
            'bg_light_red' => '101',
            'bg_light_green' => '102',
            'bg_light_yellow' => '103',
            'bg_light_blue' => '104',
            'bg_light_magenta' => '105',
            'bg_light_cyan' => '106',
            'bg_white' => '107',
        ],
        TEMPLATES = [
            'title' => ['bold', 'underline', 'magenta'],
            'desc' => ['cyan'],
            'list_title' => ['underline'],
            'check' => ['yellow'],
            'success' => ['green'],
            'failure' => ['red'],
            LogLevel::EMERGENCY => ['red', 'bold', 'underline'],
            LogLevel::ALERT => ['light_red'],
            LogLevel::CRITICAL => ['light_red', 'bold'],
            LogLevel::ERROR => ['red'],
            LogLevel::WARNING => ['yellow'],
            LogLevel::NOTICE => ['yellow'],
            LogLevel::INFO => ['blue'],
            LogLevel::DEBUG => ['magenta'],
        ];

    /**
     * That way templates are editable
     * @var array
     */
    public static $templates = self::TEMPLATES;

    /***
     * @var bool
     */
    private $isSupported;

    /**
     * @var bool
     */
    private $forceStyle = false;

    /**
     * @var string
     */
    public $output = "";

    /**
     * @var array
     */
    private $themes = [];

    /**
     * LoggerColor constructor.
     */
    public function __construct()
    {
        $this->isSupported = $this->isSupported();
    }

    /**
     * Will apply the given $styles to the given $text
     * @param string $text
     * @param array $styles
     * @return Logger
     * @throws \Exception
     */
    public function _applyStyles(string $text, array $styles): Logger
    {
        // we use templates if needed by merging
        foreach ($s = array_map(function ($element) {
            return
            isset(self::$templates[$element]) ? self::$templates[$element] : $element;
        }, $styles) as $k => $v) {
            if (is_array($v)) {
                unset($s[$k]);
                $styles = array_merge($s, $v);
            }
        }
        if (!$this->isStyleForced() && !$this->isSupported()) {
            $this->output = $text;
            return $this;
        }
        $sequences = [];
        foreach ($styles as $k => $v) {
            if (isset($this->themes[$v])) {
                $sequences = array_merge($sequences, $this->themeSequence($v));
            } elseif ($this->isValidStyle($v)) {
                $sequences[] = $this->styleSequence($v);
            } else {
                throw new \Exception($v);
            }
        }
        $sequences = array_filter($sequences, function ($val) {
            return $val !== null;
        });
        if (empty($sequences)) {
            $this->output = $text;
            return $this;
        }
        $this->output = $this->escSequence(implode(';', $sequences)) . $text . $this->escSequence(self::RESET_STYLE);
        return $this;
    }

    /**
     * @param string $text
     * @param string ...$args
     * @return Logger
     * @throws \Exception
     */
    public function applyStyles(string $text, string ... $args): Logger
    {
        return $this->_applyStyles($text, $args);
    }

    /**
     * Convert debug trace to file line
     * @param array $trace
     * @param bool $max
     * @param int $minus
     * @return string
     */
    public static function formatTrace(array $trace, int $minus = 2, bool $max = true): string
    {
        $caller = array_shift($trace);
        $ret =  ($fn = explode("\\", $caller['file']))[count($fn) - $minus] . ":".$caller['line'];
        return $max ? ("    {" . $ret."} ") :
            (" ".$ret);
    }

    /**
     * Will store in log the given value of $this->output
     */
    private function storeInLog(): void
    {
        $cleanStr = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $this->output);
        file_put_contents(getenv("LOG_FILE_PATH"), $cleanStr, FILE_APPEND | LOCK_EX);
        return;
    }

    /**
     * Will log the given string with the given styles array
     * output : [H-M-S] (TYPE:) $text {file:line}
     * @param string $text
     * @param string ...$args
     * @return Logger
     * @throws \Exception
     */
    public function logWithStyle(string $text, string ... $args): Logger
    {
        $today = (string) date("F j, g:i a");
        $string = "[" . $today . self::formatTrace(debug_backtrace(), 2, false) ."] " . $this->_applyStyles($text, $args) . PHP_EOL ;
        $this->output = $string;
        for ($i = 0; $i < count($args) - 1; $i++) {
            if ($args[$i] === "emergency") {
                $this->storeInLog();
                echo  $this->output . PHP_EOL;
                echo "[Exiting because of an emergency issue...]" . PHP_EOL;
                exit;
            }
        }
        $this->storeInLog();
        return $this;
    }

    /**
     * output : [H-M-S] Checking: $text [SUCCESS / FAILURE] {file:line}
     * @param string $text
     * @param callable $callback that have to return a bool
     * @param boolean $echo should we use a php echo() ?
     * @throws \Exception
     * @return Logger
     */
    public function check(string $text, callable $callback, bool $echo = true): Logger
    {
        $checkingString = function () use ($text): string {
            $this->storeInLog();
            return (string) $this->applyStyles("[Checking] -> " .  $text, "check") . " ... ";
        };
        $responseString = function () use ($callback): string {
            $result =   (bool) $callback();
            $this->storeInLog();
            return (string) $this->applyStyles($result ? "SUCCESS" : "FAILURE", $result ? "success" : "failure") . self::formatTrace(debug_backtrace()) . PHP_EOL;
        };
        if ($echo) {
            echo $checkingString();
            echo $responseString();
        } else {
            $this->output = $checkingString() . $responseString();
        }
        $this->storeInLog();
        return $this;
    }

    /**
     * output : _______________________
     * (bold) Title:
     *      * ...
     *      * ...
     * {file:line}
     * _______________________
     * @param string $title
     * @param string ...$elements
     * @throws \Exception
     * @return Logger
     */
    public function list(string $title, string ...$elements): Logger
    {
        $elements = is_array($elements[0]) ? $elements[0] : $elements;
        $this->output =
            $this->applyStyles($title, "list_title") . " " .   self::formatTrace(debug_backtrace()) .
            PHP_EOL .
            "*" . join(PHP_EOL . "*", $elements) .
            PHP_EOL;
        $this->storeInLog();
        return $this;
    }

    /**
     * output : (cyan) $text {file:line}
     * @param string $text
     * @throws \Exception
     * @return Logger
     */
    public function desc(string $text): Logger
    {
        $this->output =  $this->applyStyles($text, "desc") . self::formatTrace(debug_backtrace()) . PHP_EOL;
        $this->storeInLog();
        return $this;
    }

    /**
     * output : (bold) $text
     * @param string $text
     * @throws \Exception
     * @return Logger
     */
    public function title(string $text): Logger
    {
        $this->output =  $this->applyStyles($text, "title") . self::formatTrace(debug_backtrace()) . PHP_EOL;
        $this->storeInLog();
        return $this;
    }

    /**
     * @param bool $forceStyle
     */
    public function setForceStyle($forceStyle)
    {
        $this->forceStyle = (bool) $forceStyle;
    }
    /**
     * @return bool
     */
    public function isStyleForced()
    {
        return $this->forceStyle;
    }

    /**
     * @param array $themes
     * @throws \Exception
     */
    public function setThemes(array $themes)
    {
        $this->themes = [];
        foreach ($themes as $name => $styles) {
            $this->addTheme($name, $styles);
        }
    }
    /**
     * @param string $name
     * @param array|string $styles
     * @throws \Exception
     */
    public function addTheme($name, $styles)
    {
        if (is_string($styles)) {
            $styles = [$styles];
        }
        if (!is_array($styles)) {
            throw new \Exception("Style must be string or array.");
        }
        foreach ($styles as $style) {
            if (!$this->isValidStyle($style)) {
                throw new \Exception($style);
            }
        }
        $this->themes[$name] = $styles;
    }

    /**
     * @return array
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasTheme($name)
    {
        return isset($this->themes[$name]);
    }

    /**
     * @param string $name
     */
    public function removeTheme($name)
    {
        unset($this->themes[$name]);
    }

    /**
     * @return bool
     */
    public function isSupported()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            if (function_exists('sapi_windows_vt100_support') && @sapi_windows_vt100_support(STDOUT)) {
                return true;
            } elseif (getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON') {
                return true;
            }
            return false;
        } else {
            return function_exists('posix_isatty');
        }
    }

    /**
     * @return bool
     */
    public function are256ColorsSupported()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return function_exists('sapi_windows_vt100_support') && @sapi_windows_vt100_support(STDOUT);
        } else {
            return strpos(getenv('TERM'), '256color') !== false;
        }
    }

    /**
     * @return array
     */
    public function getPossibleStyles()
    {
        return array_keys(self::STYLES);
    }

    /**
     * @param string $name
     * @return string[]
     */
    private function themeSequence($name)
    {
        $sequences = array();
        foreach ($this->themes[$name] as $style) {
            $sequences[] = $this->styleSequence($style);
        }
        return $sequences;
    }

    /**
     * @param string $style
     * @return string
     */
    private function styleSequence($style)
    {
        if (array_key_exists($style, self::STYLES)) {
            return self::STYLES[$style];
        }
        if (!$this->are256ColorsSupported()) {
            return null;
        }
        preg_match(self::COLOR256_REGEXP, $style, $matches);
        $type = $matches[1] === 'bg_' ? self::BACKGROUND : self::FOREGROUND;
        $value = $matches[2];
        return "$type;5;$value";
    }

    /**
     * @param string $style
     * @return bool
     */
    private function isValidStyle($style)
    {
        return array_key_exists($style, self::STYLES) || preg_match(self::COLOR256_REGEXP, $style);
    }

    /**
     * @param string|int $value
     * @return string
     */
    private function escSequence($value)
    {
        return "\033[{$value}m";
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->output;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Will bind the params in brackets in the given $message
     * @param $message
     * @param array $context
     * @return string
     */
    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function emergency($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::EMERGENCY);
        return;
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function alert($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::ALERT);
        return;
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function critical($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::CRITICAL);
        return;
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function error($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::ERROR);
        return;
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function warning($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::WARNING);
        return;
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function notice($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::NOTICE);
        return;
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function info($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::INFO);
        return;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function debug($message, array $context = array()) : void
    {
        $message = $this->interpolate($message, $context);
        echo $this->logWithStyle($message, LogLevel::DEBUG);
        return;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     * @throws \Exception
     */
    public function log($level, $message, array $context = []) : void
    {
        if (defined("LogLevel::" . $level)) {
            $this->$level($message, $context);
        }
        return;
    }
}
