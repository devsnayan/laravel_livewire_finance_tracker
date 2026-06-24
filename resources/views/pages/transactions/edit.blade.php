<?php

use Flux\Flux;
use App\Models\Ledger;
use App\Models\Transaction;
use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\PaymentMethod;
use Livewire\Attributes\Title;

new #[Title('Edit Transaction')] class extends Component
{
    public Transaction $transaction;
    public Ledger $ledger;

    public string $trx_type = '';
    public string $date = '';

    public ?int $payment_method_id = null;
    public ?string $trx_no = null;
    public ?string $notes = null;

    public array $items = [];

    public function mount(Transaction $transaction): void
    {
        $this->transaction = $transaction;
        $this->ledger = $transaction->ledger;

        $this->trx_type = $transaction->trx_type->value ?? $transaction->trx_type;
        $this->date = $transaction->date->format('Y-m-d');
        $this->payment_method_id = $transaction->payment_method_id;
        $this->trx_no = $transaction->trx_no;
        $this->notes = $transaction->notes;

        $this->items = $transaction->items
            ->map(fn ($item) => [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'name' => $item->name,
                'notes' => $item->notes,
                'amount' => $item->amount,
            ])
            ->toArray();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'id' => null,
            'category_id' => '',
            'name' => '',
            'notes' => '',
            'amount' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        if (!empty($this->items[$index]['id'])) {

            $this->transaction
                ->items()
                ->where('id', $this->items[$index]['id'])
                ->delete();
        }

        unset($this->items[$index]);

        $this->items = array_values($this->items);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'trx_type' => ['required'],
            'date' => ['required', 'date'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.category_id' => ['nullable', 'exists:item_categories,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.notes' => ['nullable', 'string'],
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $amount = collect($this->items)
            ->sum(fn ($item) => (float) $item['amount']);

        $this->transaction->update([
            'trx_type' => $validated['trx_type'],
            'payment_method_id' => $validated['payment_method_id'],
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            'amount' => $amount,
        ]);

        $savedIds = [];

        foreach ($this->items as $item) {

            if (!empty($item['id'])) {

                $trxItem = $this->transaction
                    ->items()
                    ->find($item['id']);

                if ($trxItem) {

                    $trxItem->update([
                        'category_id' => $item['category_id'],
                        'name' => $item['name'],
                        'notes' => $item['notes'],
                        'amount' => $item['amount'],
                    ]);

                    $savedIds[] = $trxItem->id;
                }

            } else {

                $newItem = $this->transaction
                    ->items()
                    ->create([
                        'category_id' => $item['category_id'],
                        'name' => $item['name'],
                        'notes' => $item['notes'],
                        'amount' => $item['amount'],
                    ]);

                $savedIds[] = $newItem->id;
            }
        }

        $this->transaction
            ->items()
            ->whereNotIn('id', $savedIds)
            ->delete();

        Flux::toast(
            variant: 'success',
            text: __('Transaction updated successfully.')
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
};

?>

<section class="w-full">

    <flux:button.group>
        <flux:button class="me-2" variant="filled" icon="arrow-left" size="sm" :href="route('ledgers.show', $ledger)" wire:navigate>Back</flux:button>        {{-- <flux:button class="me-2" icon="plus" size="sm" color="green" variant="primary" :href="route('transactions.create', $ledger->id)" wire:navigate>Transaction</flux:button> --}}
    </flux:button.group>

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

                    <div class="relative border rounded-sm mb-4 py-3">

                        <div class="grid grid-cols-1 md:grid-cols-10 gap-2 mt-4">

                            <div class="col-span-1 md:col-span-3 lg:col-span-3 px-2">
                                <flux:select size="sm" wire:model="items.{{ $index }}.category_id" label="Category">
                                    <option value="">Select Category</option>

                                    @foreach($this->categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach

                                </flux:select>
                            </div>

                            <div class="col-span-1 md:col-span-5 lg:col-span-5 px-2">
                                <flux:input size="sm" wire:model="items.{{ $index }}.name" label="Name"/>
                            </div>

                            <div class="col-span-1 md:col-span-2 lg:col-span-2 px-2">
                                <flux:input size="sm" wire:model.live="items.{{ $index }}.amount" label="Amount" type="number" step="0.01"/>
                            </div>

                            @if(count($items) > 1)
                            <div class="absolute top-0 right-0">
                                <flux:button size="sm" type="button" variant="ghost" icon="trash" wire:click="removeItem({{ $index }})"></flux:button>
                            </div>
                            @endif

                        </div>

                    </div>
                @endforeach

                <div class="grid grid-cols-10 md:grid-cols-10 lg:grid-cols-10 gap-4 py-3">
                    <div class="col-span-4 md:col-span-7 lg:col-span-7 flex justify-center">
                        <flux:text size="lg" class="font-bold">Total Amount</flux:text>
                    </div>
                    <div class="col-span-4 md:col-span-2 lg:col-span-2 flex justify-center">
                        <flux:text size="lg" class="font-bold">{{ number_format($this->total, 2) }} /-</flux:text>
                    </div>
                    <div class="col-span-2 md:col-span-1 lg:col-span-1 flex justify-center">
                        <flux:button type="button" icon="plus" size="sm" variant="primary" wire:click="addItem"></flux:button>
                    </div>
                    
                    
                </div>
            </div>

             <div class="flex justify-center mt-5">
                <flux:button variant="primary" color="sky" class="w-full" class="" type="submit">Save</flux:button>
            </div>

        </flux:card>

    </form>

</section>