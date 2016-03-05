<?php
namespace tests\codeception\frontend\_pages\teacher;

use \yii\codeception\BasePage;

/**
 * Represents teacher index page
 * @property \tests\codeception\frontend\AcceptanceTester|\tests\codeception\frontend\FunctionalTester $actor
 */
class PaymentsIndexPage extends BasePage
{
    public static $paymentOnCalendarLocator = '.payment-made-by-student';
    public static $dayCoveredByPaymentLocator = '.day-covered-by-payment';

    public $route = 'teacher/default/list-payments';
}