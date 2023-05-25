<?php


use Phalcon\Mvc\Controller;


class LoginController extends Controller
{
    public function indexAction()
    {
        // default action
    }
    public function loginAction()
    {

        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $pass = $this->request->getPost("password");

            $collection = $this->mongo->Users;
            $data = $collection->findOne(["email" => $email, "password" => $pass]);

            if ($data) {
                $this->response->redirect("webhook");
            } else {
                echo "Wrong Credentials";
                die;
            }
        }
    }
}
