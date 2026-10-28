<?php
namespace App\Helpers;

/**
 * CyberShield Localization Dictionary
 * Default language: English (en) with structural support for Somali (so)
 */
class LangHelper {
    private static string $currentLang = 'en';

    private static array $dictionary = [
        'en' => [
            'detect_educate_defend'   => 'Detect. Educate. Defend.',
            'dashboard'               => 'Dashboard',
            'campaigns'               => 'Campaigns',
            'participants'            => 'Participants',
            'event_monitor'           => 'Event Monitor',
            'academy'                 => 'Awareness Academy',
            'quizzes'                 => 'Quiz Center',
            'reports'                 => 'Reports & Analytics',
            'audit_logs'              => 'Audit Logs',
            'report_suspicious'       => 'Report Suspicious Message',
            'simulation_disclosure'   => 'Security Awareness Simulation Disclosure',
            'congratulations'         => 'Congratulations',
            'warning_signs'           => 'Recognizing the Warning Signs',
            'take_quiz'               => 'Take Knowledge Check Quiz',
            'safe_state'              => 'Protected / Verified',
            'urgent_threat'           => 'Urgent Threat Lure',
            'unrealistic_reward'      => 'Unrealistic Reward Lure',
        ],
        'so' => [
            'detect_educate_defend'   => 'Ogow. Baro. Difaac.',
            'dashboard'               => 'Barta Xogta',
            'campaigns'               => 'Ololayaasha Tababarka',
            'participants'            => 'Ardayda Ka Qaybgalaysa',
            'event_monitor'           => 'Dusha-kala-socodka Dhacdooyinka',
            'academy'                 => 'Akademiyada Wacyigelinta',
            'quizzes'                 => 'Xarunta Imtixaanaadka',
            'reports'                 => 'Warbixinnada & Falanqaynta',
            'audit_logs'              => 'Diiwaanka Hubinta',
            'report_suspicious'       => 'Gudbi Farriin Shaki Leh',
            'simulation_disclosure'   => 'Wacyigelin Ku Saabsan Dhoolatuska Amniga',
            'congratulations'         => 'Hambalyo',
            'warning_signs'           => 'Aqoonsashada Calaamadaha Digniinta',
            'take_quiz'               => 'Qaado Imtixaanka Xaqiijinta Aqoonta',
            'safe_state'              => 'La Difaacay / La Hubiyay',
            'urgent_threat'           => 'Khatar Degdeg Ah Oo Been Abuur Ah',
            'unrealistic_reward'      => 'Abaalmarin Been Ah Oo Aan Dhab Ahayn',
        ]
    ];

    public static function setLang(string $lang): void {
        if (isset(self::$dictionary[$lang])) {
            self::$currentLang = $lang;
        }
    }

    public static function getLang(): string {
        return self::$currentLang;
    }

    public static function trans(string $key, ?string $default = null): string {
        return self::$dictionary[self::$currentLang][$key]
            ?? self::$dictionary['en'][$key]
            ?? $default
            ?? $key;
    }
}

// Global translation shorthand
if (!function_exists('__')) {
    function __(string $key, ?string $default = null): string {
        return \App\Helpers\LangHelper::trans($key, $default);
    }
}
