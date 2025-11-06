@extends('layouts.dashboard')

@section('title', 'My Wallet')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Wallet Balance Card -->
    <div class="lg:col-span-1">
        <div class="overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-white bg-opacity-25 rounded-full">
                        <i class="text-white fas fa-wallet text-2xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-blue-200 truncate">Available Balance</p>
                        <p class="text-3xl font-semibold text-white">₹{{ number_format($walletBalance, 2) }}</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="button" @click="showAddFundsModal = true" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-transparent rounded-md shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="mr-2 fas fa-plus-circle"></i>
                        Add Money
                    </button>
                    
                    <button type="button" @click="showWithdrawModal = true" class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-transparent border border-white border-opacity-50 rounded-md hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400">
                        <i class="mr-2 fas fa-arrow-up"></i>
                        Withdraw
                    </button>
                </div>
            </div>
            
            <div class="px-4 py-4 bg-blue-600 bg-opacity-25 border-t border-blue-400 border-opacity-25">
                <div class="text-sm">
                    <a href="#" class="inline-flex items-center font-medium text-blue-100 hover:text-white">
                        View all transactions
                        <i class="ml-1 text-xs fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-6 overflow-hidden bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <button type="button" @click="showAddFundsModal = true" class="flex flex-col items-center justify-center p-4 text-center transition duration-150 ease-in-out bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <div class="flex items-center justify-center w-12 h-12 text-white bg-blue-500 rounded-full">
                            <i class="text-xl fas fa-plus"></i>
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-700">Add Money</span>
                    </button>
                    
                    <button type="button" @click="showWithdrawModal = true" class="flex flex-col items-center justify-center p-4 text-center transition duration-150 ease-in-out bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <div class="flex items-center justify-center w-12 h-12 text-white bg-green-500 rounded-full">
                            <i class="text-xl fas fa-arrow-up"></i>
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-700">Withdraw</span>
                    </button>
                    
                    <button type="button" class="flex flex-col items-center justify-center p-4 text-center transition duration-150 ease-in-out bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <div class="flex items-center justify-center w-12 h-12 text-white bg-purple-500 rounded-full">
                            <i class="text-xl fas fa-exchange-alt"></i>
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-700">Transfer</span>
                    </button>
                    
                    <button type="button" class="flex flex-col items-center justify-center p-4 text-center transition duration-150 ease-in-out bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <div class="flex items-center justify-center w-12 h-12 text-white bg-yellow-500 rounded-full">
                            <i class="text-xl fas fa-history"></i>
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-700">History</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Payment Methods -->
        <div class="mt-6 overflow-hidden bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Payment Methods</h3>
                    <button type="button" @click="showAddCardModal = true" class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-white border border-transparent rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="mr-1 fas fa-plus"></i> Add
                    </button>
                </div>
                
                <div class="mt-4 space-y-3">
                    @foreach($paymentMethods as $method)
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                @if($method->card_type === 'visa')
                                    <i class="text-3xl fab fa-cc-visa text-blue-600"></i>
                                @elseif($method->card_type === 'mastercard')
                                    <i class="text-3xl fab fa-cc-mastercard text-red-600"></i>
                                @elseif($method->card_type === 'amex')
                                    <i class="text-3xl fab fa-cc-amex text-blue-400"></i>
                                @elseif($method->card_type === 'paytm')
                                    <i class="text-3xl fas fa-wallet text-blue-500"></i>
                                @elseif($method->card_type === 'upi')
                                    <i class="text-3xl fas fa-mobile-alt text-purple-600"></i>
                                @else
                                    <i class="text-3xl far fa-credit-card text-gray-500"></i>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    @if($method->card_type === 'paytm')
                                        Paytm Wallet
                                    @elseif($method->card_type === 'upi')
                                        UPI - {{ $method->last_four }}
                                    @else
                                        **** **** **** {{ $method->last_four }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if($method->card_type !== 'paytm' && $method->card_type !== 'upi')
                                        Expires {{ $method->exp_month }}/{{ $method->exp_year }}
                                    @else
                                        {{ ucfirst($method->card_type) }}
                                    @endif
                                </p>
                            </div>
                            <div class="ml-auto">
                                <button type="button" class="p-1 text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($paymentMethods->isEmpty())
                        <div class="text-center py-6">
                            <i class="mx-auto text-4xl text-gray-300 fas fa-credit-card"></i>
                            <p class="mt-2 text-sm text-gray-500">No payment methods added</p>
                            <button type="button" @click="showAddCardModal = true" class="inline-flex items-center px-3 py-1.5 mt-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-1 text-xs fas fa-plus"></i> Add Payment Method
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="lg:col-span-2">
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex flex-col justify-between sm:flex-row sm:items-center">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Transactions</h3>
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">Your last 20 transactions</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="relative">
                            <select class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option>All Transactions</option>
                                <option>Deposits</option>
                                <option>Withdrawals</option>
                                <option>Winnings</option>
                                <option>Game Fees</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="flex-shrink-0 p-2 rounded-full bg-{{ $transaction->type === 'credit' ? 'green' : 'red' }}-100">
                                    @if($transaction->type === 'credit')
                                        <i class="text-{{ $transaction->type === 'credit' ? 'green' : 'red' }}-600 fas fa-arrow-down"></i>
                                    @else
                                        <i class="text-{{ $transaction->type === 'credit' ? 'green' : 'red' }}-600 fas fa-arrow-up"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $transaction->description }}
                                    </p>
                                    <p class="flex items-center mt-1 text-sm text-gray-500">
                                        <span>{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                                        @if($transaction->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 ml-2 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Pending
                                            </span>
                                        @elseif($transaction->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 ml-2 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                Completed
                                            </span>
                                        @elseif($transaction->status === 'failed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 ml-2 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                Failed
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-{{ $transaction->type === 'credit' ? 'green' : 'red' }}-600">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                </p>
                                @if($transaction->fee > 0)
                                    <p class="mt-1 text-xs text-right text-gray-500">
                                        Fee: ₹{{ number_format($transaction->fee, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center">
                        <i class="mx-auto text-4xl text-gray-300 fas fa-exchange-alt"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding money to your wallet.</p>
                        <div class="mt-6">
                            <button type="button" @click="showAddFundsModal = true" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-2 -ml-1 fas fa-plus"></i>
                                Add Money
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if($transactions->hasPages())
                <div class="px-4 py-3 bg-gray-50 sm:px-6">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Funds Modal -->
<div x-show="showAddFundsModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showAddFundsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showAddFundsModal = false" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showAddFundsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Add Money to Wallet</h3>
                    <button type="button" @click="showAddFundsModal = false" class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mt-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₹)</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" name="amount" id="amount" min="100" step="100" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm" id="amount-currency">INR</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Minimum amount: ₹100.00</p>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700">Payment Method</h4>
                        <fieldset class="mt-2">
                            <legend class="sr-only">Payment method</legend>
                            <div class="space-y-4">
                                @foreach($paymentMethods as $method)
                                    <div class="flex items-center">
                                        <input id="method-{{ $method->id }}" name="payment-method" type="radio" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ $loop->first ? 'checked' : '' }}>
                                        <label for="method-{{ $method->id }}" class="flex items-center ml-3">
                                            @if($method->card_type === 'visa')
                                                <i class="text-2xl fab fa-cc-visa text-blue-600"></i>
                                            @elseif($method->card_type === 'mastercard')
                                                <i class="text-2xl fab fa-cc-mastercard text-red-600"></i>
                                            @elseif($method->card_type === 'paytm')
                                                <i class="text-2xl fas fa-wallet text-blue-500"></i>
                                            @elseif($method->card_type === 'upi')
                                                <i class="text-2xl fas fa-mobile-alt text-purple-600"></i>
                                            @else
                                                <i class="text-2xl far fa-credit-card text-gray-500"></i>
                                            @endif
                                            <span class="block ml-3 text-sm font-medium text-gray-700">
                                                @if($method->card_type === 'paytm')
                                                    Paytm Wallet
                                                @elseif($method->card_type === 'upi')
                                                    UPI - {{ $method->last_four }}
                                                @else
                                                    **** **** **** {{ $method->last_four }}
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                                
                                <div class="flex items-center">
                                    <input id="new-method" name="payment-method" type="radio" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="new-method" class="block ml-3 text-sm font-medium text-gray-700">
                                        Add new payment method
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700">Or choose a quick amount</h4>
                        <div class="grid grid-cols-3 gap-3 mt-2">
                            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ₹100
                            </button>
                            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ₹500
                            </button>
                            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ₹1,000
                            </button>
                            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ₹2,000
                            </button>
                            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ₹5,000
                            </button>
                            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                ₹10,000
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                    Add Money
                </button>
                <button type="button" @click="showAddFundsModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div x-show="showWithdrawModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showWithdrawModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showWithdrawModal = false" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showWithdrawModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Withdraw Funds</h3>
                    <button type="button" @click="showWithdrawModal = false" class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mt-6">
                    <div>
                        <label for="withdraw-amount" class="block text-sm font-medium text-gray-700">Amount (₹)</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" name="withdraw-amount" id="withdraw-amount" min="100" step="100" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" class="text-sm font-medium text-blue-600 hover:text-blue-500 focus:outline-none">
                                    MAX
                                </button>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Available balance: ₹{{ number_format($walletBalance, 2) }}</p>
                        <p class="mt-1 text-xs text-gray-500">Minimum withdrawal: ₹100.00</p>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700">Withdraw To</h4>
                        <div class="mt-2">
                            <select id="withdraw-method" name="withdraw-method" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option>Select withdrawal method</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="upi">UPI Transfer</option>
                                <option value="paytm">Paytm Wallet</option>
                            </select>
                        </div>
                        
                        <div id="bank-details" class="p-4 mt-4 bg-gray-50 rounded-lg" style="display: none;">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="account-number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                    <input type="text" name="account-number" id="account-number" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="confirm-account" class="block text-sm font-medium text-gray-700">Confirm Account</label>
                                    <input type="text" name="confirm-account" id="confirm-account" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="ifsc" class="block text-sm font-medium text-gray-700">IFSC Code</label>
                                    <input type="text" name="ifsc" id="ifsc" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="account-holder" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                    <input type="text" name="account-holder" id="account-holder" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                        
                        <div id="upi-details" class="p-4 mt-4 bg-gray-50 rounded-lg" style="display: none;">
                            <div>
                                <label for="upi-id" class="block text-sm font-medium text-gray-700">UPI ID</label>
                                <div class="flex mt-1 rounded-md shadow-sm">
                                    <input type="text" name="upi-id" id="upi-id" class="flex-1 block w-full min-w-0 border-gray-300 rounded-none rounded-l-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="username@upi">
                                    <button type="button" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-700 bg-blue-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-blue-200 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        Verify
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Enter a valid UPI ID to receive funds instantly</p>
                            </div>
                        </div>
                        
                        <div id="paytm-details" class="p-4 mt-4 bg-gray-50 rounded-lg" style="display: none;">
                            <div>
                                <label for="paytm-number" class="block text-sm font-medium text-gray-700">Paytm Mobile Number</label>
                                <div class="flex mt-1">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">
                                        +91
                                    </span>
                                    <input type="tel" name="paytm-number" id="paytm-number" class="flex-1 block w-full min-w-0 border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="9876543210">
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Enter the mobile number linked to your Paytm account</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="p-4 bg-yellow-50 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="text-yellow-400 fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="pl-5 space-y-1 list-disc">
                                            <li>Minimum withdrawal amount is ₹100</li>
                                            <li>Withdrawals are processed within 24-48 hours</li>
                                            <li>A 2% processing fee will be applied (min. ₹5)</li>
                                            <li>For any issues, contact support@dahlapakad.com</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
                    Request Withdrawal
                </button>
                <button type="button" @click="showWithdrawModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('wallet', () => ({
            showAddFundsModal: false,
            showWithdrawModal: false,
            showAddCardModal: false,
            
            init() {
                // Handle withdrawal method change
                const withdrawMethod = document.getElementById('withdraw-method');
                if (withdrawMethod) {
                    withdrawMethod.addEventListener('change', (e) => {
                        document.querySelectorAll('[id$="-details"]').forEach(el => {
                            el.style.display = 'none';
                        });
                        
                        const selectedMethod = e.target.value;
                        if (selectedMethod === 'bank') {
                            document.getElementById('bank-details').style.display = 'block';
                        } else if (selectedMethod === 'upi') {
                            document.getElementById('upi-details').style.display = 'block';
                        } else if (selectedMethod === 'paytm') {
                            document.getElementById('paytm-details').style.display = 'block';
                        }
                    });
                }
                
                // Handle quick amount buttons
                document.querySelectorAll('[data-amount]').forEach(button => {
                    button.addEventListener('click', (e) => {
                        document.getElementById('amount').value = e.target.dataset.amount;
                    });
                });
                
                // Handle max button
                const maxButton = document.querySelector('[data-max-amount]');
                if (maxButton) {
                    maxButton.addEventListener('click', () => {
                        document.getElementById('withdraw-amount').value = maxButton.dataset.maxAmount;
                    });
                }
            }
        }));
    });
</script>
@endpush
@endsection
