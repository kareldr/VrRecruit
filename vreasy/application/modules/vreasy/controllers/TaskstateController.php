<?php

use Vreasy\Models\Taskstate;

class Vreasy_TaskstateController extends Vreasy_Rest_Controller
{
    protected $taskstate, $taskstates;

    public function preDispatch()
    {
        parent::preDispatch();
        $req = $this->getRequest();
        $action = $req->getActionName();
        $contentType = $req->getHeader('Content-Type');
        $rawBody     = $req->getRawBody();
        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $req->setParams(['taskstate' => Zend_Json::decode($rawBody)]);
            }
        }
        if($req->getParam('format') == 'json') {
            switch ($action) {
                case 'index':
                    $this->taskstates = Taskstate::where([]);
                    break;
                case 'new':
                    $this->taskstate = new Taskstate();
                    break;
                case 'create':
                    $this->taskstate = Taskstate::instanceWith($req->getParam('taskstate'));
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $this->taskstate = Taskstate::findOrInit($req->getParam('id'));
                    break;
            }
        }

        if( !in_array($action, [
                'index',
                'new',
                'create',
                'update',
                'destroy'
            ]) && !$this->taskstates && !$this->taskstate->id) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }

    }

    public function indexAction()
    {
        $this->view->taskstates = $this->taskstates;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->taskstates]);
    }

    public function newAction()
    {
        $this->view->taskstate = $this->taskstate;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->taskstate]);
    }

    public function createAction()
    {
        if ($this->taskstate->isValid() && $this->taskstate->save()) {
            $this->view->taskstate = $this->taskstate;
        } else {
            $this->view->errors = $this->taskstate->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function showAction()
    {
        $this->view->taskstate = $this->taskstate;
        $this->_helper->conditionalGet()->sendFreshWhen(
            ['etag' => [$this->taskstate]]
        );
    }

    public function updateAction()
    {
        Taskstate::hydrate($this->taskstate, $this->_getParam('taskstate'));
        if ($this->taskstate->isValid() && $this->taskstate->save()) {
            $this->view->taskstate = $this->taskstate;
        } else {
            $this->view->errors = $this->taskstate->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function destroyAction()
    {
        if($this->taskstate->destroy()) {
            $this->view->taskstate = $this->taskstate;
        } else {
            $this->view->errors = ['delete' => 'Unable to delete resource'];
        }
    }
}
