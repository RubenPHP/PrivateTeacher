<?php
namespace tests\codeception\frontend\_pages\teacher;

use \yii\codeception\BasePage;

/**
 * Represents teacher index page
 * @property \tests\codeception\frontend\AcceptanceTester|\tests\codeception\frontend\FunctionalTester $actor
 */
class IndexPage extends BasePage
{
    public static $studentsMenuLocators = [
        'main' => '#students-menu',
        'index' => '#students-index',
        'create' => '#student-create'
    ];
    public static $paymentsMenuLocators = [
        'main' => '#payments-menu',
        'index' => '#payments-index',
        'create' => '#payment-create'
    ];
    public static $calendarMenuLocator = '#calendar-lessons-view';
    public static $myProfileLocator = '#my-profile-update';

    public $route = 'teacher';

}