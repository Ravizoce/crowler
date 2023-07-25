<x-guest-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        </h2>
    </x-slot> --}}
    <x-slot name='optional'>
        @if (isset($errorMessage))
            <div class="alert alert-danger text-denger">
                {{ $errorMessage }}
            </div>
        @endif
        <div class="py-12">
            <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg ">
                    <div class="p-6 text-white-900 dark:text-white  ">
                        <form method="POST" action="{{ url('crowler') }}">
                            @csrf
                            <div class="bg-dark p-2">
                                <div class="m-3">

                                    <x-input-label for="name" :value="__('Crowler Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full input_wrapper" type="text"
                                        name="name" :value="old('')" required />

                                    <x-input-label for="url" :value="__('URL')" />
                                    <x-text-input id="url" class="block mt-1 w-full input_wrapper" type="url"
                                        name="url" :value="old('')" required />

                                    <x-input-label for="author" :value="__('author')" />
                                    <x-text-input id="author" class="block mt-1 w-full input_wrapper" type="text"
                                        name="author" :value="old('')" required />

                                    <input type="number" name="user_id" id="user_id" value="{{ auth()->user()->id }}"
                                        hidden>

                                </div>
                                <div class="m-3 text-right">
                                    <x-primary-button class=""> {{ __('Add') }}</x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>


</x-guest-layout>
