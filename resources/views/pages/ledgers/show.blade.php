<?php

use App\Models\Ledger;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\TransactionItem;

new #[Title('Ledger Details')] class extends Component
{
    public Ledger $ledger;
    public ?int $deleteTransactionId = null;
    public ?int $showTransactionId = null;

    public bool $showDeleteTransactionModal = false;
    public bool $showItemsTransactionModal = false;

   public function mount(Ledger $ledger): void
    {
        $this->ledger = $ledger->load([
            'ledgerType',
            'transactions.items.category',
            'transactions.paymentMethod',
        ]);
    }

    public function ShowItemsTransactionModal(int $id): void
    {
        $this->showTransactionId = $id;
        $this->showItemsTransactionModal = true;   
    }

    public function confirmDeleteTransaction(int $id): void
    {
        $this->deleteTransactionId = $id;

        $this->showDeleteTransactionModal = true;
    }

    public function deleteTransaction(): void
    {
        $transaction = $this->ledger
            ->transactions()
            ->findOrFail($this->deleteTransactionId);

        $transaction->delete();

        $this->showDeleteTransactionModal = false;

        Flux::toast(
            variant: 'success',
            text: __('Transaction deleted successfully.')
        );

        $this->ledger->refresh();
    }


};

?>

<section class="w-full">
    <flux:button.group>
        <flux:button variant="filled"  icon="arrow-left" size="sm"  :href="route('ledgers.index')" wire:navigate>Back</flux:button>
        <flux:button variant="filled"  icon="pencil-square" size="sm" :href="route('ledgers.edit', $ledger->id)" wire:navigate>Edit</flux:button>
        <flux:button variant="filled"  icon="plus" size="sm" :href="route('transactions.create', ['ledger_id' => $ledger->id])" wire:navigate>Transaction</flux:button>
    </flux:button.group>

    <div class="mt-4 flex items-center justify-between mb-2">
        <div>
           
            <flux:text class="font-bold text-2xl text-white" color="sky">{{ $ledger->name }}</flux:text>
            <flux:text class="text-white">{{ $ledger->title }}</flux:text>

            <div class="flex mb-2">
                <flux:badge size="sm" class="m-1 text-white ms-0" color="{{ $ledger->is_star ? 'yellow' : 'white' }}" >{{ $ledger->is_star ? 'Favourite' : 'Not Favourite' }}</flux:badge>
                <flux:badge size="sm" class="m-1 text-white" color="{{ $ledger->is_active ? 'green' : 'white' }}" >{{ $ledger->is_star ? 'Active' : 'Inactive' }}</flux:badge>
                <flux:badge size="sm" class="m-1 text-white" color="zinc" >{{ $ledger->ledgerType->name }}</flux:badge>
            </div>
 
            <div class="grid grid-cols-1 lg:grid-cols-4 my-2">
                <flux:text size="sm" class="me-2 dark:text-blue-100"><span class="font-bold dark:text-blue-80 me-1">Last Opened: </span> {{ $ledger->opened_at ? $ledger->opened_at->format('d M Y h:m:a') : 'N/A' }}</flux:text>
                <flux:text size="sm" class="me-2 dark:text-blue-100"><span class="font-bold dark:text-blue-80 me-1">Created at: </span> {{ $ledger->created_at ? $ledger->created_at->format('d M Y h:m:a') : 'N/A' }}</flux:text>
                <flux:text size="sm" class="me-2 dark:text-blue-100"><span class="font-bold dark:text-blue-80 me-1">Updated at: </span> {{ $ledger->updated_at ? $ledger->updated_at->format('d M Y h:m:a') : 'N/A' }}</flux:text>
            </div>

            <flux:text size="xs" class="text-justify dark:text-blue-100 mt-1">{{ $ledger->notes }}</flux:text>

        </div>

    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <flux:card>
            <flux:text color="green">Total Credit</flux:text>
            <flux:text color="green" class="font-bold text-2xl">{{ number_format($ledger->totalCredit(), 2) }}/-</flux:text>
        </flux:card>

        <flux:card>
            <flux:text color="red">Total Debit</flux:text>
            <flux:text color="red" class="font-bold text-2xl">{{ number_format($ledger->totalDebit(), 2) }}/-</flux:text>
        </flux:card>

        <flux:card>
            <flux:text>Amount</flux:text>
            <flux:text class="font-bold text-2xl">{{ number_format($ledger->balance(), 2) }}/-</flux:text>
        </flux:card>

        <flux:card>
            <flux:text color="cyan">Transactions</flux:text>
            <flux:text color="cyan" class="font-bold text-2xl"> {{ $ledger->transactions->count() }}</flux:text>
        </flux:card>

    </div>

    @if($ledger->transactions->count() > 0)
    <flux:card>

        <flux:heading size="lg">
            Transactions
        </flux:heading>

        <div class="overflow-x-auto mt-4">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Transaction</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Action</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                   @foreach($ledger->transactions as $trx)
                    <flux:table.row>
                        <flux:table.cell>
                            <flux:badge size="sm" class="font-bold">{{$trx->trx_no}}</flux:badge>
                            <flux:text size="sm">{{ $trx->created_at ? $trx->created_at->format('M j, Y') : 'N/A' }}</flux:text>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">
                            <flux:badge>{{$trx->items->sum('amount')}} /-</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div>
                                <flux:button size="sm" icon="eye" variant="primary" color="green" wire:click="ShowItemsTransactionModal({{ $trx->id }})"/>
                                <flux:button size="sm" icon="trash" color="red" variant="primary" wire:click="confirmDeleteTransaction({{ $trx->id }})"/>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforeach

                </flux:table.rows>
            </flux:table>
        </div>

    </flux:card>

    <flux:modal wire:model="showDeleteTransactionModal">
        <flux:heading> Delete Transaction</flux:heading>
        <flux:text> Are you sure?</flux:text>

        <div class="flex justify-end gap-2 mt-4">
            <flux:button wire:click="$set('showDeleteTransactionModal', false)"> Cancel</flux:button>
            <flux:button variant="danger" wire:click="deleteTransaction">Delete</flux:button>
        </div>
    </flux:modal>


    <flux:modal name="edit-profile" flyout variant="floating" class="md:w-lg" wire:model="showItemsTransactionModal">

        @php
            $selectedTransaction = $ledger->transactions->firstWhere('id', $showTransactionId);
        @endphp

        <div class="">
            <flux:heading size="lg" class="mb-1 font-bold">{{$selectedTransaction->trx_no ?? ''}}</flux:heading>
            <flux:text size="sm" class="font-bold"><span class="font-bold">Date: </span> {{ $selectedTransaction?->date ? $selectedTransaction->date->format('M j, Y') : 'N/A' }}</flux:text>
            <div class="flex">
                <flux:text size="xs" class="font-bold me-2">{{ $selectedTransaction->trx_type ?? '' }}</flux:text>
                <flux:text size="xs" color="green" class="font-bold">{{ $selectedTransaction->paymentMethod->name ?? '' }}</flux:text>
            </div>
            <flux:text size="xs" class="">{{ $selectedTransaction->notes ?? '' }}</flux:text>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-2 gap-2 my-4">
            <flux:card>
                <flux:text color="">Total Amount</flux:text>
                <flux:text color="" class="font-bold text-lg">{{ number_format($selectedTransaction?->items->sum('amount') ?? 0, 2) }}/-</flux:text>
            </flux:card>

            <flux:card>
                <flux:text color="">Total Items</flux:text>
                <flux:text color="" class="font-bold text-lg">{{ $selectedTransaction?->items->count() ?? 0 }}</flux:text>
            </flux:card>

        </div>

        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Category</flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @if($selectedTransaction && $selectedTransaction->items->isNotEmpty())
                        @foreach($selectedTransaction->items as $item)
                            <flux:table.row>
                                <flux:table.cell>{{ $item->category?->name ?? 'N/A' }}</flux:table.cell>
                                <flux:table.cell>{{ $item->name ?? $item->description ?? 'Item' }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($item->amount, 2) }}/-</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    @else
                        <flux:table.row>
                            <flux:table.cell colspan="3">No transaction items found.</flux:table.cell>
                        </flux:table.row>
                    @endif
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="flex mt-4">
            <flux:button size="sm" icon="arrow-left" class="me-2" wire:click="$set('showItemsTransactionModal', false)" variant="primary">Back</flux:button>
            @if($selectedTransaction)
                <flux:button size="sm" icon="pencil-square" type="submit" variant="primary" color="yellow" :href="route('transactions.edit', $selectedTransaction->id)" wire:navigate>Edit</flux:button>
            @endif
        </div>

    </flux:modal>

    
    @endif

    


</section>