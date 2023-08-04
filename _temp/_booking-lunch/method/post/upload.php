<?php

use Slim\Http\UploadedFile;

function moveUploadedFile($directory, UploadedFile $uploadedFile,$conf)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $filename = sprintf('%s.%0.8s', $basename, $extension);
    $file = $directory . DIRECTORY_SEPARATOR . $filename;
    $uploadedFile->moveTo($file);
    return $conf['link_file'].DIRECTORY_SEPARATOR. $conf['folder_upload']. DIRECTORY_SEPARATOR .$filename;
}
try {
    require('./shared/getToken.php');
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    if (count($uploadedFiles) > 0) {
        $filenames = [];
        foreach ($uploadedFiles as $file) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                $filenames[] = moveUploadedFile($directory, $file,$conf);
            }
        }
        if(count($filenames)===1) $filenames = $filenames[0];
        $results=['status'=>'success','time'=> date('Y-m-d H:i:s'), 'data'=>$filenames];
    } else {
        throw new Exception('File not found');
    }
} catch (Exception $e) {
    $obj = @json_decode($e->getMessage());
    if (is_object($obj)) {
        $httpStatus = $obj->status;
        $results = ['status' => 'error', 'message' => $obj->message, 'code' => $obj->code];
    } else {
        $httpStatus = 201;
        $results = ['status' => 'error', 'message' => $e->getMessage(), 'code' => 'fatalerror'];
    }
}
