<?php

//Muss ggf. an attributes Array in der validation.php angefügt werden
return [

    /*
    |--------------------------------------------------------------------------
    | Database Attribute Language Lines
    |--------------------------------------------------------------------------
    */

    // personal_access_tokens
    'tokenable' => 'tokenfähig',
    'token' => 'Token',
    'abilities' => 'Fähigkeiten',

    //failed_jobs
    'connection' => 'Connection',
    'exception' => 'Fehler',
    'failed_at' => 'Fehler aufgetreten am',

    // sessions
    'ip_address' => 'IP-Adresse',
    'last_activity' => 'zuletzt Aktiv am',


    // users
    'email_verified_at' => 'E-Mail verifiziert am',
    'locale' => 'Sprache',
    'two_factor_recovery_codes' => '2FA Wiederherstellungscodes',
    'two_factor_secret' => '2FA Geheimnis',
    'last_login_at' => 'Zuletzt eingeloggt am',
    'last_login_ip' => 'Zuletzt eingeloggt mit IP-Adresse',
    'anydesk_access_token_test' => 'Teamviewer Zugangstoken Test',
    'remember_token' => 'Token speichern',
    'is_allow_api' => 'API-Zugriff erlaubt',
    'password_update_remind_at' => 'Hinweis Passwort Aktualisierung am',
    'password_updated_at' => 'Passwort aktualisiert am',
    'profile_photo_path' => 'Dateipfad zum Foto',

    //shared_users
    'name' => 'Name',
    'email' => 'E-Mail',

    //permissions, roles
    'guard_name' => 'Sicherheitsmethode',
    'permissions' => 'Berechtigungen',

    //tariffs
    'tariff_name' => 'Tarif',
    'price' => 'Preis',
    'selected_days' => 'Tage',
    'interval' => 'Intervall',
    'initial_time' => 'Anfangszeit',
    'start_time' => 'Startzeit',
    'end_time' => 'Endzeit',
    'start_period' => 'Startzeitraum',
    'end_period' => 'Endzeitraum',
    'archieved_date_time' => 'Archiviert am',
    'tariff_state' => 'Status',
    'global' => 'Global',
    'permanent' => 'Permanent',
    'color' => 'Farbe',
    'overlap_status' => 'Überlappungs-Status',


    //notifications
    'notifiable' => 'meldepflichtig',
    'type' => 'Typ',
    'active' => 'Aktoiv',

    //customers / Companies
    //'name' => 'Name',
    'subdomain' => 'Subdomain',
    'logo' => 'Logo',
    //'email' => 'Email',
    'address' => 'Adresse',
    'payment' => 'Zahlung',
    'iban' => 'IBAN',
    'bic' => 'BIC',

    'password' => 'Passwort',
    'password_confirmation' => 'Passwort-Bestätigung',
    'current_password' => 'Aktuelles Passwort',
    'confirmablePassword' => 'Bestätigbares Passwort',

    'customer_name' => 'Kundenname',
    'phone' => 'Telefon',
    'post_code' => 'Postleitzahl',
    'zip' => 'Postleitzahl',
    'city' => 'Ort',
    'country' => 'Land',
    'website' => 'Webseite',
    'tax_number' => 'USt.-Nr',
    'notes' => 'Notizen',
    'contact' => 'Kontakt',
    'contact_email' => 'Kontaktemail',

    'billing_addition' => 'Addition',
    'billing_address' => 'Adresse',
    'billing_zip_code' => 'Postleitzahl',
    'billing_city' => 'Ort',
    'billing_country' => 'Land',
    'billing_iban' => 'IBAN',
    'billing_bic' => 'BIC',
    'billing_email' => 'E-Mail',
    'billing_payment' => 'Zahlungsfrist',
    'billing_sepa' => 'SEPA',
    'selectedTariff' => 'Tarif',

    'curr_month_actual_operate_time' => 'Aufwand aktueller Monat',
    'last_month_actual_operate_time' => 'Aufwand letzter Monat',
    'last_quarter_actual_operate_time' => 'Aufwand letztes Quartal',

    'anydesk_access_token' => 'Teamviewer Token',
    'anydesk_refresh_token' => 'Teamviewer Aktualisierungstoken',
    'anydesk_access_token_for_expire_check' => 'Teamviewer Token prüfen am',

    //connection_reports
    'start_date' => 'Start Datum',
    'end_date' => 'Ende Datum',
    'currency' => 'Währung',
    'billing_state' => 'Status Abrechnung',
    'notes' => 'Notizen',
    'overlaps_color' => 'Hinweisfarbe Überlappung',
    'activity_report' => 'Tätigkeitsbeschreibung',
    'booked' => 'Gebucht',
    //'price' => 'Preis',
    'isTV' => 'Teamviewer-Eintrag',
    'contact_type' => 'Kontaktart',
    'planned_operating_time' => 'Geplante Einsatzzeit',
    'is_tariff_overlap_confirmed' => 'Überlappung bestätigt',
    'overlaps_tariff' => 'Tarifüberlappung',
    'printed' => 'gedruckt',

    //devices
    'alias' => 'Alias',
    'description' => 'Beschreibung',
    'online_state' => 'Online-Status',

    //contacts
    'salutation' => 'Anrede',
    'firstname' => 'Vorname',
    'lastname' => 'Nachname',
    's_email' => 'Nachname',
    'p_email' => 'Nachname',
    'b_number' => 'Dienstnummer',
    'm_number' => 'Mobilnummer',
    'h_number' => 'Privatnummer',
    'c_department' => 'Abteilung',
    'c_function' => 'Funktion',

    //files
    'original_name' => 'Ursprünglicher Dateiname',
    'path_to_file' => 'Dateipfad',

    //todo
    'todo' => 'Todo',
    'tag' => 'Stichwörter',
    'is_completed' => 'Abgeschlossen',
    'is_important' => 'Wichtig',
    'sort_order' => 'Reihenfolge',
    'type' => 'Typ',
    'last_poll_date' => 'Datum letzte Abfrage',

    //livetracks (Stoppuhr)
    //'start_date' => 'Start Zeit',
    //'end_date' => 'Ende Zeit',

    //subdomains
    //'subdomain' => 'Subdomain',
    //'description' => 'Beschreibung',
    'target' => 'Ziel-URL',
];


