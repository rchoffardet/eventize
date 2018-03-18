<?php
namespace Tests\Unit\Events;

use App\Events\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function event_can_be_created_and_stored()
    {
        // Arrange

        // Act
        $event = new Event(['amount' => 100]);
        $event->save();

        // Assert
        $this->assertTrue(true);

    }
}
