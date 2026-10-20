<?php

namespace src\core;

final class Env
{
    public static function charger(string $chemin): void
    {
        if (!is_file($chemin)) {
            throw new \RuntimeException('Fichier .env absent. Copiez .env.example vers .env.');
        }

        $lignes = file($chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lignes === false) {
            throw new \RuntimeException('Impossible de lire le fichier .env.');
        }

        foreach ($lignes as $ligne) {
            $ligne = trim($ligne);
            if ($ligne === '' || str_starts_with($ligne, '#')) {
                continue;
            }

            [$nom, $valeur] = array_pad(explode('=', $ligne, 2), 2, '');
            $nom = trim($nom);
            $valeur = trim($valeur, "\\\"'");

            if ($nom === '' || getenv($nom) !== false) {
                continue;
            }

            $_ENV[$nom] = $valeur;
            putenv($nom . '=' . $valeur);
        }
    }

    public static function get(string $cle, ?string $defaut = null): ?string
    {
        $valeur = getenv($cle);
        if ($valeur !== false) {
            return $valeur;
        }

        return isset($_ENV[$cle]) ? (string) $_ENV[$cle] : $defaut;
    }
}
