<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl justify-centers mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6  bg-white border-b border-gray-200">
                    Group ID : {{ $group->id}}<br />
                    Group name : {{ $group->name}}

                </div>
            </div>

            <div x-data="{ users: [], currentUserId : '' ,   emojis : [ '💪', '👀', '🥳', '😍', '🥰', '😎', '😂', '🤗' ]  }"
                x-init="init">

                <div class="mb-3 xl:w-96">
                    <div class="flex justify-center">
                        <div class="mb-3 xl:w-96">
                            <select @change="changeState($event.target.value)"
                                class="form-select appearance-noneblock w-20 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none">
                                <option></option>
                                <template x-for="emoji in emojis">
                                    <option x-text="emoji"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <a href="{{route('dashboard')}}"
                    class="inline-flex items-center px-4 py-2 mb-8 mt-8 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">leave
                </a>
                <ul
                    class="w-48 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <template x-for="user in users">
                        <li class="py-2 px-4 w-full rounded-t-lg border-b border-gray-200 dark:border-gray-600">
                            <span x-text="user.state"></span>
                            -
                            <strong x-text="user.name"></strong>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/app.js') }}"></script>

    <script>
    function init() {
        this.currentUserId = "{{auth()->user()->id}}"


        channel = Echo.join(`presence.{{$group->id}}`)

        this.changeState = (state) => {

            channel
                .whisper('changeState', {
                    state,
                    userId: this.currentUserId
                });


        }
        channel
            .here((users) => {
                this.users = users
            })
            .joining((user) => {
                this.users.push(user)
            })
            .leaving((user) => {
                this.users.splice(this.users.indexOf(user), 1)
            })
            .error((error) => {
                console.log(error)
            })
            .listenForWhisper('changeState', (e) => {

                this.users.map(user => {
                    if (user.id == e.userId) {
                        user.state = e.state
                    }
                })

            });


    }
    </script>
</x-app-layout>