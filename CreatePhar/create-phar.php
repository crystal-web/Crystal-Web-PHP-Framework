<?php
$appName = basename(__DIR__);
$srcRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . "src";
$buildRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . "build";

if (!is_dir($srcRoot) && !mkdir($srcRoot, 0777, true)) {
    die('Echec lors de la création du répertoire source...');
}
if (!is_dir($buildRoot) && !mkdir($buildRoot, 0777, true)) {
    die('Echec lors de la création du répertoire build...');
}

if (file_exists($buildRoot . "/" . $appName . ".phar")) {
    echo 'Remove old PHAR ' . $appName . ".phar - ";
    echo (@unlink($buildRoot . "/" . $appName . ".phar")) ? 'Ok' : 'Fail';
    echo PHP_EOL;
}

$phar = new Phar(
    $buildRoot . "/" . $appName . ".phar", 
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 
    $appName . ".phar"
    );
    

$phar->buildFromIterator(
    new RecursiveIteratorIterator(
     new RecursiveDirectoryIterator($srcRoot)),
    $srcRoot);

$phar->setStub($phar->createDefaultStub("index.php"));
$phar->stopBuffering();

echo "PHAR archive has been saved => " . $buildRoot . "/" . $appName . ".phar" . PHP_EOL;