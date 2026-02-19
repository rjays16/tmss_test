<?php

namespace Database\Seeders;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $locales = Locale::all();
        $tags = Tag::all();

        $translations = [
            'common.welcome' => [
                'en' => 'Welcome',
                'fr' => 'Bienvenue',
                'es' => 'Bienvenido',
                'de' => 'Willkommen',
            ],
            'common.goodbye' => [
                'en' => 'Goodbye',
                'fr' => 'Au revoir',
                'es' => 'Adiós',
                'de' => 'Auf Wiedersehen',
            ],
            'common.submit' => [
                'en' => 'Submit',
                'fr' => 'Soumettre',
                'es' => 'Enviar',
                'de' => 'Absenden',
            ],
            'common.cancel' => [
                'en' => 'Cancel',
                'fr' => 'Annuler',
                'es' => 'Cancelar',
                'de' => 'Abbrechen',
            ],
            'common.save' => [
                'en' => 'Save',
                'fr' => 'Enregistrer',
                'es' => 'Guardar',
                'de' => 'Speichern',
            ],
            'common.delete' => [
                'en' => 'Delete',
                'fr' => 'Supprimer',
                'es' => 'Eliminar',
                'de' => 'Löschen',
            ],
            'common.edit' => [
                'en' => 'Edit',
                'fr' => 'Modifier',
                'es' => 'Editar',
                'de' => 'Bearbeiten',
            ],
            'common.search' => [
                'en' => 'Search',
                'fr' => 'Rechercher',
                'es' => 'Buscar',
                'de' => 'Suchen',
            ],
            'menu.home' => [
                'en' => 'Home',
                'fr' => 'Accueil',
                'es' => 'Inicio',
                'de' => 'Startseite',
            ],
            'menu.about' => [
                'en' => 'About',
                'fr' => 'À propos',
                'es' => 'Acerca de',
                'de' => 'Über uns',
            ],
            'menu.contact' => [
                'en' => 'Contact',
                'fr' => 'Contact',
                'es' => 'Contacto',
                'de' => 'Kontakt',
            ],
            'menu.settings' => [
                'en' => 'Settings',
                'fr' => 'Paramètres',
                'es' => 'Configuración',
                'de' => 'Einstellungen',
            ],
            'errors.404' => [
                'en' => 'Page not found',
                'fr' => 'Page non trouvée',
                'es' => 'Página no encontrada',
                'de' => 'Seite nicht gefunden',
            ],
            'errors.500' => [
                'en' => 'Server error',
                'fr' => 'Erreur serveur',
                'es' => 'Error del servidor',
                'de' => 'Serverfehler',
            ],
            'auth.login' => [
                'en' => 'Login',
                'fr' => 'Connexion',
                'es' => 'Iniciar sesión',
                'de' => 'Anmelden',
            ],
            'auth.logout' => [
                'en' => 'Logout',
                'fr' => 'Déconnexion',
                'es' => 'Cerrar sesión',
                'de' => 'Abmelden',
            ],
            'auth.register' => [
                'en' => 'Register',
                'fr' => 'S\'inscrire',
                'es' => 'Registrarse',
                'de' => 'Registrieren',
            ],
            'auth.password_reset' => [
                'en' => 'Reset Password',
                'fr' => 'Réinitialiser le mot de passe',
                'es' => 'Restablecer contraseña',
                'de' => 'Passwort zurücksetzen',
            ],
            'user.profile' => [
                'en' => 'Profile',
                'fr' => 'Profil',
                'es' => 'Perfil',
                'de' => 'Profil',
            ],
            'user.name' => [
                'en' => 'Name',
                'fr' => 'Nom',
                'es' => 'Nombre',
                'de' => 'Name',
            ],
            'user.email' => [
                'en' => 'Email',
                'fr' => 'E-mail',
                'es' => 'Correo electrónico',
                'de' => 'E-Mail',
            ],
        ];

        foreach ($translations as $key => $values) {
            foreach ($values as $localeCode => $value) {
                $locale = $locales->firstWhere('code', $localeCode);
                if (!$locale) continue;

                $translation = Translation::create([
                    'key' => $key,
                    'value' => $value,
                    'locale_id' => $locale->id,
                    'locale' => $localeCode,
                ]);

                if ($tags->isNotEmpty()) {
                    $randomTags = $tags->random(min(rand(1, 3), $tags->count()));
                    $translation->tags()->attach($randomTags->pluck('id')->toArray());
                }
            }
        }
    }
}
