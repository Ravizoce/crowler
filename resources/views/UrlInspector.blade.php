<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-white-900 dark:text-white">
            </div>
        </div>
    </div>

    <div>
        {{-- <div class="modal fade" id="payModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="payModalLabel" aria-hidden="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-6 d-flex align-items-center">
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <div class="text w-100 text-center py-5">
                                    <h4 class="mb-4">Live Appointment</h4>
                                    <h5 class="mb-4" style="line-height: 2rem">
                                        " Astrology is like gravity. You don't have to believe in it for it to
                                        be working in your life. "
                                    </h5>
                                </div>
                            </div>
                            <div class="paypass col-md-6 d-flex">
                                <div class="text w-100 text-center py-5">
                                    <h4 class="mb-4">Select Payment Method</h4>
                                    <a href="" class="btn btn-danger btn-sm">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white-900 dark:text-white">
                        <table class="mt-3 table border table-inverse text-white">
                            <thead class="thead-inverse table-borderless">
                                <tr>
                                    <th>Urls</th>
                                    <th>Status</th>
                                    <th>Crowler Name</th>
                                    <th>buttons</th>
                                </tr>
                            </thead>
                            @foreach ($urls as $url)
                                <tbody>
                                    <td scope="row">{{ $url->urls }}</td>
                                    <td scope="row">{{ $url->status }}</td>
                                    <td scope="row">{{ $url->crowlers->name }}</td>
                                    <td scope="row">
                                        {{-- <form action="{{ url() }}" method="post" style="display: inline"> --}}
                                            @csrf
                                            <input type="text" name="crowler_id" id="crowler_id" value=''
                                                hidden>
                                            <x-primary-button>{{ __('Select') }}</x-primary-button>

                                        {{-- </form> --}}
                                    </td>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <h1>
            </h1>
        </div>
</x-app-layout>
