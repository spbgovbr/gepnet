<?php

/**
 * Class App_Service_SemVer
 */
class App_Service_SemVer
{
    /**
     * @return bool|string
     */
    public static function getSemver()
    {
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.semver';
        if (file_exists($filePath)) {
            return file_get_contents($filePath) . ' - Portal SPB';
        } else {
            return 'x.x.x - Portal SPB';
        }
    }
    
    /**
     * @return bool|string
     */
    public static function getSemverBase()
    {
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.semver';
        if (file_exists($filePath)) {
            return str_replace(".", "_", file_get_contents($filePath));
        } else {
            return 'x_x_x';
        }
    }
}
