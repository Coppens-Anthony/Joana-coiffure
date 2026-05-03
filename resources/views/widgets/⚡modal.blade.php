<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?string $current = null;
    public ?string $model_id = null;
    public ?string $model_type = null;

    #[On('open_modal')]
    public function open(array $payload): void
    {
        $this->current = $payload['modal'];
        $this->model_id = $payload['model_id'] ?? null;
        $this->model_type = $payload['model_type'] ?? null;
    }

    #[On('close_modal')]
    public function close(): void
    {
        $this->current = null;
        $this->model_id = null;
        $this->model_type = null;
    }

};
?>

<div>
    @if(!is_null($current))
        <livewire:is :component="$current"  :model_id="$model_id" :model_type="$model_type" />
    @endif
</div>
