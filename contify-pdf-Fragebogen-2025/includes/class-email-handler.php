<?php

if (!defined('ABSPATH')) {
    exit;
}

class CON_FRA_2025_Email_Handler {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_filter('wp_mail_content_type', array($this, 'set_html_mail_content_type'));
    }
    
    public function set_html_mail_content_type() {
        return 'text/html';
    }
    
    public function send_notification_email($form_data, $submission_id) {
        $options = get_option('con_fra_2025_settings', array());
        $to_email = isset($options['notification_email']) ? $options['notification_email'] : get_option('admin_email');
        
        $subject = sprintf(__('Neue Fragebogen Einreichung #%d - %s', 'contify-pdf-Fragebogen-2025'), 
                          $submission_id, 
                          get_bloginfo('name'));
        
        $message = $this->build_email_template($form_data, $submission_id);
        
        $headers = array(
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        );
        
        if (isset($options['cc_email']) && !empty($options['cc_email'])) {
            $headers[] = 'Cc: ' . $options['cc_email'];
        }
        
        if (isset($options['bcc_email']) && !empty($options['bcc_email'])) {
            $headers[] = 'Bcc: ' . $options['bcc_email'];
        }
        
        return wp_mail($to_email, $subject, $message, $headers);
    }
    
    private function build_email_template($form_data, $submission_id) {
        $template = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Contify Fragebogen 2025</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background-color: #f8f9fa; padding: 20px; border-bottom: 3px solid #007cba; }
                .content { padding: 20px; }
                .section { margin-bottom: 30px; }
                .section h3 { color: #007cba; border-bottom: 2px solid #eee; padding-bottom: 10px; }
                .field { margin-bottom: 15px; }
                .field-label { font-weight: bold; color: #555; }
                .field-value { margin-left: 20px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #007cba; }
                .footer { background-color: #f8f9fa; padding: 15px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>Contify Fragebogen 2025 - Neue Einreichung</h2>
                <p><strong>Einreichung ID:</strong> #' . $submission_id . '</p>
                <p><strong>Datum:</strong> ' . current_time('d.m.Y H:i') . '</p>
            </div>
            
            <div class="content">
                ' . $this->build_section_1($form_data) . '
                ' . $this->build_section_2($form_data) . '
                ' . $this->build_section_3($form_data) . '
                ' . $this->build_section_4($form_data) . '
                ' . $this->build_section_5($form_data) . '
            </div>
            
            <div class="footer">
                <p>&copy; 2025 Contify - Die Textagentur | ' . get_bloginfo('name') . '</p>
            </div>
        </body>
        </html>';
        
        return $template;
    }
    
    private function build_section_1($data) {
        return '
        <div class="section">
            <h3>1. Allgemeine Informationen</h3>
            ' . $this->build_field('Anzahl und Art der Texte', $data['text_count'] ?? '') . '
            ' . $this->build_field('Gesamtzahl Wörter', $data['word_count'] ?? '') . '
            ' . $this->build_field('Zielgruppe', $data['target_group'] ?? '') . '
            ' . $this->build_field('Userintention', $data['user_intention'] ?? '') . '
            ' . $this->build_field('Ziel der Texte', $data['text_goals'] ?? '') . '
            ' . $this->build_field('Werte/Normen', $data['values_norms'] ?? '') . '
            ' . $this->build_field('USPs', $data['usps'] ?? '') . '
            ' . $this->build_field('Ausrichtung der Texte', $data['text_orientation'] ?? '') . '
            ' . $this->build_field('Kooperationen', $data['cooperations'] ?? '') . '
        </div>';
    }
    
    private function build_section_2($data) {
        return '
        <div class="section">
            <h3>2. Textspezifische Informationen</h3>
            ' . $this->build_checkbox_field('W-Fragen', $data['w_questions'] ?? 0) . '
            ' . $this->build_checkbox_field('Google-Suggest-Anfragen', $data['google_suggest'] ?? 0) . '
            ' . $this->build_checkbox_field('WDF*IDF-Analyse', $data['wdf_idf_analysis'] ?? 0) . '
            ' . $this->build_checkbox_field('Sprachliche/rechtliche No-Gos', $data['language_nogo'] ?? 0) . '
            ' . $this->build_field('No-Gos Details', $data['language_nogo_details'] ?? '') . '
            ' . $this->build_checkbox_field('Wording Einschränkungen', $data['wording_restrictions'] ?? 0) . '
            ' . $this->build_field('Wording Details', $data['wording_details'] ?? '') . '
            ' . $this->build_cta_fields($data) . '
            ' . $this->build_address_fields($data) . '
            ' . $this->build_intonation_fields($data) . '
            ' . $this->build_field('Sprachstil', $data['language_style'] ?? '') . '
            ' . $this->build_checkbox_field('Abkürzungen ausschreiben', $data['abbreviations_expand'] ?? 0) . '
            ' . $this->build_checkbox_field('Maßeinheiten ausschreiben', $data['units_expand'] ?? 0) . '
        </div>';
    }
    
    private function build_section_3($data) {
        return '
        <div class="section">
            <h3>3. Zusätzliche Informationen</h3>
            ' . $this->build_keyword_fields($data) . '
            ' . $this->build_checkbox_field('Bildmotivvorschläge', $data['image_suggestions'] ?? 0) . '
            ' . $this->build_checkbox_field('Meta-Infos', $data['meta_info'] ?? 0) . '
        </div>';
    }
    
    private function build_section_4($data) {
        return '
        <div class="section">
            <h3>4. CMS Informationen</h3>
            ' . $this->build_checkbox_field('Tabellen', $data['tables_support'] ?? 0) . '
            ' . $this->build_checkbox_field('Zitate/Blockquotes', $data['quotes_support'] ?? 0) . '
            ' . $this->build_checkbox_field('Aufzählungen', $data['lists_support'] ?? 0) . '
            ' . $this->build_checkbox_field('Überschrift Zeichenlimit', $data['heading_limit'] ?? 0) . '
            ' . $this->build_field('Max. Zeichenanzahl Überschrift', $data['heading_max_chars'] ?? '') . '
            ' . $this->build_checkbox_field('Absatzlänge Vorgaben', $data['paragraph_limit'] ?? 0) . '
            ' . $this->build_field('Absatz Anforderungen', $data['paragraph_requirements'] ?? '') . '
            ' . $this->build_delivery_fields($data) . '
        </div>';
    }
    
    private function build_section_5($data) {
        return '
        <div class="section">
            <h3>5. Anmerkungen</h3>
            ' . $this->build_field('Zusätzliche Informationen', $data['additional_notes'] ?? '') . '
        </div>';
    }
    
    private function build_field($label, $value) {
        if (empty($value)) return '';
        
        return '
        <div class="field">
            <div class="field-label">' . esc_html($label) . ':</div>
            <div class="field-value">' . nl2br(esc_html($value)) . '</div>
        </div>';
    }
    
    private function build_checkbox_field($label, $value) {
        $checked = $value ? 'Ja' : 'Nein';
        return $this->build_field($label, $checked);
    }
    
    private function build_cta_fields($data) {
        $cta_options = array();
        if ($data['cta_in_text'] ?? 0) $cta_options[] = 'im Text';
        if ($data['cta_separated'] ?? 0) $cta_options[] = 'abgesetzt vom Text';
        if ($data['cta_button'] ?? 0) $cta_options[] = 'als Buttontext';
        
        return $this->build_field('Call-To-Action', empty($cta_options) ? 'Nein' : implode(', ', $cta_options));
    }
    
    private function build_address_fields($data) {
        $address_options = array();
        if ($data['address_du'] ?? 0) $address_options[] = 'Du';
        if ($data['address_sie'] ?? 0) $address_options[] = 'Sie';
        if ($data['address_neutral'] ?? 0) $address_options[] = 'neutral';
        
        return $this->build_field('Ansprache', empty($address_options) ? '-' : implode(', ', $address_options));
    }
    
    private function build_intonation_fields($data) {
        $intonation_options = array();
        if ($data['intonation_wir'] ?? 0) $intonation_options[] = 'Wir bieten...';
        if ($data['intonation_ich'] ?? 0) $intonation_options[] = 'Ich biete...';
        if ($data['intonation_firma_de'] ?? 0) $intonation_options[] = 'Unsere-Firma.de bietet';
        if ($data['intonation_firma'] ?? 0) $intonation_options[] = 'Unsere Firma bietet';
        if ($data['intonation_neutral'] ?? 0) $intonation_options[] = 'neutral';
        
        return $this->build_field('Intonation', empty($intonation_options) ? '-' : implode(', ', $intonation_options));
    }
    
    private function build_keyword_fields($data) {
        $keyword_options = array();
        if ($data['keywords_provided'] ?? 0) $keyword_options[] = 'bereits vorhanden';
        if ($data['keywords_research'] ?? 0) $keyword_options[] = 'KW-Research erwünscht';
        
        return $this->build_field('Keywords', empty($keyword_options) ? '-' : implode(', ', $keyword_options));
    }
    
    private function build_delivery_fields($data) {
        $delivery_options = array();
        if ($data['delivery_word'] ?? 0) $delivery_options[] = 'Word-Dokument';
        if ($data['delivery_html'] ?? 0) $delivery_options[] = 'HTML-codiert';
        if ($data['delivery_special'] ?? 0) $delivery_options[] = 'Sonderformat';
        
        return $this->build_field('Textanlieferung', empty($delivery_options) ? '-' : implode(', ', $delivery_options));
    }
}