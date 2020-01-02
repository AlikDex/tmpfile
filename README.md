# tmpfile
Alternative work with temporary files in php

### Install
```
composer require alikdex/tmpfile
```

### Usage
```php
<?php

require 'vendor/autoload.php';

// Create file
$tmpfile = new \TA\TmpFile;

// Create with options: __construct($content, $suffix, $prefix, $directory)
$tmpfile = new \TA\TmpFile('Hello, world!', '.txt', 'foo_', '/path/to/your/tmp');

// Gets fullpath
(string) $tmpfile;
//or
$tmpfile->getPathname();

// Write data
$tmpfile->write('foo');

// Write with flags. Eg. lock file.
$tmpfile->write('bar', LOCK_EX);

// Append to end of file.
$tmpfile->append('baz');

// Gets content
$tmpfile->read();

// Cut a piece of content: read($offset, $length);
$tmpfile->read(7, 5);

// Remove file
$tmpfile->delete();

// Keed after usage (don't delete)
$tmpfile->autoDelete = false;
```
