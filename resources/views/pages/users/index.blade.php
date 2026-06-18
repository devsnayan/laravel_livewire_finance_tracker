<?php

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Users')] class extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $deletingUserId = null;

    public bool $showDeleteModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingUserId = $id;

        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $user = User::findOrFail($this->deletingUserId);

        if ($user->id === auth()->id()) {

            Flux::toast(
                variant: 'danger',
                text: __('You cannot delete your own account.')
            );

            return;
        }

        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $this->showDeleteModal = false;

        Flux::toast(
            variant: 'success',
            text: __('User deleted successfully.')
        );
    }

    #[Computed]
    public function users()
    {
        return User::query()

            ->when(
                $this->search,
                fn ($query) => $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhere('title', 'like', "%{$this->search}%");
                })
            )

            ->latest()
            ->paginate(10);
    }
};

?>

<section class="w-full">

    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-center">

        <div class="md:col-span-1">
            <flux:heading size="xl">Users</flux:heading>
            <flux:text class="mt-2">Manage system users.</flux:text>
        </div>

        <div class="md:col-span-2">
            <flux:input
                size="sm"
                wire:model.live.debounce.500ms="search"
                icon="magnifying-glass"
                placeholder="Search users..."
            />
        </div>

        <div class="flex md:justify-end">
            <flux:button
                size="sm"
                variant="primary"
                :href="route('users.create')"
                wire:navigate
            >
                Create User
            </flux:button>
        </div>

    </div>

    <flux:table>

        <flux:table.columns>
            <flux:table.column>Avatar</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Phone</flux:table.column>
            <flux:table.column>Created</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @forelse($this->users as $user)

                <flux:table.row>

                    <flux:table.cell>
                        @if($user->avatar)
                            <img
                                src="{{ asset('storage/'.$user->avatar) }}"
                                class="size-10 rounded-full object-cover"
                                alt="{{ $user->name }}"
                            >
                        @else
                            <img
                                src="{{ asset('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEy7pwqmpRqSf5t5v1dCOJsCsYlXeBGjIg3i3Z2HIzvg&s=10') }}"
                                class="size-10 rounded-full object-cover"
                                alt="{{ $user->name }}"
                            >
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <p class="m-0"><strong>{{ $user->name }}</strong></p>
                        <small>{{ $user->title ?? '' }}</small>
                    </flux:table.cell>
                    <flux:table.cell>{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>{{ $user->phone }}</flux:table.cell>
                    <flux:table.cell>{{ $user->created_at->format('M j, Y') }}</flux:table.cell>
                    
                    <flux:table.cell>
                        <div class="flex gap-2">
                            <flux:button size="sm" :href="route('users.edit', $user)" wire:navigate>Edit</flux:button>
                            <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $user->id }})">Delete</flux:button>
                        </div>

                    </flux:table.cell>

                </flux:table.row>

            @empty
                <flux:table.row>

                    <flux:table.cell colspan="6" class="text-center">
                        <flux:text>No users found.</flux:text>
                    </flux:table.cell>

                </flux:table.row>
            @endforelse

        </flux:table.rows>

    </flux:table>

    <div class="mt-6">

        {{ $this->users->links() }}

    </div>

    <flux:modal wire:model="showDeleteModal">

        <div class="space-y-4">
            <flux:heading>Delete User</flux:heading>
            <flux:text>
                Are you sure you want to delete this user?
                This action cannot be undone.
            </flux:text>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)"> Cancel</flux:button>
                <flux:button variant="danger" wire:click="delete">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

</section>
