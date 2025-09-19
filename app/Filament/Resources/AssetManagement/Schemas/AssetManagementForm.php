<?php

namespace App\Filament\Resources\AssetManagement\Schemas;

use App\Models\AccountType;
use App\Models\Currency;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AssetManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('month')
                                    ->label('Month')
                                    ->options([
                                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                    ])
                                    ->searchable()
                                    ->default(now()->month)
                                    ->required(),
                                Select::make('year')
                                    ->label('Year')
                                    ->options(range(2020, now()->year + 5))
                                    ->searchable()
                                    ->default(now()->year)
                                    ->required(),
                            ]),
                        TextInput::make('notes')
                            ->label('Notes')
                            ->placeholder('Add any notes about this month\'s assets...'),
                        Hidden::make('user_id')
                            ->default(fn() => Auth::id()),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Current Accounts')
                    ->description('Manage your bank accounts, cash in hand, and other current accounts')
                    ->schema([
                        Repeater::make('accounts')
                            ->label('Accounts')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('account_type_id')
                                            ->label('Account Type')
                                            ->options(AccountType::forUser(Auth::id())->pluck('name', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('description')
                                                    ->maxLength(255),
                                            ])
                                            ->createOptionUsing(function (array $data): int {
                                                return AccountType::create([
                                                    'user_id' => Auth::id(),
                                                    'name' => $data['name'],
                                                    'description' => $data['description'] ?? null,
                                                    'is_default' => false,
                                                ])->id;
                                            }),
                                        TextInput::make('account_name')
                                            ->label('Account Name')
                                            ->placeholder('e.g., Main Account, Savings Account')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('currency')
                                            ->label('Currency')
                                            ->options(Currency::pluck('name', 'code'))
                                            ->searchable()
                                            ->default('QAR')
                                            ->required(),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->prefix(fn($get) => Currency::where('code', $get('currency'))->first()?->symbol ?? '$')
                                            ->required()
                                            ->minValue(0),
                                        TextInput::make('notes')
                                            ->label('Notes')
                                            ->placeholder('Optional notes about this account'),
                                    ]),
                            ])
                            ->addActionLabel('Add Account')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['account_name'] ?? null)
                            ->defaultItems(0),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Lent Money')
                    ->description('Money you have lent to friends and family')
                    ->schema([
                        Repeater::make('lent_money')
                            ->label('Lent Money')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('friend_name')
                                            ->label('Friend/Person Name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->prefix(fn($get) => Currency::where('code', $get('currency'))->first()?->symbol ?? '$')
                                            ->required()
                                            ->minValue(0),
                                        Select::make('currency')
                                            ->label('Currency')
                                            ->options(Currency::pluck('name', 'code'))
                                            ->searchable()
                                            ->default('QAR')
                                            ->required(),
                                    ]),
                                TextInput::make('notes')
                                    ->label('Notes')
                                    ->placeholder('Optional notes about this loan'),
                            ])
                            ->addActionLabel('Add Lent Money')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['friend_name'] ?? null)
                            ->defaultItems(0),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Borrowed Money')
                    ->description('Money you have borrowed from friends and family')
                    ->schema([
                        Repeater::make('borrowed_money')
                            ->label('Borrowed Money')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('friend_name')
                                            ->label('Friend/Person Name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->prefix(fn($get) => Currency::where('code', $get('currency'))->first()?->symbol ?? '$')
                                            ->required()
                                            ->minValue(0),
                                        Select::make('currency')
                                            ->label('Currency')
                                            ->options(Currency::pluck('name', 'code'))
                                            ->searchable()
                                            ->default('QAR')
                                            ->required(),
                                    ]),
                                TextInput::make('notes')
                                    ->label('Notes')
                                    ->placeholder('Optional notes about this loan'),
                            ])
                            ->addActionLabel('Add Borrowed Money')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['friend_name'] ?? null)
                            ->defaultItems(0),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Investments')
                    ->description('Your investment holdings')
                    ->schema([
                        Repeater::make('investments')
                            ->label('Investments')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('investment_name')
                                            ->label('Investment Name')
                                            ->placeholder('e.g., Apple Stock, S&P 500 Fund')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('currency')
                                            ->label('Currency')
                                            ->options(Currency::pluck('name', 'code'))
                                            ->searchable()
                                            ->default('QAR')
                                            ->required(),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('amount')
                                            ->label('Current Value')
                                            ->numeric()
                                            ->prefix(fn($get) => Currency::where('code', $get('currency'))->first()?->symbol ?? '$')
                                            ->required()
                                            ->minValue(0),
                                        TextInput::make('notes')
                                            ->label('Notes')
                                            ->placeholder('Optional notes about this investment'),
                                    ]),
                            ])
                            ->addActionLabel('Add Investment')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['investment_name'] ?? null)
                            ->defaultItems(0),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Unusable Deposits')
                    ->description('Deposits that are currently unusable but part of your assets')
                    ->schema([
                        Repeater::make('deposits')
                            ->label('Deposits')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('deposit_name')
                                            ->label('Deposit Name')
                                            ->placeholder('e.g., Bank FD, Apartment Security')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('currency')
                                            ->label('Currency')
                                            ->options(Currency::pluck('name', 'code'))
                                            ->searchable()
                                            ->default('QAR')
                                            ->required(),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->prefix(fn($get) => Currency::where('code', $get('currency'))->first()?->symbol ?? '$')
                                            ->required()
                                            ->minValue(0),
                                        TextInput::make('notes')
                                            ->label('Notes')
                                            ->placeholder('Optional notes about this deposit'),
                                    ]),
                            ])
                            ->addActionLabel('Add Deposit')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['deposit_name'] ?? null)
                            ->defaultItems(0),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
