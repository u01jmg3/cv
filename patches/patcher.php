<?php

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Patcher
{
    public static function startsWith($haystack, $needle){
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function postPackagePatching(PackageEvent $event){
        $installedPackage = $event->getOperation()->getPackage();
        $patchApplied     = false;

        if(Patcher::startsWith($installedPackage, 'dompdf/dompdf')){
            echo '> 1. Applying `dompdf.patch`...' . "\n";
            shell_exec('git apply --ignore-space-change --ignore-whitespace --whitespace=nowarn patches/dompdf.patch');
            $patchApplied = true;
        } else if(Patcher::startsWith($installedPackage, 'tecnickcom/tcpdf')){
            echo '> 1. Applying `tcpdf.patch`...' . "\n";
            shell_exec('git apply --ignore-space-change --ignore-whitespace --whitespace=nowarn patches/tcpdf.patch');
            $patchApplied = true;
        }

        if($patchApplied){
            echo '> 2. Success' . "\n\n";
        } else {
            echo '> Nothing to patch' . "\n\n";
        }
    }
}