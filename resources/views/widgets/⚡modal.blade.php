<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public array $stack = [];
    public ?string $model_id = null;
    public ?string $model_type = null;
    public ?array $params = null;

    #[On('open_modal')]
    public function open(array $payload): void
    {
        $this->stack[] = $payload;
        $this->model_id = $payload['model_id'] ?? null;
        $this->model_type = $payload['model_type'] ?? null;
        $this->params = $payload['params'] ?? null;
    }

    #[On('close_modal')]
    public function close(): void
    {
        array_pop($this->stack);
        $this->model_id = null;
        $this->model_type = null;
        $this->params = null;
    }

};
?>

<div>
    @foreach($stack as $modal)
        <livewire:is
            :component="$modal['modal']"
            :model_id="$modal['model_id'] ?? null"
            :model_type="$modal['model_type'] ?? null"
            :params="$modal['params'] ?? null"
            :key="$loop->index"
        />
    @endforeach
</div>
