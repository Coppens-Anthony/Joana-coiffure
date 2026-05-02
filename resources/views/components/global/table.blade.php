@props(['titles' => []])

<div class="w-full lg:rounded-2xl lg:border lg:border-primary lg:overflow-hidden">
    <table class="w-full lg:text-center lg:table-fixed">
        <thead class="hidden lg:table-header-group bg-primary">
        <tr>
            @foreach($titles as $title)
                <th class="py-4">
                    {{ $title }}
                </th>
            @endforeach
        </tr>
        </thead>

        <tbody class="flex flex-col gap-4 lg:table-row-group">
        {{ $slot }}
        </tbody>
    </table>
</div>
