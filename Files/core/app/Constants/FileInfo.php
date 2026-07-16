<?php

namespace App\Constants;

class FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo()
    {
        $data['verify'] = [
            'path'      => 'assets/verify'
        ];
        $data['requestDocuments'] = [
            'path'      => 'assets/request_documents',
        ];
        $data['default'] = [
            'path'      => 'assets/images/default.png',
        ];
        $data['ticket'] = [
            'path'      => 'assets/support',
        ];
        $data['logoIcon'] = [
            'path'      => 'assets/images/logo_icon',
        ];
        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['extensions'] = [
            'path'      => 'assets/images/extensions',
            'size'      => '36x36',
        ];
        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];
        $data['buyerProfile'] = [
            'path'      => 'assets/images/buyer/profile',
            'size'      => '350x300',
        ];
        $data['userProfile'] = [
            'path'      => 'assets/images/user/profile',
            'size'      => '350x300',
        ];
        $data['adminProfile'] = [
            'path'      => 'assets/admin/images/profile',
            'size'      => '400x400',
        ];
        $data['push'] = [
            'path'      => 'assets/images/push_notification',
        ];
        $data['maintenance'] = [
            'path'      => 'assets/images/maintenance',
            'size'      => '660x325',
        ];
        $data['language'] = [
            'path' => 'assets/images/language',
            'size' => '50x50'
        ];
        $data['gateway'] = [
            'path' => 'assets/images/gateway',
            'size' => ''
        ];
        $data['withdrawMethod'] = [
            'path' => 'assets/images/withdraw_method',
            'size' => ''
        ];
        $data['pushConfig'] = [
            'path'      => 'assets/admin',
        ];
        $data['badge'] = [
            'path'      => 'assets/images/badge',
            'size'      => '100x100',
        ];
        $data['category'] = [
            'path'      => 'assets/images/category',
            'size'      => '610x740',
        ];
        $data['portfolio'] = [
            'path'      => 'assets/images/user/portfolio',
            'size'      => '800x560',
        ];
        $data['message'] = [
            'path' => 'assets/conversation',
            'size' => '',
        ];
        $data['projectFile'] = [
            'path' => 'assets/project',
            'size' => '',
        ];
        $data['taskFile'] = [
            'path' => 'assets/task',
            'size' => '',
        ];

        return $data;
    }
}