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

    <div class="flex items-center justify-between mb-2">

        <div>
            <flux:text class="font-bold text-2xl" color="sky">{{ $ledger->name }}</flux:text>
            <flux:text>{{ $ledger->title }}</flux:text>

            <div class="flex mb-2">
                <flux:badge size="sm" class="m-1 ms-0" color="{{ $ledger->is_star ? 'yellow' : 'white' }}" >{{ $ledger->is_star ? 'Favourite' : 'Not Favourite' }}</flux:badge>
                <flux:badge size="sm" class="m-1" color="{{ $ledger->is_active ? 'green' : 'white' }}" >{{ $ledger->is_star ? 'Active' : 'Inactive' }}</flux:badge>
                <flux:badge size="sm" class="m-1" color="zinc" >{{ $ledger->ledgerType->name }}</flux:badge>
            </div>
 
            <div class="grid grid-cols-1 lg:grid-cols-4 mb-2 mb-2">
                <flux:badge size="sm" class="m-1 ms-0" color="zinc" ><span class="font-bold">Last Opened:</span>  {{ $ledger->opened_at ? $ledger->opened_at->format('d M Y h:m:a') : 'N/A' }}</flux:badge>
                <flux:badge size="sm" class="m-1 ms-0" color="zinc" ><span class="font-bold">Created at:</span>  {{ $ledger->created_at ? $ledger->created_at->format('d M Y h:m:a') : 'N/A' }}</flux:badge>
                <flux:badge size="sm" class="m-1 ms-0" color="zinc" ><span class="font-bold">Updated at:</span>  {{ $ledger->updated_at ? $ledger->updated_at->format('d M Y h:m:a') : 'N/A' }}</flux:badge>
            </div>

            {{-- <div class="grid grid-cols-1 lg:grid-cols-4 mb-2">
                <flux:text> <span class="font-bold">Last Opened:</span>  {{ $ledger->opened_at ? $ledger->opened_at->format('d M Y h:m:a') : 'N/A' }}</flux:text>
                <flux:text> <span class="font-bold">Created at:</span>  {{ $ledger->created_at ? $ledger->created_at->format('d M Y h:m:a') : 'N/A' }}</flux:text>
                <flux:text> <span class="font-bold">Updated at:</span>  {{ $ledger->updated_at ? $ledger->updated_at->format('d M Y h:m:a') : 'N/A' }}</flux:text>
            </div> --}}

            <flux:text size="xs" class="text-justify mt-1">
            {{ $ledger->notes }}
        </flux:text>

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
            <flux:text color="blue">Amount</flux:text>
            <flux:text color="blue" class="font-bold text-2xl">{{ number_format($ledger->balance(), 2) }}/-</flux:text>
        </flux:card>

        <flux:card>
            <flux:text color="cyan">Transactions</flux:text>
            <flux:text color="cyan" class="font-bold text-2xl"> {{ $ledger->transactions->count() }}</flux:text>
        </flux:card>

    </div>

    <flux:card>

        <flux:heading size="lg">
            Transactions
        </flux:heading>

        <div class="overflow-x-auto mt-4">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Ref Num</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Items</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Action</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                   @foreach($ledger->transactions as $trx)
                    <flux:table.row>
                        <flux:table.cell>
                            <flux:text class="font-bold">{{$trx->ref_no}}</flux:text>
                            <flux:badge color="green" size="sm">{{$trx->paymentMethod->name ?? ''}}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>{{ $trx->created_at ? $trx->created_at->format('M j, Y') : 'N/A' }}</flux:table.cell>
                        <flux:table.cell class="py-0">{{$trx->items->count()}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{$trx->items->sum('amount')}} /-</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div>
                                <flux:button size="xs" icon="eye" wire:click="ShowItemsTransactionModal({{ $trx->id }})"/>
                                <flux:button size="xs" icon="trash" wire:click="confirmDeleteTransaction({{ $trx->id }})"/>
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
        <div class="space-y-6">
            <flux:heading size="lg">Update profile</flux:heading>

            <flux:subheading>Make changes to your personal details.</flux:subheading>

            <flux:input label="Name" placeholder="Your name" />

            <flux:input label="Date of birth" type="date" />
        </div>

        <x-slot name="footer" class="flex items-center justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="filled">Cancel</flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </x-slot>
    </flux:modal>


</section>