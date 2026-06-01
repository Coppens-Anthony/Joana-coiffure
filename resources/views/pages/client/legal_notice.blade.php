<x-client.layout title="Mentions légales" :isContactOrAppointment="true">
    <div class="flex flex-col gap-16">
        <h2 class="text-[4rem]">Mentions légales</h2>

        <section>
            <h3 class="text-[2rem]">1. Identification du site</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p><strong>Nom du site :</strong> Joana-Coiffure</p>
                <p><strong>Adresse :</strong> Rue de la station 57, 1350 Orp-Jauche</p>
                <p><strong>Numéro de téléphone :</strong> 0466 48 67 77</p>
                <p><strong>Adresse email :</strong> joanastofs@gmail.com</p>
            </div>
        </section>

        <section>
            <h3 class="text-[2rem]">2. Directeur de la publication</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p>Directeur de la publication : Anthony Coppens</p>
                <p>Cette fonction est assurée par le responsable du site.</p>
            </div>
        </section>

        <section>
            <h3 class="text-[2rem]">3. Hébergeur du site</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p><strong>Raison sociale :</strong> Laravel Cloud</p>
                <p><strong>Site web :</strong>
                    <x-global.link-button.link :isTarget="true" route="https://cloud.laravel.com/" title="Envoyer-moi un mail">
                        https://cloud.laravel.com/
                    </x-global.link-button.link>
                </p>
            </div>
        </section>

        <section>
            <h3 class="text-[2rem]">4. Propriété intellectuelle</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p>
                    L'ensemble des contenus présents sur ce site (textes, photographies, visuels, logo, etc.)
                    sont la propriété exclusive de Joana Coiffure, sauf mention contraire.
                </p>
                <p>
                    Toute reproduction, représentation ou diffusion, en tout ou partie, sur quelque support
                    que ce soit, sans l'autorisation expresse de l'éditeur, est interdite et constituerait
                    une contrefaçon sanctionnée par les articles L.335-2 et suivants du Code de la propriété
                    intellectuelle.
                </p>
            </div>
        </section>

        <section>
            <h3 class="text-[2rem]">5. Données personnelles (RGPD)</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p>
                    Les informations transmises via le formulaire de contact sont utilisées
                    uniquement pour répondre à vos demandes et ne sont pas transmises à des tiers.
                </p>
                <p>
                    Conformément au Règlement Général sur la Protection des Données (RGPD),
                    vous disposez d'un droit d'accès, de rectification et de suppression
                    de vos données personnelles. Pour exercer ce droit, vous pouvez contacter :
                    <x-global.link-button.link route="mailto:joanastofs@gmail.com" title="Envoyer-moi un mail">
                        joanastofs@gmail.com
                    </x-global.link-button.link>
                    .
                </p>
            </div>
        </section>

        <section>
            <h3 class="text-[2rem]">6. Cookies</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p>
                    Ce site peut utiliser des cookies techniques strictement nécessaires à son bon fonctionnement.
                    Aucun cookie publicitaire ou de traçage n'est déposé sans votre consentement.
                </p>
                <p>
                    Vous pouvez à tout moment configurer votre navigateur pour refuser les cookies.
                    Cela pourrait toutefois affecter certaines fonctionnalités du site.
                </p>
            </div>
        </section>

        <section>
            <h3 class="text-[2rem]">7. Limitation de responsabilité</h3>
            <div class="flex flex-col gap-2 mt-4">
                <p>
                    Joana Coiffure s'efforce d'assurer l'exactitude et la mise à jour des informations
                    diffusées sur ce site. Toutefois, elle ne peut garantir l'exhaustivité ou l'absence
                    d'erreur des informations publiées.
                </p>
                <p>
                    L'éditeur ne saurait être tenu responsable des dommages directs ou indirects
                    résultant de l'utilisation de ce site ou de l'impossibilité d'y accéder.
                </p>
            </div>
        </section>
    </div>
</x-client.layout>
