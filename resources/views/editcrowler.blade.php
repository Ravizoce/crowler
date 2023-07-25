<x-guest-layout>
    <x-slot name='optional'>
<div>

</div>
    <form method="POST" action="{{ url('crowler', $crowler[0]['id']) }}">
        @csrf
        @method('PUT')
        <div>
            <x-input-label for="name" :value="__('Crowler Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$crowler[0]['name']"
                required/>
        </div>

        <div class="mt-4">
            <x-input-label for="url" :value="__('URL')" />

            <x-text-input id="url" class="block mt-1 w-full" type="url" name="url" required :value="$crowler[0]['url']"/>
        </div>
        <div>
            <x-input-label for="author" :value="__('author')" />
            <x-text-input id="author" class="block mt-1 w-full" type="text" name="author" :value="$crowler[0]['author']"
                required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</x-slot >
</x-guest-layout>
