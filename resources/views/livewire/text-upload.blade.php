<div class="flex flex-col h-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">Text upload</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class="mt-5" size="xl">Text upload</flux:heading>
    <flux:field class="mt-5">
        <flux:label badge="Required">Source</flux:label>
        <flux:input wire:model="source"/>
    </flux:field>
    <div class="mt-5 grow flex flex-col gap-3">
        <flux:label badge="Required">Text</flux:label>
        <flux:textarea class="h-full" wire:model="text"/>
    </div>
    <div class="flex justify-end mt-5">
        <flux:button variant="primary" wire:click="submit">Загрузить</flux:button>
    </div>
</div>
