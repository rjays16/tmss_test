import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import App from './App.vue'

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en: {
      translations: 'Translations',
      locales: 'Locales',
      tags: 'Tags',
      export: 'Export',
      search: 'Search translations...',
      addTranslation: 'Add Translation',
      addLocale: 'Add Locale',
      addTag: 'Add Tag',
      key: 'Key',
      actions: 'Actions',
      edit: 'Edit',
      delete: 'Delete',
      allTags: 'All Tags',
      exportTranslations: 'Export Translations',
      exportDescription: 'Download your translations in JSON format for frontend applications',
      selectLocales: 'Select Locales',
      filterByTag: 'Filter by Tag',
      exportJson: 'Export JSON',
      active: 'Active',
      inactive: 'Inactive',
    },
    fr: {
      translations: 'Traductions',
      locales: 'Langues',
      tags: 'Étiquettes',
      export: 'Exporter',
      search: 'Rechercher des traductions...',
      addTranslation: 'Ajouter une traduction',
      addLocale: 'Ajouter une langue',
      addTag: 'Ajouter une étiquette',
      key: 'Clé',
      actions: 'Actions',
      edit: 'Modifier',
      delete: 'Supprimer',
      allTags: 'Toutes les étiquettes',
      exportTranslations: 'Exporter les traductions',
      exportDescription: 'Téléchargez vos traductions au format JSON pour les applications frontend',
      selectLocales: 'Sélectionner les langues',
      filterByTag: 'Filtrer par étiquette',
      exportJson: 'Exporter JSON',
      active: 'Actif',
      inactive: 'Inactif',
    },
    es: {
      translations: 'Traducciones',
      locales: 'Idiomas',
      tags: 'Etiquetas',
      export: 'Exportar',
      search: 'Buscar traducciones...',
      addTranslation: 'Añadir traducción',
      addLocale: 'Añadir idioma',
      addTag: 'Añadir etiqueta',
      key: 'Clave',
      actions: 'Acciones',
      edit: 'Editar',
      delete: 'Eliminar',
      allTags: 'Todas las etiquetas',
      exportTranslations: 'Exportar traducciones',
      exportDescription: 'Descargue sus traducciones en formato JSON para aplicaciones frontend',
      selectLocales: 'Seleccionar idiomas',
      filterByTag: 'Filtrar por etiqueta',
      exportJson: 'Exportar JSON',
      active: 'Activo',
      inactive: 'Inactivo',
    }
  }
})

const app = createApp(App)
app.use(i18n)
app.mount('#app')
