<?php

namespace Bouda\Php7Backport;


class DirectoryBackporter
{
    public function __construct($sourceDir, $destinationDir = false, $output = false)
    {
        if (!$destinationDir)
        {
            $destinationDir = $sourceDir;
        }

        $backporter = new Backporter;

        $iterator = 
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sourceDir));

        foreach ($iterator as $file)
        {
            if (!$file->isFile() || !in_array($file->getExtension(), ['php', 'phpt', 'phtml'], TRUE))
            {
                continue;
            }

            $newPath = preg_replace("#^$sourceDir#", $destinationDir, $file);

            $dir = dirname($newPath);
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
                if ($output) echo "mkdir $newPath\n";
            }

            $original = file_get_contents($file);

            file_put_contents($newPath, $backporter->port($original));

            if ($output) echo "ported file $newPath\n";
        }
    }
}
