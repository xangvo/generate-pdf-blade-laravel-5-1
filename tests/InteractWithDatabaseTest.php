<?php

class InteractWithDatabaseTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->artisan('db:seed', ['--class' => 'UserSingleRowSeeder']);
    }

    /**
     * @author XangVo
     * @todo test should redirect to dashboard when login info is OK
     *
     * @access public
     */
    public function testShouldRedirectToDashboardWhenLoginInfoIsOk()
    {
        $this->visit('/login')
            ->type('testmysendmail@gmail.com', 'email')
            ->type('123456', 'password')
            ->press('Login')
            ->seePageIs('/')
            ->see('Welcome!!!')
            ->dontSee('Login');
    }
}
