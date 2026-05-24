<?php

it('shows home page', function () {
    $page = visit(route('home'));

    $page->assertSee('Joana-Coiffure');
    $page->assertSee('Coiffeuse & visagiste indépendante à Orp-Jauche');
});

it('redirects to contact page', function (int $index) {
    $page = visit(route('home'));

    $page->click('a[title="Vers la page de contact"] >> nth='.$index)
        ->assertUrlIs(route('contact'));
})->with([0, 1, 2, 3]);

