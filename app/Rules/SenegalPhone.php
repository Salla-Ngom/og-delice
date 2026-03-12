<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valide un numéro mobile sénégalais :
 * - 9 chiffres exactement
 * - Commence par 70, 71, 72, 76, 77 ou 78
 * Accepte avec ou sans indicatif +221 (nettoyé automatiquement)
 */
class SenegalPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Nettoyer : supprimer +221, 00221, espaces, tirets
        $cleaned = preg_replace('/[\s\-]/', '', $value);
        $cleaned = preg_replace('/^(\+221|00221)/', '', $cleaned);

        if (!preg_match('/^(70|71|72|76|77|78)\d{7}$/', $cleaned)) {
            $fail('Le numéro de téléphone doit être un mobile sénégalais valide (ex: 77 123 45 67).');
        }
    }

    /**
     * Nettoie le numéro pour le stocker en base (9 chiffres bruts)
     */
    public static function normalize(string $phone): string
    {
        $cleaned = preg_replace('/[\s\-]/', '', $phone);
        return preg_replace('/^(\+221|00221)/', '', $cleaned);
    }
}