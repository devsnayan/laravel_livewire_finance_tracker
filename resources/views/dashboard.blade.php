<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
        <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>
        
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
            
            <flux:card>
                <img src="{{asset('/assets/images/poster2.png')}}" class="w-full h-auto rounded-lg"/>
            </flux:card>

            <flux:card>
                <img src="{{asset('/assets/images/poster.png')}}" class="w-full rounded-lg"/>

                <div class=" mt-6">
                    <flux:text size="lg" class="font-bold">Developer: Mohammad Nayan</flux:text>
                    <flux:text size="sm" class="font-bold">Phone: 01690 091590</flux:text>
                    <flux:text size="sm" class="font-bold">Web: www.nayan.pro</flux:text>
                </div>
            </flux:card>

        </div>
    </div>
</x-layouts::app>


