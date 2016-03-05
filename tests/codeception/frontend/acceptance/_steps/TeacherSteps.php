<?php
namespace tests\codeception\frontend\acceptance\_steps;

use tests\codeception\common\_pages\LoginPage;

class TeacherSteps extends \tests\codeception\frontend\AcceptanceTester
{
    public function loginAsTeacher()
    {
        $I = $this;
        $I->wantTo('Login as a Teacher');
        $loginPage = LoginPage::openBy($I);
        $loginPage->login('erau', 'password_0');
    }
}