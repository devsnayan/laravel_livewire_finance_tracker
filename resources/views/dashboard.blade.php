<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
        <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2">
            <flux:card>
                <img src="{{asset('/assets/images/poster.png')}}" class="w-full h-auto rounded-lg"/>
            </flux:card>
        </div>
    </div>
</x-layouts::app>


