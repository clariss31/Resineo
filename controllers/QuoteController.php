<?php

class QuoteController
{
    public function showQuote()
    {
        // Affiche la page de devis (panier)
        $view = new View("Mon Devis");
        $view->render("quote");
    }
}
