<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Contact;

class ContactController
{
    public function list(Request $request): void
    {
        $contactList = Contact::find(['deleted' => false], ['sort' => ['createdAt' => 'desc']]);
        View::render('admin/pages/contact-list', [
            'pageTitle' => 'Liên hệ',
            'contactList' => $contactList,
        ]);
    }

    public function deletePatch(Request $request): void
    {

        Contact::updateOne(
            ['id' => $request->params['id']],
            [
                'deleted'   => true,
                'deletedAt' => date('Y-m-d H:i:s'),
            ]
        );

        \App\Core\Response::json([
            'code' => 'success',
            'message' => 'Xoá thông tin liên hệ thành công!'
        ]);
    }
}
