<?php
namespace tests\codeception\frontend\_pages\teacher;

use \yii\codeception\BasePage;

/**
 * Represents teacher index page
 * @property \tests\codeception\frontend\AcceptanceTester|\tests\codeception\frontend\FunctionalTester $actor
 */
class StudentsIndexPage extends BasePage
{
    public $route = 'teacher/default/list-students';
}