<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">ChromaDB</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class="mt-5" size="xl">ChromaDB Viewer</flux:heading>

    <form wire:submit="$refresh" class="flex items-end mt-5 gap-3">
        <flux:field class="min-w-[250px]">
            <flux:label badge="By Text">Similarity search</flux:label>
            <flux:input wire:model="similaritySearch"/>
        </flux:field>
        <flux:field class="min-w-[350px]">
            <flux:label>Source search</flux:label>
            <flux:input wire:model="sourceSearch"/>
        </flux:field>
        <flux:field>
            <flux:label>Collection</flux:label>
            <flux:select wire:model="chromaCollection">
                @foreach($chromaCollections as $collection)
                    <flux:select.option>{{ $collection->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>
        <flux:button variant="primary" type="submit">Search</flux:button>
    </form>

    <div class="mt-5">

        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-xl border">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-sm text-body bg-zinc-100 border-b rounded-base border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        URL
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Word count
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $record)

                    <tr class="bg-neutral-primary "
                        x-on:click="shown = !shown">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            {{ $record->id }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $record->sourceUrl }}
                        </td>
                        <td class="px-6 py-4">
                            {{ count(explode(' ', $record->document)) }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:button size="sm" icon="pencil"></flux:button>
                            <flux:button size="sm" icon="trash" wire:click="deleteRecord('{{$record->collection->id}}', '{{$record->id}}')"></flux:button>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td colspan="4" class="px-6 py-4">
                            {{ $record->document }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
