<x-wire-modal-card wire:model="form.open" width="lg">

    <p class="text-xl text-center mb-2">
        Enviar email
    </p>

    <p class="text-lg text-center uppercase mb-2">
        {{-- Documento F001 - 123 --}}
        {{$form['document']}}
    </p>

    <p class="text-lg text-center uppercase mb-2">
        {{-- 265448687982 - Coderesfree --}}
        {{$form['client']}}
    </p>

    <form wire:submit="sendEmail">
        <x-wire-input wire:model="form.email" type="email" label="Correo electrónico" />

        <x-wire-button type="submit" class="w-full mt-4" label="Enviar correo" />
    </form>
</x-wire-modal-card>