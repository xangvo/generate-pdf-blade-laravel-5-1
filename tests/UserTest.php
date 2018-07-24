<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations; //nếu để nó comment out thì nó sẽ migrate hết  data sau khi chạy

    /**
     * A basic test example.
     *
     * @return void
     */

    /**
     * @author XangVo
     * @todo test should redirect to Login page when click link Login
     *
     * @access public
     */
    public function testShouldRedirectToLoginPageWhenClickLinkLogin()
    {
        // GIVEN

        // WHEN

        // THEN
        $this->visit('/')
            ->click('Login')
            ->seePageIs('/login');
    }

    /**
     * @author XangVo
     * @todo test should redirect to Register page when click link Register
     *
     * @access public
     */
    public function testShouldRedirectToRegisterPageWhenClickLinkRegister()
    {
        // GIVEN

        // WHEN

        // THEN
        $this->visit('/')
            ->click('Register')
            ->seePageIs('/register');
    }

    /**
     * @author XangVo
     * @todo test should return error message when password and password_confirmation were not match in register page
     *
     * @access public
     */
    public function testShouldReturnErrorMessageWhenPasswordAndPasswordConfirmationWereNotMatchInRegisterPage()
    {
        $this->visit('/register')
            ->type('sangtran', 'name')
            ->type('testmysendmail@gmail.com', 'email')
            ->type('123456', 'password')
            ->type('12345', 'password_confirmation')
            ->press('Register')
            ->see('The Password confirmation does not match.');
    }

    /**
     * @author XangVo
     * @todo test should insert data into db when register is ok
     *
     * @access public
     */
    public function testShouldInsertDataIntoDbWhenRegisterIsOk()
    {
        $this->visit('/register')
            ->type('sangtran', 'name')
            ->type('testmysendmail@gmail.com', 'email')
            ->type('123456', 'password')
            ->type('123456', 'password_confirmation')
            ->press('Register')
            ->see('Welcome!!!')
            ->dontsee('Register');

        $this->seeInDatabase('users', ['email' => 'testmysendmail@gmail.com']);
    }
    /**
     * @author XangVo
     * @todo test should return login page when access login page
     *
     * @access public
     */
    public function testShouldReturnLoginPageWhenAccessLoginPage()
    {
        // GIVEN

        // WHEN

        // THEN
        $this->visit('/login')
            ->see('E-Mail')
            ->see('Password');
    }

    
}
