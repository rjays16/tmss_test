<?php

namespace Database\Seeders;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MassiveTranslationSeeder extends Seeder
{
    public function run(): void
    {
        ini_set('memory_limit', '512M');
        
        $this->command->info('Starting massive translation seeding...');
        
        $locales = Locale::all();
        
        $totalTranslations = 100000;
        $localesCount = $locales->count();
        
        $batchSize = 500;
        $translationsPerKey = $localesCount;
        $totalKeys = (int) ceil($totalTranslations / $translationsPerKey);
        
        $prefixes = [
            'common', 'menu', 'auth', 'user', 'errors', 'validation',
            'button', 'form', 'message', 'title', 'label', 'placeholder',
            'footer', 'header', 'sidebar', 'modal', 'alert', 'success',
            'warning', 'info', 'home', 'about', 'contact', 'settings'
        ];
        
        $suffixes = [
            'title', 'text', 'message', 'label', 'button', 'link', 'hint',
            'error', 'success', 'warning', 'info', 'description', 'placeholder',
            'header', 'footer', 'content', 'caption', 'tooltip', 'value'
        ];
        
        $localeArray = $locales->toArray();
        
        $sampleValues = [
            'en' => ['Welcome', 'Hello', 'Goodbye', 'Submit', 'Cancel', 'Save', 'Delete', 'Edit', 'Search', 'Settings', 'Profile', 'Logout', 'Login', 'Register', 'Home', 'About', 'Contact', 'Success', 'Error', 'Warning'],
            'fr' => ['Bienvenue', 'Bonjour', 'Au revoir', 'Soumettre', 'Annuler', 'Enregistrer', 'Supprimer', 'Modifier', 'Rechercher', 'Paramètres', 'Profil', 'Déconnexion', 'Connexion', 'S\'inscrire', 'Accueil', 'À propos', 'Contact', 'Succès', 'Erreur', 'Avertissement'],
            'es' => ['Bienvenido', 'Hola', 'Adiós', 'Enviar', 'Cancelar', 'Guardar', 'Eliminar', 'Editar', 'Buscar', 'Configuración', 'Perfil', 'Cerrar sesión', 'Iniciar sesión', 'Registrarse', 'Inicio', 'Acerca de', 'Contacto', 'Éxito', 'Error', 'Advertencia'],
            'de' => ['Willkommen', 'Hallo', 'Auf Wiedersehen', 'Absenden', 'Abbrechen', 'Speichern', 'Löschen', 'Bearbeiten', 'Suchen', 'Einstellungen', 'Profil', 'Abmelden', 'Anmelden', 'Registrieren', 'Startseite', 'Über uns', 'Kontakt', 'Erfolg', 'Fehler', 'Warnung'],
            'it' => ['Benvenuto', 'Ciao', 'Arrivederci', 'Invia', 'Annulla', 'Salva', 'Elimina', 'Modifica', 'Cerca', 'Impostazioni', 'Profilo', 'Disconnetti', 'Accedi', 'Registrati', 'Home', 'Chi siamo', 'Contatto', 'Successo', 'Errore', 'Avviso'],
            'pt' => ['Bem-vindo', 'Olá', 'Adeus', 'Enviar', 'Cancelar', 'Salvar', 'Excluir', 'Editar', 'Pesquisar', 'Configurações', 'Perfil', 'Sair', 'Entrar', 'Registrar', 'Início', 'Sobre', 'Contato', 'Sucesso', 'Erro', 'Aviso'],
            'zh' => ['欢迎', '你好', '再见', '提交', '取消', '保存', '删除', '编辑', '搜索', '设置', '个人资料', '退出登录', '登录', '注册', '首页', '关于', '联系方式', '成功', '错误', '警告'],
            'ja' => ['ようこそ', 'こんにちは', 'さようなら', '送信', 'キャンセル', '保存', '削除', '編集', '検索', '設定', 'プロフィール', 'ログアウト', 'ログイン', '登録', 'ホーム', '概要', 'お問い合わせ', '成功', 'エラー', '警告'],
            'ko' => ['환영', '안녕', '녕', '제출', '취소', '저장', '삭제', '편집', '검색', '설정', '프로필', '로그아웃', '로그인', '등록', '홈', '정보', '연락처', '성공', '오류', '경고'],
            'ar' => ['مرحبا', 'أهلا', 'وداعا', 'إرسال', 'إلغاء', 'حفظ', 'حذف', 'تعديل', 'بحث', 'الإعدادات', 'الملف الشخصي', 'تسجيل الخروج', 'تسجيل الدخول', 'التسجيل', 'الرئيسية', 'حول', 'اتصل بنا', 'نجاح', 'خطأ', 'تحذير']
        ];
        
        $this->command->info("Creating {$totalTranslations} translations across {$localesCount} locales...");
        
        $chunks = ceil($totalKeys / $batchSize);
        
        for ($chunk = 0; $chunk < $chunks; $chunk++) {
            $translations = [];
            $keysInBatch = min($batchSize, $totalKeys - ($chunk * $batchSize));
            
            for ($i = 0; $i < $keysInBatch; $i++) {
                $prefix = $prefixes[array_rand($prefixes)];
                $suffix = $suffixes[array_rand($suffixes)];
                $keyIndex = ($chunk * $batchSize) + $i;
                // Use letters to keep keys cleaner: errors.warning.a, errors.warning.b, etc.
                $letter = chr(97 + ($keyIndex % 26));
                $key = $keyIndex < 26 
                    ? "{$prefix}.{$suffix}.{$letter}" 
                    : "{$prefix}.{$suffix}.{$letter}" . floor($keyIndex / 26);
                
                foreach ($localeArray as $locale) {
                    $code = $locale['code'];
                    $values = $sampleValues[$code] ?? $sampleValues['en'];
                    $value = $values[array_rand($values)];
                    
                    $translations[] = [
                        'key' => $key,
                        'value' => $value,
                        'locale' => $code,
                        'locale_id' => $locale['id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            DB::table('translations')->insert($translations);
            unset($translations);
            
            $progress = round(($chunk + 1) / $chunks * 100);
            $this->command->info("Progress: {$progress}%");
        }
        
        $this->command->info('Seeding complete!');
        $this->command->info('Created ' . Translation::count() . ' translation records.');
        
        $this->command->info('Assigning tags to translations...');
        
        $tags = Tag::all();
        $tagIds = $tags->pluck('id')->toArray();
        
        $translationCount = Translation::count();
        $tagBatchSize = 1000;
        
        for ($i = 1; $i <= $translationCount; $i += $tagBatchSize) {
            $limit = min($tagBatchSize, $translationCount - $i + 1);
            $translationIds = range($i, $i + $limit - 1);
            
            $pivotData = [];
            foreach ($translationIds as $transId) {
                $numTags = rand(1, 3);
                $selectedTags = array_rand(array_flip($tagIds), $numTags);
                if (!is_array($selectedTags)) {
                    $selectedTags = [$selectedTags];
                }
                foreach ($selectedTags as $tagId) {
                    $pivotData[] = [
                        'translation_id' => $transId,
                        'tag_id' => $tagId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            DB::table('translation_tag')->insert($pivotData);
            unset($pivotData);
        }
        
        $this->command->info('Tags assigned successfully!');
    }
}
