<?php

use Livewire\Component;

new class extends Component
{
    public int $count;

    public function mount(array $params)
    {
        $this->count = $params['count'];
    }
};
?>

<div>
    <livewire:admin.modal modal_title="Confirmation">
        <p>Il y a {{ $this->count }} rendez-vous prévus à cette période. Êtes-vous sûr(e) de vouloir mettre cette période en off ? Les rendez-vous seront alors annulés</p>
    </livewire:admin.modal>
</div>
