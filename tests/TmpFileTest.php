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

    protected function generateTestContent()
    {
        return \random_bytes(1024);
    }
}
