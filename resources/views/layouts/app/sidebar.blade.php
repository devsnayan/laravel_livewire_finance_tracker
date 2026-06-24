<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            {{-- <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" /> --}}

            <flux:spacer />

            <x-desktop-user-menu />
        </flux:header>
        
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <flux:text size="xl" class="font-bold w-full text-center">
                    আমার খাতা
                </flux:text>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                        {{-- <flux:sidebar.item icon="credit-card" :href="route('dashboard')" :current="request()->routeIs('settings.profile')" wire:navigate>
                            Transactions
                        </flux:sidebar.item> --}}

                    <flux:sidebar.item icon="book-open" :href="route('ledgers.index')" :current="request()->routeIs('ledgers*')" wire:navigate>
                        Ledgers
                    </flux:sidebar.item>

                    {{-- <flux:sidebar.group icon="link" expandable expanded="false" heading="Link Management" class="grid" >
                        <flux:sidebar.item icon="book-open" :href="route('links.index')" :current="request()->routeIs('links.index')" wire:navigate>Links</flux:sidebar.item>
                        <flux:sidebar.item icon="puzzle-piece" :href="route('link_categories.index')" :current="request()->routeIs('link_categories.index')" wire:navigate>Categories</flux:sidebar.item>
                    </flux:sidebar.group> --}}

                    <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>
                        Users
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.index')" wire:navigate>
                        Roles
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="key" :href="route('permissions.index')" :current="request()->routeIs('permissions.index')" wire:navigate>
                        Permissions
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="presentation-chart-line" :href="route('dashboard')" :current="request()->routeIs('settings.profile')" wire:navigate>
                        Activities
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="cog" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>
                        Settings
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="user" :href="route('profile.show')" :current="request()->routeIs('profile.show')" wire:navigate>
                        Profile
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="arrow-left-end-on-rectangle" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>
                        Logout
                    </flux:sidebar.item>

                </flux:sidebar.group>
            </flux:sidebar.nav>
            

            <flux:spacer />
                    {{-- @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer text-center"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form> --}}

        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
