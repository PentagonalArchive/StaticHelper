<?php
namespace Pentagonal\StaticHelper;

/**
 * Class PathHelper
 *
 * @package Pentagonal\StaticHelper
 */
class PathHelper
{
    /**
     * Cleaning path for window directory separator and trim right the separator '/'
     *
     * @param  string $path path
     * @return string       cleaned path
     */
    public static function cleanPath($path)
    {
        if (empty($path)) {
            return $path;
        }
        if (is_array($path)) {
            foreach ($path as $key => $value) {
                $path[$key] = $this->cleanPath($value);
            }
        } elseif (is_object($path)) {
            foreach (get_object_vars($path) as $key => $value) {
                $path->$key = $this->cleanPath($value);
            }
        } elseif (is_string($path)) {
            $path = rtrim(self::cleanSlashed($path), '/');
        }

        return $path;
    }

    /**
     * Clan Invalid Slashed to be only one slashed on separate
     *
     * @param  mixed $path  path to be cleaned
     * @return string
     */
    public static function cleanSlashed($path)
    {
        if (is_array($path)) {
            foreach ($path as $key => $value) {
                $path[$key] = self::cleanSlashed($value);
            }
        }
        if (is_object($path)) {
            foreach (get_object_vars($path) as $key => $value) {
                $path->{$key} = self::cleanSlashed($value);
            }
        }
        if (is_string($path)) {
            static $path_tmp = array();
            $path_tmp[$path] = isset($path_tmp[$path])
                ? $path_tmp[$path]
                : preg_replace('/(\\\|\/)+/', '/', $path);
            return $path_tmp[$path];
        }
        return $path;
    }

    /* --------------------------------------------------------------------------------*
     |                              Path & File Helpers                                |
     |---------------------------------------------------------------------------------|
     */

    /**
     * Check if path is file
     *
     * @param  string  $path path to be check
     * @return boolean       true if it is file
     */
    public static function isFile($path)
    {
        return is_file($path);
    }

    /**
     * Check if current path is directory
     *
     * @param  string  $path directory
     * @return boolean      true if is directory
     */
    public static function isDir($path)
    {
        return is_dir($path);
    }

    /**
     * Check if current path is writable
     *
     * @param  string  $path path to be check
     * @return boolean       true if writable
     */
    public static function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * Chmod Function to set permission of files
     *
     * @param  string  $path path to be set
     * @param  integer $mode chmod mode
     * @return boolean
     */
    public static function chmod($path, $mode)
    {
        if (is_dir($path) || is_file($file)) {
            return @chmod($path, $mode);
        }
    }

    /**
     * Tests for file writability
     *
     * is_writable() returns TRUE on Windows servers when you really can't write to
     * the file, based on the read-only attribute. is_writable() is also unreliable
     * on Unix servers if safe_mode is on.
     *
     * @credits CI (Code Igniter)
     *
     * @link    https://bugs.php.net/bug.php?id=54709
     * @param   string  $file filepath
     * @param   bool    $force  to determine recheck true if want to recheck
     * @return  bool
     */
    public static function isReallyWritable($file, $force = false)
    {
        if (!is_string($file)) {
            return false;
        }
        // cached result
        static $retval = array();
        $key = md5($file);
        if (isset($retval[$key]) && !$force) {
            return $retval[$key];
        }
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (self::isPhp('5.4') || ! @ini_get('safe_mode'))) {
            $retval[$key] = self::isWritable($file);
            return $retval[$key];
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (self::isDir($file)) {
            $file = self::cleanPath($file);
            // file random
            $file .='/'.md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }

            fclose($fp);
            self::chmod($file, 0777);
            @unlink($file);
            unset($file);
            $retval[$key] = true;
            return $retval[$key];
        } elseif (! is_file($file) || ($fp = @fopen($file, 'ab')) === false) {
            $retval[$key] = false;
            return $retval[$key];
        }

        $fp && fclose($fp); // close resource if resource is opened
        return $retval[$key];
    }

    /**
     * Read Directory Nested
     *
     * @param  string  $path            Path directory to be scan
     * @param  integer $directory_depth directory depth of nested to be scanned
     * @param  boolean $hidden          true if want to show hidden content
     * @return array                    path trees
     */
    public static function readDirList($path, $directory_depth = 0, $hidden = false)
    {
        $filedata = false;
        if (self::isDir($path) && $fp = opendir($path)) {
            $new_depth  = $directory_depth - 1;
            $path = self::cleanPath($path).'/';
            while (false !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.' || $file === '..' || ($hidden === false && $file[0] === '.')) {
                    continue;
                }

                self::isDir($path.$file) && $path .= '/';

                if (($directory_depth < 1 || $new_depth > 0) &&  self::isDir($path.$file)) {
                    $filedata[$file] = self::readDirList($path.$file, $new_depth, $hidden);
                } else {
                    $filedata[] = $file;
                }
            }
            // close resource
            closedir($fp);
        }

        return $filedata;
    }
}
