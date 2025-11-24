<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">Sitemap scrapper</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class="mt-5" size="xl">Sitemap scrapper</flux:heading>

    <div class="flex flex-col gap-3 mt-5">
        @for($i = 0; $i < count($linksToSearch); ++$i)
            <flux:field>
                <flux:label>Link to scrap</flux:label>
                <flux:input wire:model="linksToSearch.{{ $i }}"/>
            </flux:field>
        @endfor
        @if(count($searchedLinks) == 0)
            <flux:button class="self-end" variant="primary" wire:click="searchLinks">Scrap sitemap</flux:button>
        @endif
    </div>
    <div class="flex items-end gap-3 mt-5">
        <flux:field>
            <flux:label>Regex Filter</flux:label>
            <flux:input wire:model="regexFilter"/>
        </flux:field>
        <flux:button wire:click="applyFilter">Apply filter</flux:button>
    </div>

    @if(count($searchedLinks) > 0)
        <div class="flex justify-between items-center mt-5">
            <flux:heading size="lg">Searched links</flux:heading>
            <div class="self-end flex gap-3">
                <flux:button wire:click="rescrap">Re-scrap these links</flux:button>
                <flux:button wire:click="collectInfo" x-on:click="$flux.modal('progress').show()" variant="primary">Use them to collect info</flux:button>
            </div>
        </div>
        <div class="flex flex-col gap-3 mt-5">
            @foreach($searchedLinks as $link)
                <span>{{$link}}</span>
            @endforeach
        </div>
    @endif

    <flux:modal name="progress" :dismissible="false"
                x-data="{progress: 0}"
                x-on:progress="progress = $event.detail.progress">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Scrapping pages</flux:heading>
                <flux:text class="mt-2">Wait till end...</flux:text>
            </div>

            <div wire:stream="progress">
                Process completed!
            </div>
{{--            <flux:button wire:click="test"></flux:button>--}}
        </div>
    </flux:modal>


</div>
