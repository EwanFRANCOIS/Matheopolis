<?php

namespace src\models;

/**
 * Modèle de gestion de la lecture de l'histoire et des personnages.
 */
class StoryModel
{
    /**
     * Lit et renvoie le contenu d'un dialogue.
     *
     * @param int $n Numéro du dialogue.
     * @param string $file Chemin du fichier JSON.
     *
     * @return string Contenu du dialogue ou chaîne vide.
     */
    public function readDialogue(int $n, string $file): string
    {
        $jsonContent = file_get_contents($file);
        $dialogues = json_decode($jsonContent, true);

        foreach ($dialogues as $number) {
            if ($number['n'] === $n) {
                return $number['content'];
            }
        }
        return '';
    }

    /**
     * Récupère l'avatar d'un personnage.
     *
     * @param int $character_id Identifiant du personnage.
     * @param string $file Chemin du fichier JSON.
     *
     * @return string Avatar du personnage ou chaîne vide.
     */
    public function getAvatar(int $character_id, string $file): string
    {
        $jsonContent = file_get_contents($file);
        $dialogues = json_decode($jsonContent, true);

        foreach ($dialogues as $id) {
            if ($id['id'] === $character_id) {
                return $id['avatar'];
            }
        }
        return '';
    }

    /**
     * Récupère le nom d'un personnage.
     *
     * @param int $character_id Identifiant du personnage.
     * @param string $file Chemin du fichier JSON.
     *
     * @return string Nom du personnage ou chaîne vide.
     */
    public function getPersonaName(int $character_id, string $file): string
    {
        $jsonContent = file_get_contents($file);
        $dialogues = json_decode($jsonContent, true);

        foreach ($dialogues as $id) {
            if ($id['id'] === $character_id) {
                return $id['name'];
            }
        }
        return '';
    }
}
