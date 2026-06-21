<?php

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new #[Title('Edit User')] class extends Component {

    use WithFileUploads;

    public User $user ;

    public string $name = '';
    public string $email = '';
    public string $title = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public TemporaryUploadedFile|string|null $avatar = null;

    public function mount(?User $user = null): void
    {
        $this->user = $user ?? Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->title = $this->user->title ?? '';
        $this->phone = $this->user->phone ?? '';
        $this->avatar = $this->user->avatar;
    }

};

?>

<section class="w-full">

    <flux:heading size="xl">
        {{ __('Profile') }}
    </flux:heading>

    <flux:text class="mt-2">
        {{ __('Viewing information of: ') }}  {{ $user->name ?? '' }}
    </flux:text>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-7 gap-1 ">
        <div class="md:col-span-2"></div>
        <div class="md:col-span-3">
            <flux:card class="space-y-6 mt-6 w-full max-w-2xl mx-auto">
                <div class="flex flex-col items-center">
                    <flux:avatar size="xl" src="{{ asset('storage/'.$user->avatar) }}" />

                    <flux:heading size="lg">{{ $user->name ?? '' }}</flux:heading>
                    <flux:text class="mt-0">{{ $user->title ?? '' }}</flux:text>

                    <flux:text class="mt-2"><span class="font-bold">Email: </span> {{ $user->email ?? '' }}</flux:text>
                    <flux:text class="mt-2"><span class="font-bold">Phone: </span> {{ $user->phone ?? '' }}</flux:text>
                    <flux:text class="mt-2"><span class="font-bold">Created: </span> {{ $user->created_at->format('F j, Y') ?? '' }}</flux:text>

                    <div class=" mt-4 flex gap-2">
                            {{-- <flux:button size="xs" icon="eye" :href="route('users.show', $user)" wire:navigate></flux:button> --}}
                            <flux:button size="xs" icon="pencil" :href="route('users.edit', $user)" wire:navigate></flux:button>
                            {{-- <flux:button size="xs" icon="trash" variant="danger" wire:click="confirmDelete({{ $user->id }})"></flux:button> --}}
                        </div>

                </div>
            </flux:card>
        </div>
        <div class="md:col-span-2"></div>
    </div>


</section>
