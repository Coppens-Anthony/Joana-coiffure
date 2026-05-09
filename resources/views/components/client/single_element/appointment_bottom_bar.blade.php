<div
    id="appointment-bar"
    class="hidden js:block bg-tertiary rounded-t-3xl z-10 p-4 md:p-8 fixed bottom-0 left-8 right-8 md:left-16 md:right-16 shadow-[0_0_10px_rgba(0,0,0,0.1)]">

    <div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-center">

        <p id="empty-message">
            Aucune prestation sélectionnée
        </p>

        <div id="appointment-content" class="hidden flex-col gap-4">
            <p id="appointment-summary"></p>

            <ul id="selected-services" class="flex flex-wrap gap-4">

            </ul>
        </div>

        <x-global.linkbutton.button_link
            class="next-step"
            title="Passer à l'étape du choix de la date">
            Continuer
        </x-global.linkbutton.button_link>

    </div>
</div>
