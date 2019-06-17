<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Download
 *
 * @author Administrador
 */
class App_Download
{
    /* List of File Types */

    private $_fileTypes = array(
        'swf' => 'application/x-shockwave-flash',
        'pdf' => 'application/pdf',
        'exe' => 'application/octet-stream',
        'zip' => 'application/zip',
        'doc' => 'application/msword',
        'docx' => 'application/docx',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'jpeg' => 'image/jpg',
        'jpg' => 'image/jpg',
        'rar' => 'application/rar',
        'ra' => 'audio/x-pn-realaudio',
        'ram' => 'audio/x-pn-realaudio',
        'ogg' => 'audio/x-pn-realaudio',
        'wav' => 'video/x-msvideo',
        'wmv' => 'video/x-msvideo',
        'avi' => 'video/x-msvideo',
        'asf' => 'video/x-msvideo',
        'divx' => 'video/x-msvideo',
        'mp3' => 'audio/mpeg',
        'mp4' => 'audio/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'mov' => 'video/quicktime',
        'swf' => 'video/quicktime',
        '3gp' => 'video/quicktime',
        'm4a' => 'video/quicktime',
        'aac' => 'video/quicktime',
        'm3u' => 'video/quicktime',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
    );

    /* extensions to stream */
    private $_extensionsToStream = array(
        'mp3',
        'm3u',
        'm4a',
        'mid',
        'ogg',
        'ra',
        'ram',
        'wm',
        'wav',
        'wma',
        'aac',
        '3gp',
        'avi',
        'mov',
        'mp4',
        'mpeg',
        'mpg',
        'swf',
        'wmv',
        'divx',
        'asf'
    );

    public function getHeaders($fileName, $size)
    {
        //$extension = strtolower(end(explode('.', $fileName)));
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        //Zend_Debug::dump($extension); exit;

        if (array_key_exists($extension, $this->_fileTypes)) {
            $contentType = $this->_fileTypes[$extension];
        }
        $contentType = 'application/force-download';

        $headers = array(
            "Cache-Control" => "public",
            "Content-Transfer-Encoding" => "binary",
            "Content-Type" => $contentType,
        );

        $contentDisposition = 'attachment';

        $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
        //$headers[] = array("Content-Disposition" => "$contentDisposition;filename=\"$fileName\"");
        $headers["Content-Disposition"] = "$contentDisposition;filename=\"$fileName\"";
        // $headers["Pragma"]              = 'anytextexeptno-cache';
        //header('Pragma: anytextexeptno-cache', true);

        $headers["Accept-Ranges"] = "bytes";
        $range = 0;

        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
            str_replace($range, "-", $range);
            $size2 = $size - 1;
            $new_length = $size - $range;
            //$headers[]  = "HTTP/1.1 206 Partial Content";
            $headers["Content-Length"] = $new_length;
            //$headers["Content-Range"]  = "bytes $range$size2/$size";
        } else {
            $size2 = $size - 1;
            //$headers["Content-Range"]  = "bytes 0-$size2/$size";
            $headers["Content-Length"] = $size;
        }

        return $headers;
    }

}