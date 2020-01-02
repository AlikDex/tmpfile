<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TmpFileTest extends TestCase
{
    public function testCreate()
    {
        $tmpfile = new TmpFile;

        $this->assertFileExists($tmpfile->getPahtname());
    }

    public function testCreateWithContent()
    {
        $data = $this->generateTestContent();
        $tmpfile = new TmpFile($data);

        $this->assertStringEqualsFile($tmpfile->getPahtname(), $data);
    }

    protected function generateTestContent()
    {
        return \random_bytes(1024);
    }
}
