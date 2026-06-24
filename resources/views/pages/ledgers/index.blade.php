<?php

use App\Models\Ledger;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;


new #[Title('Ledgers')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $deletingLedgerId = null;
    public bool $showDeleteModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingLedgerId = $id;
        $this->showDeleteModal = true;
    }

    public function updateIsActive(int $id): void
    {
        $ledger = Ledger::findOrFail($id);

        $ledger->update([
            'is_active' => ! $ledger->is_active,
        ]);

         Flux::toast(
            variant: 'success',
            text: $ledger->fresh()->is_active
                ? __('Actived Successfully.')
                : __('Disabled Successfully.')
        );
    }

    public function updateStar(int $id): void
    {
        $ledger = Ledger::findOrFail($id);

        $ledger->update([
            'is_star' => ! $ledger->is_star,
        ]);

        Flux::toast(
            variant: 'success',
            text: $ledger->fresh()->is_star
                ? __('Added to favorites.')
                : __('Removed from favorites.')
        );
    }

    public function delete(): void
    {
        $ledger = Ledger::findOrFail($this->deletingLedgerId);

        $ledger->delete();

        $this->showDeleteModal = false;

        Flux::toast(
            variant: 'success',
            text: __('Ledger deleted successfully.')
        );

         $this->redirect(
            route('ledgers.index'),
            navigate: true
        );
    }

    #[Computed]
    public function ledgers()
    {
        return Ledger::query()->where('user_id', Auth::user()->id)
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(25);
    }
};

?>

<section class="w-full">

    <flux:button.group>
        <flux:button variant="filled"  icon="arrow-left" size="sm"  :href="route('dashboard')" wire:navigate>Back</flux:button>
        <flux:button variant="filled"  icon="plus" size="sm" :href="route('ledgers.create')" wire:navigate>Create Ledger</flux:button>
    </flux:button.group>

    <div class="my-6 grid grid-cols-1 md:grid-cols-4 gap-1 items-center">
        
        <div class="md:col-span-2">
            <flux:heading size="xl">Ledgers</flux:heading>
            {{-- <flux:text class="mt-2">Manage system ledgers.</flux:text> --}}
        </div>

        <div class="md:col-span-2">
            <flux:input
                size="sm"
                wire:model.live.debounce.500ms="search"
                icon="magnifying-glass"
                placeholder="Search ledgers..."
            />
        </div>

    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($this->ledgers as $ledger)
            <flux:card>
                <div>
                    <flux:text size="lg" color="sky" class="font-bold" >{{ Str::title($ledger->name ?? '') }}</flux:text>
                    <flux:text class="m-0">{{ $ledger->ledgerType->name_bn ?? '' }}</flux:text>
                </div>
                <div class="items-center justify-center">
                    <flux:text size="xs" class="m-0"> <span class="font-bold">Last Opened:</span> {{ $ledger->opened_at ? $ledger->opened_at->format('M j, Y') : 'N/A' }}</flux:text>
                    <flux:text size="xs" class="m-0"> <span class="font-bold">Created:</span> {{ $ledger->created_at ? $ledger->created_at->format('M j, Y') : 'N/A' }}</flux:text>
                    <flux:text size="xs" class="m-0"> <span class="font-bold">Transactions:</span> {{$ledger->transactions->count() ?? '0'}}</flux:text>
                </div>

                <div class="flex gap-2 mt-4 items-center justify-center">
                    <flux:button size="sm" variant="primary" color="" icon="eye" :href="route('ledgers.show', $ledger)" wire:navigate></flux:button>
                    {{-- <flux:button size="sm" variant="primary" color="yellow" icon="pencil" :href="route('ledgers.edit', $ledger)" wire:navigate></flux:button> --}}
                    <flux:button
                    size="sm"
                    icon="heart"
                    wire:click="updateStar({{ $ledger->id }})"
                    variant="primary"
                    color="{{ $ledger->is_star ? 'pink' : '' }}"
                    class=""
                    ></flux:button>
                    
                    <flux:button
                    size="sm"
                    icon="check-badge"
                    wire:click="updateIsActive({{ $ledger->id }})"
                    variant="primary"
                    color="{{ $ledger->is_active ? 'green' : '' }}"
                    class=""
                    ></flux:button>
                    
                    <flux:button size="sm" variant="danger"  icon="trash"  wire:click="confirmDelete({{ $ledger->id }})"></flux:button>

                </div>
            </flux:card>
        @endforeach
    </div>

    <div class="mt-6">

        {{ $this->ledgers->links() }}

    </div>

    <flux:modal wire:model="showDeleteModal">

        <div class="space-y-4">
            <flux:heading>Delete Ledger</flux:heading>
            <flux:text>
                Are you sure you want to delete this ledger?
            </flux:text>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)"> Cancel</flux:button>
                <flux:button variant="danger" wire:click="delete">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

</section>
