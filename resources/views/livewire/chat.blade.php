<div class="flex flex-col h-full max-w-[55%] mx-auto"
     x-data="{
        currentMessage: '',
        isLoading: false
    }">
    <div class="flex flex-col grow gap-3">
        {{--        Persistent messages --}}
        @foreach($messages as $message)
            @if($message['type'] == 'user')
                <div class="bg-zinc-100 dark:bg-zinc-600 rounded p-2"
                     x-data="{source: 'user'}"
                     x-bind:class="source = 'user' ? 'self-end min-w-[75%] max-w-[75%]' : 'self-start' ">
                    {{ $message['prompt'] }}
                </div>
                <div>
                    @markdown($message['modelAnswer'])
                </div>
            @endif
        @endforeach

        {{--        Live updating --}}
        <div class="bg-zinc-100 dark:bg-zinc-600 rounded p-2"
             x-data="{source: 'user'}"
             x-bind:class="source = 'user' ? 'self-end min-w-[75%] max-w-[75%]' : 'self-start' "
             x-text="currentMessage"
             x-show="isLoading"
             wire:loading>
        </div>
        <div wire:stream="model-stream-answer" >

        </div>
        <div wire:loading.class="flex" wire:loading.class.remove="hidden" class="text-sm text-muted-foreground hidden items-center gap-3">
            <svg class="fill-muted-foreground" width="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z">
                    <animateTransform attributeName="transform" type="rotate" dur="0.75s" values="0 12 12;360 12 12"
                                      repeatCount="indefinite"/>
                </path>
            </svg>
            Модель думает... Может что нибудь придумает...
        </div>
    </div>

    <div class="relative">
        <flux:textarea x-model="currentMessage" wire:model="userPrompt" resize="none"
                       placeholder="{{ $this->randomPlaceholder() }}"/>
        <button
            class="absolute right-3 bottom-3 bg-zinc-800 hover:bg-zinc-700 dark:bg-zinc-50 hover:dark:bg-zinc-200 transition-all
             rounded-2xl p-2 cursor-pointer"
            wire:click="sendMessage"
            @click="isLoading = true"
            x-on:loading-completed="alert('ХУЙ')">
            <flux:icon icon="arrow-up" class="text-sm text-zinc-50 dark:text-zinc-800"></flux:icon>
        </button>
    </div>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('message-accepted', (e) => {
                document.getElementById('userMessage').innerHTML = e.detail.message
            })
        })
    </script>
    <style>
        ul, ol {
            list-style: unset!important;
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 1rem;
        }
        h1,
        h2,
        h3,
        h4 {
            margin-bottom: 0.5rem;
        }

        h1 {
            font-size: 2rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        h3 {
            font-size: 1.2rem;
        }

        h4 {
            font-size: 1rem;
        }
    </style>
</div>
