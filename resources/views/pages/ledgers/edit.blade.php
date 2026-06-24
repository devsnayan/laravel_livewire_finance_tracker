<?php

use Flux\Flux;
use App\Models\Ledger;
use Livewire\Component;
use App\Models\LedgerType;
use Livewire\Attributes\Title;

new #[Title('Edit Ledger')] class extends Component {

    public Ledger $ledger;

    public ?int $ledger_type_id = null;

    public string $name = '';
    public string $title = '';
    public ?string $notes = null;

    public bool $is_star = false;
    public bool $is_active = true;

    public ?string $opened_at = null;

    public function mount(Ledger $ledger): void
    {
        abort_if(
            $ledger->user_id !== auth()->id(),
            403
        );

        $this->ledger = $ledger;

        $this->ledger_type_id = $ledger->ledger_type_id;
        $this->name = $ledger->name;
        $this->title = $ledger->title ?? '';
        $this->notes = $ledger->notes;
        $this->is_star = $ledger->is_star;
        $this->is_active = $ledger->is_active;
        $this->opened_at = $ledger->opened_at?->format('Y-m-d');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'ledger_type_id' => ['required', 'exists:ledger_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'opened_at' => ['nullable', 'date'],
        ]);

        $this->ledger->update([
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
            text: __('Ledger updated successfully.')
        );

        $this->redirect(
            route('ledgers.show', $this->ledger),
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
        <flux:button
            variant="filled"
            icon="arrow-left"
            size="sm"
            :href="route('ledgers.show', $ledger)"
            wire:navigate
        >
            Back
        </flux:button>
    </flux:button.group>

    <div class="mt-4 flex items-center justify-between mb-2">
        <flux:heading size="xl">
            Edit Ledger
        </flux:heading>
    </div>

    <form wire:submit="save" class="my-6 w-full space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-2">

            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:select
                    wire:model="ledger_type_id"
                    label="Ledger Type"
                    required
                >
                    <option value="">Select Ledger Type</option>

                    @foreach($this->ledgerTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach

                </flux:select>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:input
                    wire:model="name"
                    label="Name"
                    type="text"
                    required
                />
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:input
                    wire:model="title"
                    label="Title"
                    type="text"
                />
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <flux:input
                    wire:model="opened_at"
                    label="Opened Date"
                    type="date"
                />
            </div>

            <div class="col-span-1 md:col-span-4 lg:col-span-4">
                <flux:input
                    wire:model="notes"
                    label="Notes"
                    type="text"
                />
            </div>

            <div class="col-span-1 md:col-span-1 lg:col-span-1">
                <flux:field>
                    <flux:label>Is Favorite</flux:label>
                    <flux:switch wire:model="is_star" />
                </flux:field>
            </div>

            <div class="col-span-1 md:col-span-1 lg:col-span-1">
                <flux:field>
                    <flux:label>Is Active</flux:label>
                    <flux:switch wire:model="is_active" />
                </flux:field>
            </div>

        </div>

        <div class="flex items-center gap-4">

            <flux:button
                variant="primary"
                color="sky"
                type="submit"
            >
                Update Ledger
            </flux:button>

        </div>

    </form>

</section>