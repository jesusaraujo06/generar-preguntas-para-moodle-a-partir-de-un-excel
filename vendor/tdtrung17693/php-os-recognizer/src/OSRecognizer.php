<?php
namespace TdTrung\OSRecognizer;

class OSRecognizer
{
    private $osMap = [
        '/win/i'      =>  'windows',
        '/darwin/i'   =>  'mac',
        '/linux/i'    =>  'linux',
        '/freebsd/i'  =>  'freebsd',
    ];

    private $platform = 'unknown';
    private $release = '0';

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $osname = php_uname();

        foreach ($this->osMap as $pat => $name) {
            if (preg_match($pat, $osname)) {
                $this->platform = $name;
                break;
            }
        }

        if ($this->platform == 'windows') {
            $return = shell_exec('ver');
            preg_match('/Version (\d+).(\d+).(\d+)/', $return, $matches);
            $this->release = "{$matches[1]}.{$matches[2]}.{$matches[3]}";
        } else if ($this->platform == 'mac') {
            $return = shell_exec('system_profiler SPSoftwareDataType');
            preg_match('/System Version:\s*.*\s\((\w+)\)\n\s+Kernel Version:.*Darwin\s*([\w.]+)$/', $return, $matches);
            $this->release = "{$matches[2]}.{$matches[1]}";
        } else {
            $this->release = php_uname('r');
        }
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getRelease()
    {
        return $this->release;
    }

    public function getSupportedOS()
    {
        return array_values($this->osMap);
    }
}
