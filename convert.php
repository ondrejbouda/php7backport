<?php

require_once('vendor/autoload.php');

use Bouda\Php7Backport\DirectoryBackporter;


$webserver = php_sapi_name() !== 'cli'; 

echo $webserver ? "<pre>" : "";


echo "\n";
echo "Php7backporter by Ondrej Bouda\n";
echo "==============================\n";


// web
if ($webserver)
{
    if (isset($_GET['source']) && isset($_GET['destination']))
    {
        $source = $_GET['source'];
        $destination = $_GET['destination'];
    }
    else
    {
        echo "You must define source and destination.\n";
        echo "Use GET variables 'source' and 'destination'.\n";
        die();
    }
}
// command line
else
{
    if (isset($_SERVER['argv'][1]) && isset($_SERVER['argv'][2]))
    {
        $source = $_SERVER['argv'][1];
        $destination = $_SERVER['argv'][2];
    }
    else
    {
        echo "You must define source and destination.\n";
        echo "Usage: " . $_SERVER['argv'][0] . " <source> <destination>\n";
        die();
    }
}


echo "Backporting directory '" . $source . "' to '" . $destination . "'.\n";

$backporter = new DirectoryBackporter($source, $destination, true);
