<?php

use Flux\Flux;
use App\Models\Ledger;
use App\Models\Transaction;
use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\PaymentMethod;
use Livewire\Attributes\Title;

new #[Title('Create Transaction')] class extends Component
{
    public Ledger $ledger;

    public string $trx_type = '';
    public string $date = '';

    public ?int $payment_method_id = null;
    public ?string $trx_no = null;
    public ?string $notes = null;

    public array $items = [];

    public function mount(Ledger $ledger): void
    {
        // abort_if(
        //     $ledger->user_id !== auth()->id(),
        //     403
        // );

        $this->ledger = $ledger;

        $this->date = now()->format('Y-m-d');

        $this->items = [
            [
                'category_id' => '',
                'name' => '',
                'notes' => '',
                'amount' => '',
            ],
            [
                'category_id' => '',
                'name' => '',
                'notes' => '',
                'amount' => '',
            ]
        ];
    }

    public function addItem(): void
    {
        $this->items[] = [
            'category_id' => '',
            'name' => '',
            'notes' => '',
            'amount' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);

        $this->items = array_values($this->items);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'trx_type' => ['required'],
            'date' => ['required', 'date'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'trx_no' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.category_id' => ['nullable', 'exists:item_categories,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.notes' => ['nullable', 'string'],
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $amount = collect($this->items)->sum(fn ($item) => (float) $item['amount']);

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'ledger_id' => $this->ledger->id,
            'trx_type' => $validated['trx_type'],
            'payment_method_id' => $validated['payment_method_id'],
            'date' => $validated['date'],
            'trx_no' => $validated['trx_no'],
            'notes' => $validated['notes'],
            'amount' => $amount,
        ]);

        foreach ($this->items as $item) {

            $transaction->items()->create([
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'notes' => $item['notes'],
                'amount' => $item['amount'],
            ]);
        }

        $transaction->update([
            'trx_no' => $this->generateTransactionNumber($transaction),
        ]);

        Flux::toast(
            variant: 'success',
            text: __('Transaction created successfully.')
        );

        $this->redirect(
            route('ledgers.show', $this->ledger),
            navigate: true
        );
    }

    public function getCategoriesProperty()
    {
        return ItemCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getTotalProperty(): float
    {
        return collect($this->items)
            ->sum(fn ($item) => (float) ($item['amount'] ?? 0));
    }

    public function generateTransactionNumber(Transaction $transaction): string
    {
        $ledgerPart = str_pad(
            (string) $transaction->ledger_id,
            3,
            '0',
            STR_PAD_LEFT
        );

        $transactionPart = str_pad(
            (string) $transaction->id,
            4,
            '0',
            STR_PAD_LEFT
        );

        $serialPart = str_pad(
            (string) $transaction->ledger->transactions()->count(),
            2,
            '0',
            STR_PAD_LEFT
        );

        return "{$ledgerPart}{$transactionPart}{$serialPart}";
    }
};

?>

<section class="w-full">

    <div class="flex">
        <flux:button class="me-2" icon="arrow-left" size="sm" variant="primary" :href="route('ledgers.show', $ledger)" wire:navigate>Back</flux:button>        {{-- <flux:button class="me-2" icon="plus" size="sm" color="green" variant="primary" :href="route('transactions.create', $ledger->id)" wire:navigate>Transaction</flux:button> --}}
    </div>

    <flux:text class="font-bold text-2xl mt-2" color="sky">Create Transaction</flux:text>
    <flux:text class="">Ledger: {{ $ledger->name }}</flux:text>

    <form wire:submit="save" class="my-6 w-full space-y-6">

        <flux:card>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-2">

                <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <flux:select size="sm" wire:model="trx_type" label="Transaction Type">
                        <option value="">Select Type</option>
                        <option value="credit">Credit</option>
                        <option value="debit">Debit</option>
                    </flux:select>
                </div>

                <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <flux:input size="sm" wire:model="date" label="Date" type="date"/>
                </div>

                <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <flux:select size="sm" wire:model="payment_method_id" label="Payment Method">
                        <option value="">Payment Method </option>
                        @foreach($this->paymentMethods as $method)
                            <option value="{{ $method->id }}"> {{ $method->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="col-span-6 md:col-span-6 lg:col-span-6">
                    <flux:input size="sm" wire:model="notes" label="Notes" type="text"/>
                </div>

            </div>

            <div class="space-y-4 mt-3">

                @foreach($items as $index => $item)

                    <div class="border mb-2 py-3">

                        <div class="grid grid-cols-1 md:grid-cols-10 gap-2">

                            <div class="col-span-1 md:col-span-3 lg:col-span-3">
                                <flux:select size="sm" wire:model="items.{{ $index }}.category_id" placeholder="Category">
                                    <option value="">Select Category</option>

                                    @foreach($this->categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach

                                </flux:select>
                            </div>

                            <div class="col-span-1 md:col-span-4 lg:col-span-4">
                                <flux:input size="sm" wire:model="items.{{ $index }}.name" placeholder="Name"/>
                            </div>

                            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                                <flux:input size="sm" wire:model.live="items.{{ $index }}.amount" placeholder="Amount" type="number" step="0.01"/>
                            </div>

                            @if(count($items) > 1)
                            <div class="col-span-1 flex justify-center">
                                <flux:button size="sm" type="button" variant="primary" color="red" icon="x-mark" wire:click="removeItem({{ $index }})"></flux:button>
                            </div>
                            @endif

                        </div>

                    </div>
                @endforeach

                <div class="grid grid-cols-10 md:grid-cols-10 lg:grid-cols-10 gap-4 py-3">
                    <div class="col-span-6 md:col-span-7 lg:col-span-7 flex justify-center">
                        <flux:text size="lg" class="font-bold" color="green">Total Amount</flux:text>
                    </div>
                    <div class="col-span-4 md:col-span-2 lg:col-span-2 flex justify-center">
                        <flux:text size="lg" class="font-bold" color="green">{{ number_format($this->total, 2) }} /-</flux:text>
                    </div>
                    <div class="col-span-1 flex justify-center">
                        <flux:button type="button" icon="plus" size="sm" variant="primary" color="green" wire:click="addItem"></flux:button>
                    </div>
                    
                    
                </div>
            </div>

             <div class="flex justify-center mt-5">
                <flux:button variant="primary" color="green" class="" type="submit">Save</flux:button>
            </div>

        </flux:card>

    </form>

</section>
