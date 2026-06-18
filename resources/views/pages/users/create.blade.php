<?php

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new #[Title('Create User')] class extends Component {

    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $title = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public TemporaryUploadedFile|string|null $avatar = null;

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $avatarPath = null;

        if ($this->avatar instanceof TemporaryUploadedFile) {
            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        User::create([
            'name' => $validated['name'],
            'title' => $validated['title'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'avatar' => $avatarPath,
            'password' => Hash::make($validated['password']),
        ]);

        Flux::toast(
            variant: 'success',
            text: __('User created successfully.')
        );

        $this->redirect(
            route('users.index'),
            navigate: true
        );
    }
};

?>

<section class="w-full">

    <flux:heading size="xl">
        {{ __('Create User') }}
    </flux:heading>

    <flux:text class="mt-2">
        {{ __('Create a new system user.') }}
    </flux:text>

    <form
        wire:submit="save"
        class="my-6 w-full space-y-6"
        enctype="multipart/form-data"
    >

        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
        />

        <flux:input
            wire:model="title"
            :label="__('Title')"
            type="text"
        />

        <flux:input
            wire:model="phone"
            :label="__('Phone Number')"
            type="tel"
        />

        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
        />

        <flux:input
            wire:model="avatar"
            :label="__('Avatar')"
            type="file"
            accept="image/*"
        />

        @if ($avatar)
            <div>
                <img
                    src="{{ $avatar->temporaryUrl() }}"
                    class="w-20 h-20 rounded-full object-cover border"
                    alt="Preview"
                >
            </div>
        @endif

        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
        />

        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm Password')"
            type="password"
            required
        />

        <div class="flex items-center gap-4">

            <flux:button
                variant="primary"
                type="submit"
            >
                {{ __('Save User') }}
            </flux:button>

            <flux:button
                variant="ghost"
                :href="route('users.index')"
                wire:navigate
            >
                {{ __('Cancel') }}
            </flux:button>

        </div>

    </form>

</section>
