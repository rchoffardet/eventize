<?php
namespace Tests\Unit;

use App\Events\Event;
use App\Exceptions\AlreadyRegisteredException;
use App\Notifications\UserRegistered;
use App\Reservations\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_book_a_reservation()
    {
        // Arrange
        $event = factory(Event::class)->create();
        $user = factory(User::class)->create();

        // Act
        $reservation = Reservation::fromEventAndUser($event, $user);

        // Assert
        $this->assertEquals($user->id, $reservation->user_id);
    }

    /** @test */
    public function user_can_register_with_an_email_adress()
    {
        // Arrange
        $email = 'john.doe@isp.world';

        // Act
        User::RegisterWithEmail($email);

        // Assert
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function newly_registered_user_cannot_login()
    {
        // Arrange
        $email = 'john.doe@isp.world';

        // Act
        $user = User::RegisterWithEmail($email);

        // Assert
        $this->assertFalse($user->canLogin());

    }

    /** @test */
    public function an_email_is_sent_when_an_user_register()
    {
        // Arrange
        $email = 'john.doe@isp.world';
        Notification::fake();

        // Act
        $user = User::RegisterWithEmail($email);

        // Assert
        Notification::assertSentTo([$user], UserRegistered::class);
    }

    /** @test */
    public function user_can_confirm_his_registration()
    {
        // Arrange
        $email = 'john.doe@isp.world';
        $user = User::RegisterWithEmail($email);
        // Act
        $user->confirmRegistration($user->registration_token, 'strongPassw0rd!');

        // Assert
        $this->assertTrue(true);

    }

    /** @test */
    public function confirmed_user_can_login()
    {
        // Arrange
        $email = 'john.doe@isp.world';
        $user = User::RegisterWithEmail($email);

        // Act
        $user->confirmRegistration($user->registration_token, 'strongPassw0rd!');

        // Assert
        $this->assertTrue($user->canLogin());
    }

    /** @test */
    public function confirmed_user_cannot_confirm_registration_again()
    {
        // Arrange
        $email = 'john.doe@isp.world';
        $user = User::RegisterWithEmail($email);
        $user->confirmRegistration($user->registration_token, 'strongPassw0rd!');

        // Act & Assert
        $this->expectException(AlreadyRegisteredException::class);
        $user->confirmRegistration($user->registration_token, 'strongPassw0rd!');
    }

    /** @test */
    public function user_can_create_event()
    {
        // Arrange
        /** @var User $user */
        $user = factory(User::class)->create();

        // Act
        $user->createEvent(['amount' => 100]);

        // Assert
        $this->assertEquals(1, Event::count());

    }

    /** @test */
    public function user_can_subscribe_to_event()
    {
        // Arrange
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Event $event */
        $event = factory(Event::class)->create();

        // Act
        $user->subscribeToEvent($event);

        // Assert
        $this->assertEquals($event->subscribers->first()->id, $user->id);

    }
}
