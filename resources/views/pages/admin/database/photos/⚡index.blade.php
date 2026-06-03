<?php

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Galerie')]
class extends Component {

};
?>

<div>
    <livewire:admin.database.photos/>
</div>
