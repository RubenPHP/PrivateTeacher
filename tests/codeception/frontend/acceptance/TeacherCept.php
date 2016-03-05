<?php
use tests\codeception\frontend\_pages\teacher\IndexPage;
use tests\codeception\frontend\acceptance\_steps\TeacherSteps as TeacherTester;

$I = new TeacherTester($scenario);
$I->am('Teacher user');
$I->wantTo('have a index page with different sections');
$I->loginAsTeacher();
IndexPage::openBy($I);
$I->see('Private Teacher','b');

$I->wantTo('have a section to Manage my Students');
$I->see('Manage Students','span');
$I->wantTo('have a subsection to List my Students');
$I->see('List Students','a');
$I->wantTo('have a subsection to Add New Students');
$I->see('Add New Student','a');
