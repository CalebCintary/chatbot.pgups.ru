<div class="flex flex-col h-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">Text upload 2.0</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class="mt-5" size="xl">Text upload 2.0</flux:heading>
    <div class="flex items-end gap-3 mt-5">
        <flux:field class="grow">
            <flux:label badge="Required">Source</flux:label>
            <flux:input wire:model="source"/>
        </flux:field>
        <flux:button wire:click="extractText">Extract text</flux:button>
    </div>
    <div class="mt-5 grow flex flex-col gap-3">
        <flux:label badge="Required">Text</flux:label>
        <flux:textarea class="h-full" wire:model="text"/>
    </div>
    <div class="flex justify-end gap-3 mt-5">
        <flux:button wire:click="enhanceText">Enhance with AI</flux:button>
        <flux:button variant="primary" wire:click="submit">Загрузить</flux:button>
    </div>
</div>
