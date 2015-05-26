<?php

require_once('vendor/autoload.php');

use Tracy\Debugger,
    Bouda\Php7Backport\Backporter;

Debugger::enable();


$inDir  = 'C:/home/projects/components/src/Bouda/DI';
$outDir = 'tmp';

$backporter = new Backporter;

// iterate over all .php files in the directory
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inDir));
$files = new RegexIterator($files, '/\.php$/');

foreach ($files as $file) {
    try {
        // read the file that should be converted
        $code = file_get_contents($file);

        $code = "<?php\n\n" . $backporter->port($code);

        // write the converted file to the target directory
        file_put_contents(
            substr_replace($file->getPathname(), $outDir, 0, strlen($inDir)),
            $code
        );
    } catch (PhpParser\Error $e) {
        echo 'Parse Error: ', $e->getMessage();
    }
}
