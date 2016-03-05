<?php
use tests\codeception\frontend\_pages\teacher\IndexPage;
use tests\codeception\frontend\_pages\teacher\PaymentsIndexPage;
use tests\codeception\frontend\acceptance\_steps\TeacherSteps as TeacherTester;

$I = new TeacherTester($scenario);
$I->am('Teacher user');


$I->wantTo('have an index page with different sections');
$I->loginAsTeacher();
IndexPage::openBy($I);
$I->see('Private Teacher', 'b');


$I->wantTo('have a section to Manage my Students');
$I->seeElement(IndexPage::$studentsMenuLocators['main']);

$I->wantTo('have a subsection to List my Students');
$I->seeElement(IndexPage::$studentsMenuLocators['index']);

$I->wantTo('have a subsection to Add New Students');
$I->seeElement(IndexPage::$studentsMenuLocators['create']);


$I->wantTo("have a section to Manage the student's Payments");
$I->seeElement(IndexPage::$paymentsMenuLocators['main']);

$I->wantTo("have a subsection to Add New Payments");
$I->seeElement(IndexPage::$paymentsMenuLocators['index']);

$I->wantTo("have a subsection to List all the Payments made by Students");
$I->seeElement(IndexPage::$paymentsMenuLocators['create']);


$I->wantTo('have a section to see a Calendar with all the programed lessons');
$I->seeElement(IndexPage::$calendarMenuLocator);


$I->wantTo('have a section to edit my Profile');
$I->seeElement(IndexPage::$myProfileLocator);


PaymentsIndexPage::openBy($I);
$I->see('Payment List', 'h3');

$I->wantTo('have a Calendar on the Payments List section and see when a student has paid');
$I->seeElement(PaymentsIndexPage::$paymentOnCalendarLocator);

$I->wantTo('see the period a payment cover on the Calendar ');
$I->seeElement(PaymentsIndexPage::$dayCoveredByPaymentLocator);