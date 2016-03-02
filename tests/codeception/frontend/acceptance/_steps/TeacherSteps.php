<?php
namespace tests\codeception\frontend\AcceptanceTester;

class TeacherSteps extends \tests\codeception\frontend\AcceptanceTester
{
    public function amATeacher()
    {
        $I = $this;
        $I->amOnPage('/teacher');
        $I->fillField('username', 'admin');
        $I->fillField('password', '');
        $I->click('Login');
    }
}