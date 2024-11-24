<?php

/* Convert upload error code to a message. */


class ModelUploadErrors
{

    public static function errorToMessage($error)
    {
        return [
            UPLOAD_ERR_OK           => "File uploaded successfully.",                       // 0
            UPLOAD_ERR_INI_SIZE     => "The file is too big.",                              // 1
            UPLOAD_ERR_FORM_SIZE    => "The file is too big.",                              // 2
            UPLOAD_ERR_PARTIAL      => "The file was only partially uploaded.",             // 3
            UPLOAD_ERR_NO_FILE      => "No file was uploaded.",                             // 4
            UPLOAD_ERR_NO_TMP_DIR   => "The temporary folder is missing.",                  // 6
            UPLOAD_ERR_CANT_WRITE   => "Failed to write the file to the disk",              // 7
            UPLOAD_ERR_EXTENSION    => "Upload failed due to an error in an extension."     // 8
        ][$error];
    }

}
