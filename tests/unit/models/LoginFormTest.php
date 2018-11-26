<?php

namespace tests\models;

use app\models\form\LoginForm;

class LoginFormTest extends \Codeception\Test\Unit
{
    private $model;

    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginNoUser()
    {
        $this->model = new LoginForm([
            'username' => 'not_existing_username',
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
    }

    public function testLoginWrongPassword()
    {
        $this->model = new LoginForm([
            'username' => 'demo',
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
    }

    public function testLoginCorrect()
    {
        $this->model = new LoginForm([
            'username' => 'demo',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
    }

}
