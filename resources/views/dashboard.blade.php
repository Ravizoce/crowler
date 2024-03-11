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
                    <div class="card-deck">
                        <div class="row justify-content-center"">
                            <div class="m-4  card bg-gray-800" >
                                @if (empty($count) || $count == 0)
                                    <div class="card-body dark:text-white ">
                                        <h1 class="font-weight-bold">Last Complet crowl</h1>
                                        <dl>
                                            <dt>Start</dt>
                                            <dl></dl>

                                            <dt>End</dt>
                                            <dl></dl>

                                            <dt>Total URLs</dt>
                                            <dl>nothing</dl>
                                        </dl>
                                    </div>
                                    <div>
                                        <form action="{{ url('scrap') }} " method="post">
                                            @csrf
                                            <input name="crowler_id" id="crowler_id" value="{{ $crowler_id }}" hidden>
                                            <x-primary-button id="Autocrowl" hidden>{{ __('scrap') }}
                                            </x-primary-button>
                                        </form>
                                    </div>
                                    {{-- {{dd("stope")}} --}}
                                @else
                                    <div class="card-body dark:text-white"">
                                        <h1 class="font-weight-bold h4">Last Complet crowl</h1>
                                        <dl>
                                            <dt>Start</dt>
                                            <dl>{{ $startDateString }}</dl>

                                            <dt>End</dt>
                                            <dl>{{ $endDateString }}</dl>

                                            <dt>Total Duration</dt>
                                            <dl>
                                                @php
                                                    echo $differ;
                                                @endphp
                                            </dl>

                                            <dt>Total URLs</dt>
                                            <dl>{{ $count }}</dl>
                                        </dl>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h1>
        </h1>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                console.log("hello");
                document.getElementById("Autocrowl").click();
            }, 20);
        });
        window.onpopstate = function(event) {
            if (event && event.state && event.state.goBack) {
                history.back();
            }
        };
    </script>
</x-app-layout>
