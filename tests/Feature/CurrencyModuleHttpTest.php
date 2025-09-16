<?php

use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('guests cannot access currency management', function () {
    $this->post(route('logout'));
    
    $this->get(route('filament.hisabat.resources.currencies.index'))
        ->assertRedirect(route('filament.hisabat.auth.login'));
});

test('authenticated users can view currency list', function () {
    $this->get(route('filament.hisabat.resources.currencies.index'))
        ->assertOk()
        ->assertSee('Currencies');
});

test('can create a new currency via form submission', function () {
    $currencyData = [
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => true,
    ];

    $this->post(route('filament.hisabat.resources.currencies.store'), $currencyData)
        ->assertRedirect();

    assertDatabaseHas('currencies', $currencyData);
});

test('can create currency without symbol', function () {
    $currencyData = [
        'code' => 'EUR',
        'name' => 'Euro',
        'is_base' => false,
    ];

    $this->post(route('filament.hisabat.resources.currencies.store'), $currencyData)
        ->assertRedirect();

    assertDatabaseHas('currencies', $currencyData);
});

test('validates required fields', function () {
    $this->post(route('filament.hisabat.resources.currencies.store'), [])
        ->assertSessionHasErrors(['code', 'name']);
});

test('validates currency code length', function () {
    $currencyData = [
        'code' => 'USDD', // Too long
        'name' => 'US Dollar',
    ];

    $this->post(route('filament.hisabat.resources.currencies.store'), $currencyData)
        ->assertSessionHasErrors(['code']);
});

test('can update existing currency', function () {
    $currency = Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => false,
    ]);

    $updateData = [
        'code' => 'USD',
        'name' => 'United States Dollar',
        'symbol' => 'USD',
        'is_base' => true,
    ];

    $this->put(route('filament.hisabat.resources.currencies.update', $currency), $updateData)
        ->assertRedirect();

    $currency->refresh();
    expect($currency->name)->toBe('United States Dollar');
    expect($currency->symbol)->toBe('USD');
    expect($currency->is_base)->toBeTrue();
});

test('prevents duplicate currency codes', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);

    $duplicateData = [
        'code' => 'USD',
        'name' => 'Another US Dollar',
    ];

    $this->post(route('filament.hisabat.resources.currencies.store'), $duplicateData)
        ->assertSessionHasErrors(['code']);
});

test('can only have one base currency', function () {
    Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar', 'is_base' => true]);

    $newBaseData = [
        'code' => 'EUR',
        'name' => 'Euro',
        'is_base' => true,
    ];

    $this->post(route('filament.hisabat.resources.currencies.store'), $newBaseData)
        ->assertRedirect();

    // Check that only the new currency is base
    expect(Currency::where('is_base', true)->count())->toBe(1);
    expect(Currency::where('code', 'EUR')->first()->is_base)->toBeTrue();
    expect(Currency::where('code', 'USD')->first()->is_base)->toBeFalse();
});

test('can delete currency', function () {
    $currency = Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);

    $this->delete(route('filament.hisabat.resources.currencies.destroy', $currency))
        ->assertRedirect();

    assertDatabaseMissing('currencies', ['id' => $currency->id]);
});

test('currency code is automatically uppercased', function () {
    $currencyData = [
        'code' => 'qar',
        'name' => 'Qatari Riyal',
        'symbol' => 'ر.ق',
    ];

    $this->post(route('filament.hisabat.resources.currencies.store'), $currencyData)
        ->assertRedirect();

    assertDatabaseHas('currencies', [
        'code' => 'QAR',
        'name' => 'Qatari Riyal',
        'symbol' => 'ر.ق',
    ]);
});

test('can view currency edit form', function () {
    $currency = Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'is_base' => true,
    ]);

    $this->get(route('filament.hisabat.resources.currencies.edit', $currency))
        ->assertOk()
        ->assertSee('USD')
        ->assertSee('US Dollar')
        ->assertSee('$');
});

test('can view currency create form', function () {
    $this->get(route('filament.hisabat.resources.currencies.create'))
        ->assertOk()
        ->assertSee('Create Currency');
});
