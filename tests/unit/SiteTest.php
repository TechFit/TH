<?php

namespace tests;

use app\models\form\LoginForm;
use app\tests\fixtures\UserFixture;

class SiteTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return ['users' => UserFixture::className()];
    }

    public function testLoginByExistedUser()
    {
        $user = $this->tester->grabFixture('users', 'user1');

        $login = new LoginForm();
        $login->username = $user->username;
        expect($login->login())->equals(true);
    }

    public function testSignUpByExistedUser()
    {
        $login = new LoginForm();
        $login->username = 'Maks';
        expect($login->login())->equals(false);
    }
}