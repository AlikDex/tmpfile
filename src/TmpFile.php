<?php
namespace TA;

final class TmpFile
{
    /**
     * @var bool Delete this file automatically.
     */
    public $autoRemove = true;

    /**
     * @var string The name of this file.
     */
    private $filename;

    /**
     * @var string Default mode of this file.
     */
    const CHMOD_MODE = 0600;

    /**
     * Constructor
     *
     * @param string $data The tmp file content.
     * @param string|null $suffix The optional suffix for the tmp file.
     * @param string|null $prefix The optional prefix for the tmp file. If null 'php_tmpfile_' is used.
     * @param string|null $directory Directory where the file should be created. Autodetected if not provided.
     */
    public function __construct($data = '', $suffix = null, $prefix = null, $directory = null)
    {
        if (null === $directory) {
            $directory = static::getTempDir();
        }

        if (null === $prefix) {
            $prefix = 'php_tmpfile_';
        }

        $this->filename = \tempnam($directory, $prefix);

        if (null !== $suffix) {
            $newName = $this->filename.$suffix;
            \rename($this->filename, $newName);
            $this->filename = $newName;
        }

        if ($content) {
            $this->write($this->filename, $content);
        } else {
            \touch($this->filename);
            \chmod($this->filename, static::CHMOD_MODE);
        }
    }

    /**
     * Write the data to a file
     *
     * @param mixed $data
     * @param int $flags
     *
     * @return int|false
     */
    public function write(string $data, int $flags = 0)
    {
        return \file_put_contents($this->filename, $data, $flags, static::CHMOD_MODE);
    }

    /**
     * Append the data to the end of the file
     *
     * @param mixed $data
     *
     * @return int|false
     */
    public function append($data)
    {
        return $this->write($data, FILE_APPEND);
    }

    /**
     * Delete a file
     *
     * @return bool
     */
    public function delete(): bool
    {
        return @unlink($this->filename);
    }

    /**
     * Gets the path of the temp directory.
     *
     * @return string The path of the temp directory.
     */
    public static function getTempDir(): string
    {
        if (\function_exists('sys_get_temp_dir')) {
            return \sys_get_temp_dir();
        } elseif (($tmp = \getenv('TMP')) || ($tmp = \getenv('TEMP')) || ($tmp = \getenv('TMPDIR'))) {
            return \realpath($tmp);
        }

        return '/tmp';
    }

    /**
     * @return string Gets path to file.
     */
    public function getPathname(): string
    {
        return $this->filename;
    }

    /**
     * Gets absolute path to file
     *
     * @return string|false Real path.
     */
    public function getRealPath()
    {
        return \realpath($this->filename);
    }

    /**
     * Delete tmp file on shutdown if `$autoRemove` is `true`
     */
    public function __destruct()
    {
        if (true === (bool) $this->autoRemove) {
            $this->delete();
        }
    }

    /**
     * @return string Convert object to full file path.
     */
    public function __toString()
    {
        return $this->filename;
    }
}
