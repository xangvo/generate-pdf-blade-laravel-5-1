<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @coversNothing
 */
class TestRegistForm extends TestCase
{
    /**
     * A basic test example.
     */
    public function testCaseOne()
    {
        $this->assertTrue(true);
    }

    public function testRegistPage()
    {
        $this->visit('/regist-input');
        $this->see('name');
        $this->see('email');
        $this->see('password');
        $this->see('password_confirmation');
    }

    public function testRegistPageWithOldInput()
    {
        //Giả lập dữ liệu session cho input
        $this->withSession([
            '_old_input' => [
                'name' => 'old name',
                'email' => 'old email',
            ],
        ]);
        $this->visit('/regist-input');
        $this->seeInField('name', 'old name');
        $this->seeInField('email', 'old email');
    }

    public function testRegistFormWithOldInputAndError()
    {
        //Simulate error message
        $message = new Illuminate\Support\MessageBag();
        $message->add('name', 'error name');
        $message->add('email', 'error email');
        $message->add('password', 'error password');
        $errors_bag = new Illuminate\Support\ViewErrorBag();
        $errors_bag->put('default', $message);

        //Simulate old input & errors
        $this->withSession([
            '_old_input' => [
                'name' => 'old name',
                'email' => 'old email',
            ],
            'errors' => $errors_bag,
        ]);
        $this->visit('/regist-input');
        $this->seeInField('name', 'old name');
        $this->seeInField('email', 'old email');
    }

    public function testConfirmPagewithInputOK()
    {
        //simulate token for post request
        $this->withSession(['_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE']);
        //simulate post request
        $this->call('POST', '/regist-confirm', [
            '_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE',
            'name' => 'Taylor',
            'email' => 'baoluu@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $this->seePageIs('/regist-confirm');
    }

    public function testRedirectWhenValidateFail()
    {
        //simulate token for post request
        $this->withSession(['_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE']);
        //simulate post request
        $this->call('POST', '/regist-confirm', [
            '_token' => 'test_token',
            'name' => '',
            'email' => '',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $this->assertRedirectedTo('/regist-input');
    }

    public function testCompletePagewithInputOK()
    {
        $name = 'bao';
        $email = 'baoluu@gmail.com';
        //simulate token for post request
        $this->withSession(['_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE']);
        //simulate post request
        $this->call('POST', '/regist-complete', [
            '_token' => 'test_token',
            'name' => $name,
            'email' => $email,
            'password' => '123456',
        ]);

        $this->seeInDataBase('users', [
            'name' => $name,
            'email' => $email,
        ]);
        $this->seePageIs('/regist-complete');
    }

    public function testCompleteFailwithInputNG()
    {
        $name = '';
        $email = 'baoluu@gmail.com';
        //simulate token for post request
        $this->withSession(['_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE']);
        //simulate post request
        $this->call('POST', '/regist-complete', [
            '_token' => 'test_token',
            'name' => $name,
            'email' => $email,
            'password' => '123456',
        ]);

        $this->dontSeeInDatabase('users', [
            'name' => $name,
            'email' => $email,
        ]);
        $this->followRedirects();

        $this->seePageIs('/regist-input');
    }

    public function testNameEmpty()
    {
        $data = [
            'name' => '',
            '_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE',
        ];

        //test rule run right
        $rule = ['name' => 'required'];
        $validator = Validator::make($data, $rule);
        $is_fail = $validator->fails();
        $this->assertTrue($is_fail);

        //test controller run rule right
        $this->withSession(['_token' => 'dYSiA1gnXDpSb8rCXWRBNGkIJCLwgWqNQxEYcKOE']);
        $this->call('POST', '/regist-confirm', $data);
        $this->followRedirects();
        $this->seePageIs('regist-input');
        $error_message = $validator->messages()->all();
        foreach ($error_message as $msg) {
            $this->see($msg);
        }
    }

    public function createUserWithPrefix($prefix, $name, $email, $password)
    {
        User::create([
            'name' => $prefix.$name,
            'email' => $prefix.$email,
            'password' => bcrypt($password),
        ]);
    }

    public function testCreateUserWithPrefix()
    {
        $prefix = 'your_prefix';
        $name = 'name';
        $email = 'email';
        $password = '123456';

        $user = new User();
        $user->createUserWithPrefix($prefix, $name, $email, $password);

        $this->seeInDatabase('users', ['name' => $prefix.$name, 'email' => $prefix.$email]);
    }

    public function testTypeFormToConfirmPage()
    {
        $this->visit('regist-input')
            ->type('name', 'name')
            ->type('bao.luu@tctav.com', 'email')
            ->type('123456', 'password')
            ->press('Submit');
        $this->seePageIs('regist-confirm'); // kết quả mong đợi sẽ thấy trang confirm
    }
}
