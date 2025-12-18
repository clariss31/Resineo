<?php

class LegalController
{
    /**
     * Affiche la page Mentions Légales.
     * @return void
     */
    public function showMentionsLegales()
    {
        $view = new View("Mentions Légales");
        $view->render("mentions_legales");
    }

    /**
     * Affiche la page Politique de Confidentialité.
     * @return void
     */
    public function showPolitiqueConfidentialite()
    {
        $view = new View("Politique de Confidentialité");
        $view->render("politique_confidentialite");
    }
}
