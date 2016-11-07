<?php


call_user_func(function(){
    $possibleVendorDirs = [
        __DIR__ .'/../../../vendor', // If required by other app
        __DIR__ .'/../vendor',       // If installed by self
    ];


    foreach ($possibleVendorDirs as $possibleVendorDir) {
        if (is_dir($possibleVendorDir)){
            $vendorDir = $possibleVendorDir;
            break;
        }
    }

    if (!isset($vendorDir)){
        throw new \RuntimeException("Cannot find the vendor directory! Did you run 'composer install'?");
    }

    if (PATH_SEPARATOR === '/'){
        // linux:
        $workermanDir = $vendorDir .'/workerman/workerman';
    } else {
        // windows:
        $workermanDir = $vendorDir .'/workerman/workerman-for-win';
    }

    if (!is_dir($workermanDir)){
        throw new \RuntimeException("Cannot find the workerman directory! Did you require workerman?");
    }

    $workerManNamespace = 'Workerman\\';
    $workerManNamespaceLen = strlen($workerManNamespace);

    // a simple psr-4 autoloader
    spl_autoload_register(function($class) use ($workermanDir, $workerManNamespace, $workerManNamespaceLen){
        if (substr_compare($class, $workerManNamespace, $workerManNamespaceLen) === 0){
            $file = $workermanDir . PATH_SEPARATOR . str_replace('\\', PATH_SEPARATOR, substr($class, $workerManNamespaceLen)) . '.php';
            if (is_file($file)){
                require_once($file);
                return true;
            }
        }

        return false;
    });
});
