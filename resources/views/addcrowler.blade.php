<x-guest-layout>
    <x-slot name='optional'>


        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="text-right" style="color: aliceblue">
                <x-normal-link :href="route('logout')"
                    onclick="event.preventDefault();
                            this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-normal-link>
        </form>

        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-10">
                <div class="bg-dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2 text-white-900 dark:text-white">
                        <div class="content">
                            <div class="container" style="margin-top: 10px;">
                                <x-normal-link :href="url('crowler/create')">&#10010;{{ __('Add New Crawler') }}</x-normal-link>
                                {{-- @if ($crowlers->isEmpty())
                                @endif --}}
                                <table class="mt-3 table border table-inverse text-white">
                                    <thead class="thead-inverse table-borderless">
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Author</th>
                                            {{-- <th>Last update</th> --}}
                                            <th></th>
                                        </tr>
                                    </thead>
                                    @if ($crowlers->isEmpty())
                                        <td scope="row" colspan="5">You dosnt have any Crowler register</td>
                                    @else
                                        <tbody>
                                            @foreach ($crowlers as $crowler)
                                                <tr>
                                                    <td scope="row">{{ $crowler->name }}</td>
                                                    <td scope="row">{{ $crowler->status }}</td>
                                                    <td scope="row">{{ $crowler->author }}</td>
                                                    {{-- <td scope="row">{{ $crowler->updated_at }}</td> --}}
                                                    <td scope="row">

                                                        <form action="{{ route('dashboard', $crowler->id) }}"
                                                            method="GET" style="display: inline">
                                                            @csrf
                                                            <x-primary-button
                                                                type="submit">{{ __('Select') }}</x-primary-button>
                                                        </form>
                                                        <x-normal-link href="{{ url('crowler', $crowler->id) }}">
                                                            {{ __('Edit') }}</x-normal-link>
                                                        <form action="{{ url('crowler', $crowler->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-primary-button>
                                                                {{ __('Delete') }}</x-primary-button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </x-slot>

</x-guest-layout>
