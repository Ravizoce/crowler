<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-white-900 dark:text-white">
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white-900 dark:text-white">
                    <table class="mt-3 table border table-inverse text-white ">
                        <thead class="thead-inverse table-borderless" >
                            <tr>
                                <th>Urls</th>
                                <th>Status</th>
                                <th>Crowler Name</th>
                                <th>buttons</th>
                            </tr>
                        </thead>
                        @foreach ($urls as $url)
                            <tbody>
                                <td scope="row" style="max-width: 200px; word-break: break-all;">{{ $url->urls }}
                                </td>
                                <td scope="row">{{ $url->status }}</td>
                                <td scope="row">{{ $url->crowlers->name }}</td>
                                <td scope="row">
                                    <x-normal-link href="{{ url('Urls', $url->id) }}"> {{ __('Enter') }}
                                    </x-normal-link>

                                </td>
                            </tbody>
                        @endforeach
                    </table>
                    <div class="mt-3 pagination-links">
                        {{ $urls->links() }}
                    </div>
                </div>
            </div>
        </div>
        <h1>
        </h1>
    </div>

</x-app-layout>
