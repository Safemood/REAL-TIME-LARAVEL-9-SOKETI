<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="message" class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>

    </div>
    <div x-data="{loading: false }" class="max-w-7xl ml-5 mx-auto sm:px-6 lg:px-8">
        <x-button @click="upload" x-text="!loading ? 'Upload' : 'Loading...' " type="button">
            Upload
        </x-button>

        <div class="py-12">

            <span class="py-2 px-2">
                {{$group->name}}
                <a class="underline" href="{{route('group',$group->id)}}">Join</a>
            </span>

        </div>

    </div>


    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    async function upload() {

        this.loading = true
        await axios.get("{{route('fire.private.event')}}", {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        }).then(res => this.loading = false)
    }
    Echo.private('private.{{auth()->user()->id}}')
        .listen('PrivateEvent', (e) => document.getElementById('message').innerText = e.message);
    </script>

</x-app-layout>