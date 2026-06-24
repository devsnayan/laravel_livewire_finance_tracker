<?php

use Flux\Flux;
use App\Models\Ledger;
use Livewire\Component;
use App\Models\LedgerType;
use Livewire\Attributes\Title;

new #[Title('Create Ledger')] class extends Component {

    public ?int $ledger_type_id = null;

    public string $name = '';
    public string $title = '';
    public ?string $notes = null;

    public bool $is_star = false;
    public bool $is_active = true;

    public ?string $opened_at = null;

    public function save(): void
    {
        $validated = $this->validate([
            'ledger_type_id' => ['required', 'exists:ledger_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'opened_at' => ['nullable', 'date'],
        ]);

        Ledger::create([
            'user_id' => auth()->id(),
            'ledger_type_id' => $validated['ledger_type_id'],
            'name' => $validated['name'],
            'title' => $validated['title'],
            'notes' => $validated['notes'],
            'opened_at' => $validated['opened_at'],
            'is_star' => $this->is_star,
            'is_active' => $this->is_active,
        ]);

        Flux::toast(
            variant: 'success',
            text: __('Ledger created successfully.')
        );

        $this->redirect(
            route('ledgers.index'),
            navigate: true
        );
    }

    public function getLedgerTypesProperty()
    {
        return LedgerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
};

?>

<section class="w-full">
    <flux:button.group>
        <flux:button variant="filled"  icon="arrow-left" size="sm"  :href="route('ledgers.index')" wire:navigate>Back</flux:button>
    </flux:button.group>

    <div class="mt-4 flex items-center justify-between mb-2">
        <flux:heading size="xl">Create Ledger</flux:heading>
    </div>

    <form wire:submit="save" class="my-6 w-full space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-2">
            
            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:select wire:model="ledger_type_id" label="Ledger Type" required>
                    <option value="">Select Ledger Type</option>
                    @foreach($this->ledgerTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </flux:select>
            </div>
                
            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:input wire:model="name" label=" Name"type="text" required/>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:input wire:model="notes" label="Notes" type="text"/>
            </div>

            <div class="col-span-1 md:col-span-1 lg:col-span-1">
                <flux:field>
                    <flux:label> Is Favorite</flux:label>
                    <flux:switch wire:model="is_star"/>
                </flux:field>
            </div>

            <div class="col-span-1 md:col-span-1 lg:col-span-1">
                <flux:field >
                    <flux:label> Is Active</flux:label>
                    <flux:switch  wire:model="is_active"/>
                </flux:field>
            </div>

        </div>


        <div class="flex items-center gap-4">
            <flux:button variant="primary" color="green" type="submit">Save Ledger</flux:button>
        </div>

    </form>

</section>