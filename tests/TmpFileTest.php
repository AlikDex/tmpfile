<?php
declare(strict_types=1);

use TA\TmpFile;
use PHPUnit\Framework\TestCase;

final class TmpFileTest extends TestCase
{
    public function testCreate()
    {
        $tmpfile = new TmpFile;

        $this->assertFileExists($tmpfile->getPathname());
    }

    public function testCreateWithContent()
    {
        $data = $this->generateTestContent();
        $tmpfile = new TmpFile($data);

        $this->assertStringEqualsFile($tmpfile->getPathname(), $data);
    }

    public function testCanCreateFileWithSuffix()
    {
        $content = $this->generateTestContent();

        $tmpfile = new TmpFile($content, '.html');
        $filename = $tmpfile->getPathname();

        $this->assertEquals('.html', \substr($filename, -5));
    }

    public function testCanCreateFileWithPrefix()
    {
        $content = $this->generateTestContent();

        $tmpfile = new TmpFile($content, null, 'test_');
        $filename = $tmpfile->getPathname();
        $basename = \basename($filename);

        $this->assertEquals('test_', \substr($basename, 0, 5));
    }

    public function testCanCreateFileInDirectory()
    {
        $directory = __DIR__.'/tmp';

        \mkdir($directory);

        $content = $this->generateTestContent();
        $tmpfile = new TmpFile($content, null, null, $directory);
        $filename = $tmpfile->getPathname();

        $this->assertEquals($directory, \dirname($filename));

        unset($tmpfile);
        \rmdir($directory);
    }

    public function testWrite()
    {
        $content = $this->generateTestContent();

        $tmpfile = new TmpFile;
        $tmpfile->write($content);

        $this->assertEquals($content, \file_get_contents($tmpfile->getPathname()));
    }

    public function testWriteWithFlags()
    {
        $tmpfile = new TmpFile('foo');
        $tmpfile->write('bar', FILE_APPEND);

        $this->assertEquals('foobar', \file_get_contents($tmpfile->getPathname()));
    }

    public function testAppend()
    {
        $tmpfile = new TmpFile('abc');
        $tmpfile->append('def');

        $this->assertEquals('abcdef', file_get_contents($tmpfile->getPathname()));
    }

    public function testRead()
    {
        $content = $this->generateTestContent();
        $tmpfile = new TmpFile($content);
        $readedContent = $tmpfile->read();

        $this->assertEquals($content, $readedContent);
    }

    public function testReadWithOffsetAndMaxlen()
    {
        $tmpfile = new TmpFile('Hello, world!');
        $data = $tmpfile->read(7, 5);

        $this->assertEquals('world', $data);
    }

    public function testDelete()
    {
        $tmpfile = new TmpFile;
        $tmpfile->delete();

        $this->assertFileNotExists($tmpfile->getPathname());
    }

    public function testAutoDelete()
    {
        $tmpfile = new TmpFile;
        $tmpfile->autoDelete = true;
        $path = $tmpfile->getPathname();
        unset($tmpfile);

        $this->assertFileNotExists($path);
    }

    public function testCanKeepTempFile()
    {
        $tmpfile = new TmpFile;
        $tmpfile->autoDelete = false;
        $path = $tmpfile->getPathname();
        unset($tmpfile);

        $this->assertFileExists($path);

        unlink($path);
    }

    public function testObjectCanCastToFullPath()
    {
        $tmpfile = new TmpFile;

        $this->assertEquals($tmpfile, $tmpfile->getPathname());
    }

    protected function generateTestContent()
    {
        return \random_bytes(1024);
    }
}
