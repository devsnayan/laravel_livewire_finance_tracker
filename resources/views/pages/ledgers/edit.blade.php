<?php

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new #[Title('Edit User')] class extends Component {

    use WithFileUploads;

    public User $user;

    public string $name = '';
    public string $email = '';
    public string $title = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public TemporaryUploadedFile|string|null $avatar = null;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->title = $user->title ?? '';
        $this->phone = $user->phone ?? '';
        $this->avatar = $user->avatar;
    }

    public function update(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required','email','max:255','unique:users,email,' . $this->user->id,],
            'avatar' => ['nullable'],
            'password' => ['nul lable', 'confirmed', 'min:8'],
        ]);

        $avatarPath = $this->user->avatar;

        if ($this->avatar instanceof TemporaryUploadedFile) {

            if ($this->user->avatar) {
                Storage::disk('public')->delete($this->user->avatar);
            }

            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        $this->user->update([
            'name' => $validated['name'],
            'title' => $validated['title'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'avatar' => $avatarPath,
        ]);

        if (! empty($this->password)) {

            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        Flux::toast(
            variant: 'success',
            text: __('User updated successfully.')
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
        {{ __('Edit User') }}
    </flux:heading>

    <flux:text class="mt-2">
        {{ __('Update user information.') }}
    </flux:text>

    <form wire:submit="update" class="my-6 w-full space-y-6" enctype="multipart/form-data"
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

        @if ($avatar instanceof TemporaryUploadedFile)

            <img
                src="{{ $avatar->temporaryUrl() }}"
                class="w-20 h-20 rounded-full object-cover border"
                alt="Preview"
            >

        @elseif($user->avatar)

            <img
                src="{{ asset('storage/'.$user->avatar) }}"
                class="w-20 h-20 rounded-full object-cover border"
                alt="{{ $user->name }}"
            >

        @endif

        <flux:separator />

        <flux:text>
            Leave password blank if you don't want to change it.
        </flux:text>

        <flux:input
            wire:model="password"
            :label="__('New Password')"
            type="password"
        />

        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm Password')"
            type="password"
        />

        <div class="flex items-center gap-4">

            <flux:button
                variant="primary"
                type="submit"
            >
                {{ __('Update User') }}
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
