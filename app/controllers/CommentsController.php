<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;

class CommentsController extends BaseController
{
    public $model_name = 'Comments';

    public function indexAction()
    {
        $this->flash->error($this->t->_('Can not access!'));
        return $this->redirect('/');
    }

    public function showAction($relate_type, $relate_id, $only_comment = '0')
    {
        $this->view->setTemplateAfter('ajax');

        $model = $this->getModel();
        $comments = $model->getMany([
            'conditions' => 'relate_type = :relate_type: AND relate_id = :relate_id:',
            'bind' => [
                'relate_type' => $relate_type,
                'relate_id' => $relate_id
            ]
        ]);

        $this->view->relate_type = $relate_type;
        $this->view->relate_id = $relate_id;
        $this->view->comments = $comments;
        $this->view->only_comment = $only_comment;
    }

    public function insertAction()
    {
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $relate_type = $this->request->getPost('relate_type');
        $relate_id = $this->request->getPost('relate_id');

        if ($message && $relate_type && $relate_id) {
            /* @var $model \TrueCustomer\Models\Comments */
            $model = $this->getModel();
            $model->subject = $subject;
            $model->message = $message;
            $model->relate_type = $relate_type;
            $model->relate_id = $relate_id;
            $model->attachments = $this->request->getPost('attachments');
            if ($model->save()) {
                $result = [
                    'status' => 1,
                    'data' => $model->toArray()
                ];
            } else {
                $result = [
                    'status' => 0,
                    'messages' => $model->getMessages()
                ];
            }
        } else {
            $result = [
                'status' => 0,
                'messages' => ['Invalid Parameters']
            ];
        }

        $this->responseJson($result);
    }
}