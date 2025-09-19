<?php

namespace App\Filament\Resources\AssetManagement\Schemas;

use App\Models\AccountType;
use App\Models\Friend;
use App\Models\InvestmentType;
use App\Models\DepositType;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Auth;

class AssetManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make()
                            ->columns(['default' => 2])
                            ->schema([
                                Select::make('month')
                                    ->label('Month')
                                    ->options([
                                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                    ])
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
                    ->columnSpanFull(),

                Tabs::make('Asset Management')
                    ->tabs([
                        Tabs\Tab::make('Current Accounts')
                            ->schema([
                                Select::make('new_account_type')
                                    ->label('Create New Account Type')
                                    ->placeholder('Select to create a new account type')
                                    ->options([])
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('description')
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data): string {
                                        return AccountType::create([
                                            'user_id' => Auth::id(),
                                            'name' => $data['name'],
                                            'description' => $data['description'] ?? null,
                                            'is_default' => false,
                                        ])->name;
                                    })
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Create Account Type')
                                            ->modalSubmitActionLabel('Create Account Type')
                                            ->modalWidth('lg');
                                    }),
                                Repeater::make('accounts')
                                    ->label('Accounts')
                                    ->schema([
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                Select::make('account_type_id')
                                                    ->label('Account Type')
                                                    ->relationship(
                                                        name: 'accountType',
                                                        titleAttribute: 'name',
                                                        modifyQueryUsing: fn ($query) => $query->whereUserId(Auth::id())
                                                    )
                                                    ->searchable()
                                                    ->required()
                                                    ->preload()
                                                    ->live(),
                                                TextInput::make('exchange_rate')
                                                    ->label('Exchange Rate')
                                                    ->placeholder('Enter exchange rate (e.g., 24.20 for QAR to INR)')
                                                    ->default(1.000000)
                                                    ->required()
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->minValue(0.000001),
                                            ]),
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                TextInput::make('actual_amount')
                                                    ->label('Actual Amount')
                                                    ->placeholder('Enter amount in original currency')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0),
                                                TextInput::make('notes')
                                                    ->label('Notes')
                                                    ->placeholder('Optional notes about this account'),
                                            ]),
                                        Placeholder::make('calculated_amount')
                                            ->label('Calculated Amount (QAR)')
                                            ->content(function ($get) {
                                                $actualAmount = $get('actual_amount');
                                                $exchangeRate = $get('exchange_rate');
                                                
                                                if ($actualAmount && $exchangeRate && $exchangeRate > 0) {
                                                    $calculated = $actualAmount / $exchangeRate;
                                                    return number_format($calculated, 2);
                                                }
                                                
                                                return '0.00';
                                            })
                                            ->live()
                                            ->extraAttributes(['class' => 'text-sm font-medium text-gray-700 bg-gray-50 p-2 rounded']),
                                    ])
                                    ->addActionLabel('Add Account')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => AccountType::find($state['account_type_id'])?->name ?? 'New Account')
                                    ->defaultItems(0),
                            ]),

                        Tabs\Tab::make('Lent Money')
                            ->schema([
                                Select::make('new_friend_lent')
                                    ->label('Create New Friend')
                                    ->placeholder('Select to create a new friend')
                                    ->options([])
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('description')
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data): string {
                                        return Friend::create([
                                            'user_id' => Auth::id(),
                                            'name' => $data['name'],
                                            'description' => $data['description'] ?? null,
                                        ])->name;
                                    })
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Create Friend')
                                            ->modalSubmitActionLabel('Create Friend')
                                            ->modalWidth('lg');
                                    }),
                                Repeater::make('lent_money')
                                    ->label('Lent Money')
                                    ->schema([
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                Select::make('friend_id')
                                                    ->label('Friend/Person Name')
                                                    ->relationship(
                                                        name: 'friend',
                                                        titleAttribute: 'name',
                                                        modifyQueryUsing: fn ($query) => $query->whereUserId(Auth::id())
                                                    )
                                                    ->searchable()
                                                    ->required()
                                                    ->live()
                                                    ->preload(),
                                                TextInput::make('exchange_rate')
                                                    ->label('Exchange Rate')
                                                    ->placeholder('Enter exchange rate (e.g., 24.20 for QAR to INR)')
                                                    ->default(1.000000)
                                                    ->required()
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->minValue(0.000001),
                                            ]),
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                TextInput::make('actual_amount')
                                                    ->label('Actual Amount')
                                                    ->placeholder('Enter amount in original currency')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0),
                                                TextInput::make('notes')
                                                    ->label('Notes')
                                                    ->placeholder('Optional notes about this loan'),
                                            ]),
                                        Placeholder::make('calculated_amount')
                                            ->label('Calculated Amount (QAR)')
                                            ->content(function ($get) {
                                                $actualAmount = $get('actual_amount');
                                                $exchangeRate = $get('exchange_rate');
                                                
                                                if ($actualAmount && $exchangeRate && $exchangeRate > 0) {
                                                    $calculated = $actualAmount / $exchangeRate;
                                                    return number_format($calculated, 2);
                                                }
                                                
                                                return '0.00';
                                            })
                                            ->live()
                                            ->extraAttributes(['class' => 'text-sm font-medium text-gray-700 bg-gray-50 p-2 rounded']),
                                    ])
                                    ->addActionLabel('Add Lent Money')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => isset($state['friend_id']) ? Friend::find($state['friend_id'])?->name ?? 'New Friend' : 'New Friend')
                                    ->defaultItems(0),
                            ]),

                        Tabs\Tab::make('Borrowed Money')
                            ->schema([
                                Select::make('new_friend_borrowed')
                                    ->label('Create New Friend')
                                    ->placeholder('Select to create a new friend')
                                    ->options([])
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('description')
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data): string {
                                        return Friend::create([
                                            'user_id' => Auth::id(),
                                            'name' => $data['name'],
                                            'description' => $data['description'] ?? null,
                                        ])->name;
                                    })
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Create Friend')
                                            ->modalSubmitActionLabel('Create Friend')
                                            ->modalWidth('lg');
                                    }),
                                Repeater::make('borrowed_money')
                                    ->label('Borrowed Money')
                                    ->schema([
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                Select::make('friend_id')
                                                    ->label('Friend/Person Name')
                                                    ->relationship(
                                                        name: 'friend',
                                                        titleAttribute: 'name',
                                                        modifyQueryUsing: fn ($query) => $query->whereUserId(Auth::id())
                                                    )
                                                    ->searchable()
                                                    ->required()
                                                    ->live()
                                                    ->preload(),
                                                TextInput::make('exchange_rate')
                                                    ->label('Exchange Rate')
                                                    ->placeholder('Enter exchange rate (e.g., 24.20 for QAR to INR)')
                                                    ->default(1.000000)
                                                    ->required()
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->minValue(0.000001),
                                            ]),
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                TextInput::make('actual_amount')
                                                    ->label('Actual Amount')
                                                    ->placeholder('Enter amount in original currency')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0),
                                                TextInput::make('notes')
                                                    ->label('Notes')
                                                    ->placeholder('Optional notes about this loan'),
                                            ]),
                                        Placeholder::make('calculated_amount')
                                            ->label('Calculated Amount (QAR)')
                                            ->content(function ($get) {
                                                $actualAmount = $get('actual_amount');
                                                $exchangeRate = $get('exchange_rate');
                                                
                                                if ($actualAmount && $exchangeRate && $exchangeRate > 0) {
                                                    $calculated = $actualAmount / $exchangeRate;
                                                    return number_format($calculated, 2);
                                                }
                                                
                                                return '0.00';
                                            })
                                            ->live()
                                            ->extraAttributes(['class' => 'text-sm font-medium text-gray-700 bg-gray-50 p-2 rounded']),
                                    ])
                                    ->addActionLabel('Add Borrowed Money')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => isset($state['friend_id']) ? Friend::find($state['friend_id'])?->name ?? 'New Friend' : 'New Friend')
                                    ->defaultItems(0),
                            ]),

                        Tabs\Tab::make('Investments')
                            ->schema([
                                Select::make('new_investment_type')
                                    ->label('Create New Investment Type')
                                    ->placeholder('Select to create a new investment type')
                                    ->options([])
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('description')
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data): string {
                                        return InvestmentType::create([
                                            'user_id' => Auth::id(),
                                            'name' => $data['name'],
                                            'description' => $data['description'] ?? null,
                                        ])->name;
                                    })
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Create Investment Type')
                                            ->modalSubmitActionLabel('Create Investment Type')
                                            ->modalWidth('lg');
                                    }),
                                Repeater::make('investments')
                                    ->label('Investments')
                                    ->schema([
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                Select::make('investment_type_id')
                                                    ->label('Investment Type')
                                                    ->relationship(
                                                        name: 'investmentType',
                                                        titleAttribute: 'name',
                                                        modifyQueryUsing: fn ($query) => $query->whereUserId(Auth::id())
                                                    )
                                                    ->searchable()
                                                    ->required()
                                                    ->live()
                                                    ->preload(),
                                                TextInput::make('exchange_rate')
                                                    ->label('Exchange Rate')
                                                    ->placeholder('Enter exchange rate (e.g., 24.20 for QAR to INR)')
                                                    ->default(1.000000)
                                                    ->required()
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->minValue(0.000001),
                                            ]),
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                TextInput::make('actual_amount')
                                                    ->label('Actual Amount')
                                                    ->placeholder('Enter amount in original currency')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0),
                                                TextInput::make('notes')
                                                    ->label('Notes')
                                                    ->placeholder('Optional notes about this investment'),
                                            ]),
                                        Placeholder::make('calculated_amount')
                                            ->label('Calculated Amount (QAR)')
                                            ->content(function ($get) {
                                                $actualAmount = $get('actual_amount');
                                                $exchangeRate = $get('exchange_rate');
                                                
                                                if ($actualAmount && $exchangeRate && $exchangeRate > 0) {
                                                    $calculated = $actualAmount / $exchangeRate;
                                                    return number_format($calculated, 2);
                                                }
                                                
                                                return '0.00';
                                            })
                                            ->live()
                                            ->extraAttributes(['class' => 'text-sm font-medium text-gray-700 bg-gray-50 p-2 rounded']),
                                    ])
                                    ->addActionLabel('Add Investment')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => isset($state['investment_type_id']) ? InvestmentType::find($state['investment_type_id'])?->name ?? 'New Investment' : 'New Investment')
                                    ->defaultItems(0),
                            ]),

                        Tabs\Tab::make('Unusable Deposits')
                            ->schema([
                                Select::make('new_deposit_type')
                                    ->label('Create New Deposit Type')
                                    ->placeholder('Select to create a new deposit type')
                                    ->options([])
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('description')
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data): string {
                                        return DepositType::create([
                                            'user_id' => Auth::id(),
                                            'name' => $data['name'],
                                            'description' => $data['description'] ?? null,
                                        ])->name;
                                    })
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Create Deposit Type')
                                            ->modalSubmitActionLabel('Create Deposit Type')
                                            ->modalWidth('lg');
                                    }),
                                Repeater::make('deposits')
                                    ->label('Deposits')
                                    ->schema([
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                Select::make('deposit_type_id')
                                                    ->label('Deposit Type')
                                                    ->relationship(
                                                        name: 'depositType',
                                                        titleAttribute: 'name',
                                                        modifyQueryUsing: fn ($query) => $query->whereUserId(Auth::id())
                                                    )
                                                    ->searchable()
                                                    ->required()
                                                    ->live()
                                                    ->preload(),
                                                TextInput::make('exchange_rate')
                                                    ->label('Exchange Rate')
                                                    ->placeholder('Enter exchange rate (e.g., 24.20 for QAR to INR)')
                                                    ->default(1.000000)
                                                    ->required()
                                                    ->numeric()
                                                    ->step(0.000001)
                                                    ->minValue(0.000001),
                                            ]),
                                        Grid::make(2)
                                            ->columns(['default' => 2])
                                            ->schema([
                                                TextInput::make('actual_amount')
                                                    ->label('Actual Amount')
                                                    ->placeholder('Enter amount in original currency')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0),
                                                TextInput::make('notes')
                                                    ->label('Notes')
                                                    ->placeholder('Optional notes about this deposit'),
                                            ]),
                                        Placeholder::make('calculated_amount')
                                            ->label('Calculated Amount (QAR)')
                                            ->content(function ($get) {
                                                $actualAmount = $get('actual_amount');
                                                $exchangeRate = $get('exchange_rate');
                                                
                                                if ($actualAmount && $exchangeRate && $exchangeRate > 0) {
                                                    $calculated = $actualAmount / $exchangeRate;
                                                    return number_format($calculated, 2);
                                                }
                                                
                                                return '0.00';
                                            })
                                            ->live()
                                            ->extraAttributes(['class' => 'text-sm font-medium text-gray-700 bg-gray-50 p-2 rounded']),
                                    ])
                                    ->addActionLabel('Add Deposit')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => isset($state['deposit_type_id']) ? DepositType::find($state['deposit_type_id'])?->name ?? 'New Deposit' : 'New Deposit')
                                    ->defaultItems(0),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Summary')
                    ->schema([
                        Grid::make()
                            ->columns(['default' => 2, 'md' => 3, 'lg' => 5])
                            ->schema([
                                Placeholder::make('total_current_accounts')
                                    ->prefix('QAR ')
                                    ->label('Total Current Amount')
                                    ->content(function ($get) {
                                        $total = 0;
                                        $accounts = $get('accounts') ?? [];
                                        foreach ($accounts as $account) {
                                            if (isset($account['actual_amount']) && is_numeric($account['actual_amount']) && 
                                                isset($account['exchange_rate']) && is_numeric($account['exchange_rate']) && 
                                                $account['exchange_rate'] > 0) {
                                                $total += (float) $account['actual_amount'] / (float) $account['exchange_rate'];
                                            }
                                        }
                                        return number_format($total, 0);
                                    })
                                    ->live()
                                    ->liveOn(['accounts'])
                                    ->extraAttributes(['class' => 'text-lg font-semibold text-green-600']),

                                Placeholder::make('total_lent_money')
                                    ->prefix('QAR ')
                                    ->label('Total Lent Money')
                                    ->content(function ($get) {
                                        $total = 0;
                                        $lentMoney = $get('lent_money') ?? [];
                                        foreach ($lentMoney as $loan) {
                                            if (isset($loan['actual_amount']) && is_numeric($loan['actual_amount']) && 
                                                isset($loan['exchange_rate']) && is_numeric($loan['exchange_rate']) && 
                                                $loan['exchange_rate'] > 0) {
                                                $total += (float) $loan['actual_amount'] / (float) $loan['exchange_rate'];
                                            }
                                        }
                                        return number_format($total, 0);
                                    })
                                    ->live()
                                    ->liveOn(['lent_money'])
                                    ->extraAttributes(['class' => 'text-lg font-semibold text-blue-600']),

                                Placeholder::make('total_borrowed_money')
                                    ->prefix('QAR ')
                                    ->label('Total Borrowed Money')
                                    ->content(function ($get) {
                                        $total = 0;
                                        $borrowedMoney = $get('borrowed_money') ?? [];
                                        foreach ($borrowedMoney as $loan) {
                                            if (isset($loan['actual_amount']) && is_numeric($loan['actual_amount']) && 
                                                isset($loan['exchange_rate']) && is_numeric($loan['exchange_rate']) && 
                                                $loan['exchange_rate'] > 0) {
                                                $total += (float) $loan['actual_amount'] / (float) $loan['exchange_rate'];
                                            }
                                        }
                                        return number_format($total, 0);
                                    })
                                    ->live()
                                    ->liveOn(['borrowed_money'])
                                    ->extraAttributes(['class' => 'text-lg font-semibold text-orange-600']),

                                Placeholder::make('total_investments')
                                    ->prefix('QAR ')
                                    ->label('Total Investments')
                                    ->content(function ($get) {
                                        $total = 0;
                                        $investments = $get('investments') ?? [];
                                        foreach ($investments as $investment) {
                                            if (isset($investment['actual_amount']) && is_numeric($investment['actual_amount']) && 
                                                isset($investment['exchange_rate']) && is_numeric($investment['exchange_rate']) && 
                                                $investment['exchange_rate'] > 0) {
                                                $total += (float) $investment['actual_amount'] / (float) $investment['exchange_rate'];
                                            }
                                        }
                                        return number_format($total, 0);
                                    })
                                    ->live()
                                    ->liveOn(['investments'])
                                    ->extraAttributes(['class' => 'text-lg font-semibold text-purple-600']),

                                Placeholder::make('total_deposits')
                                    ->prefix('QAR ')
                                    ->label('Total Unusable Deposits')
                                    ->content(function ($get) {
                                        $total = 0;
                                        $deposits = $get('deposits') ?? [];
                                        foreach ($deposits as $deposit) {
                                            if (isset($deposit['actual_amount']) && is_numeric($deposit['actual_amount']) && 
                                                isset($deposit['exchange_rate']) && is_numeric($deposit['exchange_rate']) && 
                                                $deposit['exchange_rate'] > 0) {
                                                $total += (float) $deposit['actual_amount'] / (float) $deposit['exchange_rate'];
                                            }
                                        }
                                        return number_format($total, 0);
                                    })
                                    ->live()
                                    ->liveOn(['deposits'])
                                    ->extraAttributes(['class' => 'text-lg font-semibold text-gray-600']),

                                Placeholder::make('grand_total')
                                    ->prefix('QAR ')
                                    ->label('Total Asset Amount')
                                    ->content(function ($get) {
                                        $total = 0;

                                        // Current Accounts
                                        $accounts = $get('accounts') ?? [];
                                        foreach ($accounts as $account) {
                                            if (isset($account['actual_amount']) && is_numeric($account['actual_amount']) && 
                                                isset($account['exchange_rate']) && is_numeric($account['exchange_rate']) && 
                                                $account['exchange_rate'] > 0) {
                                                $total += (float) $account['actual_amount'] / (float) $account['exchange_rate'];
                                            }
                                        }

                                        // Lent Money
                                        $lentMoney = $get('lent_money') ?? [];
                                        foreach ($lentMoney as $loan) {
                                            if (isset($loan['actual_amount']) && is_numeric($loan['actual_amount']) && 
                                                isset($loan['exchange_rate']) && is_numeric($loan['exchange_rate']) && 
                                                $loan['exchange_rate'] > 0) {
                                                $total += (float) $loan['actual_amount'] / (float) $loan['exchange_rate'];
                                            }
                                        }

                                        // Borrowed Money
                                        $borrowedMoney = $get('borrowed_money') ?? [];
                                        foreach ($borrowedMoney as $loan) {
                                            if (isset($loan['actual_amount']) && is_numeric($loan['actual_amount']) && 
                                                isset($loan['exchange_rate']) && is_numeric($loan['exchange_rate']) && 
                                                $loan['exchange_rate'] > 0) {
                                                $total -= (float) $loan['actual_amount'] / (float) $loan['exchange_rate'];
                                            }
                                        }

                                        // Investments
                                        $investments = $get('investments') ?? [];
                                        foreach ($investments as $investment) {
                                            if (isset($investment['actual_amount']) && is_numeric($investment['actual_amount']) && 
                                                isset($investment['exchange_rate']) && is_numeric($investment['exchange_rate']) && 
                                                $investment['exchange_rate'] > 0) {
                                                $total += (float) $investment['actual_amount'] / (float) $investment['exchange_rate'];
                                            }
                                        }

                                        // Deposits
                                        $deposits = $get('deposits') ?? [];
                                        foreach ($deposits as $deposit) {
                                            if (isset($deposit['actual_amount']) && is_numeric($deposit['actual_amount']) && 
                                                isset($deposit['exchange_rate']) && is_numeric($deposit['exchange_rate']) && 
                                                $deposit['exchange_rate'] > 0) {
                                                $total += (float) $deposit['actual_amount'] / (float) $deposit['exchange_rate'];
                                            }
                                        }

                                        return number_format($total, 0);
                                    })
                                    ->live()
                                    ->liveOn(['accounts', 'lent_money', 'borrowed_money', 'investments', 'deposits'])
                                    ->extraAttributes(['class' => 'text-2xl font-bold text-indigo-600 text-center']),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }
}
