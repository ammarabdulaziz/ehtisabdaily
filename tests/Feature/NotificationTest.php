<?php

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Set the current panel for Filament
    Filament::setCurrentPanel('hisabat');
});

it('can send database notifications', function () {
    // Send a database notification
    Notification::make()
        ->title('Test Notification')
        ->body('This is a test notification')
        ->success()
        ->sendToDatabase($this->user);
    
    // Check that the notification was created in the database
    $this->assertDatabaseHas('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $this->user->id,
        'type' => 'Filament\\Notifications\\DatabaseNotification',
    ]);
});

it('can send session notifications', function () {
    // Send a session notification
    Notification::make()
        ->title('Test Session Notification')
        ->body('This is a test session notification')
        ->success()
        ->send();
    
    // Check that the notification was sent to the session
    Notification::assertNotified('Test Session Notification');
});

it('can send notifications with actions', function () {
    // Send a notification with an action
    Notification::make()
        ->title('Notification with Action')
        ->body('This notification has an action button')
        ->success()
        ->actions([
            \Filament\Actions\Action::make('view')
                ->label('View Details')
                ->button(),
        ])
        ->send();
    
    // Check that the notification was sent
    Notification::assertNotified('Notification with Action');
});
