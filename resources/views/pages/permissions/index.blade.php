<?php

use Spatie\Permission\Models\Permission;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;


new #[Title('Permissions')] class extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function permissions()
    {
        return Permission::query()
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(25);
    }
};

?>

<section class="w-full">

    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-1 items-center">

        <div class="md:col-span-2">
            <flux:heading size="xl">Permissions</flux:heading>
            <flux:text class="mt-2">Manage system permissions.</flux:text>
        </div>

        <div class="md:col-span-2">
            <flux:input
                size="sm"
                wire:model.live.debounce.500ms="search"
                icon="magnifying-glass"
                placeholder="Search permissions..."
            />
        </div>

    </div>

    <flux:table>

        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Guard</flux:table.column>
            <flux:table.column>Created</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @forelse($this->permissions as $permission)

                <flux:table.row>

                    <flux:table.cell>
                        <p class="m-0 font-bold">{{ Str::title(str_replace('_', ' ', $permission->name)) }}</p>
                    </flux:table.cell>
                    <flux:table.cell>{{ $permission->guard_name }}</flux:table.cell>
                    <flux:table.cell>{{ $permission->created_at->format('M j, Y') }}</flux:table.cell>

                </flux:table.row>

            @empty
                <flux:table.row>

                    <flux:table.cell colspan="6" class="text-center">
                        <flux:text>No permissions found.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse

        </flux:table.rows>

    </flux:table>

    <div class="mt-6">

        {{ $this->permissions->links() }}

    </div>

</section>
