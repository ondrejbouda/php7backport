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
        new \CallbackFilterIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sourceDir)), function($file)
        {
            return $file;
        });

        foreach ($iterator as $file)
        {
            $newPath = preg_replace("#^" . preg_quote($sourceDir) . "#", $destinationDir, $file);

            if ($file->isDir() && !file_exists($newPath))
            {
                mkdir($newPath);

                if ($output) echo "mkdir $newPath\n";
            }
            elseif ($file->isFile() && in_array($file->getExtension(), ['php', 'phpt', 'phtml'], TRUE))
            {
                $original = file_get_contents($file);

                file_put_contents($newPath, $backporter->port($original));

                if ($output) echo "ported file $newPath\n";
            }
            
        }
    }
}
