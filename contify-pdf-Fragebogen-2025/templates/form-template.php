<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="con-fra-2025-container">
    <div class="con-fra-form-header">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <p><?php echo esc_html($atts['description']); ?></p>
    </div>

    <form id="conFra2025Form" class="con-fra-form" method="post" action="" novalidate>
        <?php wp_nonce_field('con_fra_2025_form', 'con_fra_2025_nonce'); ?>
        <input type="hidden" name="con_fra_2025_submit" value="1">

        <!-- Step Indicators -->
        <div class="step-indicator-container">
            <div class="step-indicator active" data-step="1">1</div>
            <div class="step-indicator-line"></div>
            <div class="step-indicator" data-step="2">2</div>
            <div class="step-indicator-line"></div>
            <div class="step-indicator" data-step="3">3</div>
            <div class="step-indicator-line"></div>
            <div class="step-indicator" data-step="4">4</div>
            <div class="step-indicator-line"></div>
            <div class="step-indicator" data-step="5">5</div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <span class="progress-text" id="progressText"><?php _e('Schritt 1 von 5', 'contify-pdf-Fragebogen-2025'); ?></span>

        <!-- Step 1: Allgemeine Informationen -->
        <div class="form-section active" data-section="1">
            <div class="section-header">
                <h3><span class="section-number">1.</span> <?php _e('Allgemeine Informationen', 'contify-pdf-Fragebogen-2025'); ?></h3>
            </div>
            <div class="section-content">
                <div class="form-group">
                    <label for="text_count" class="required"><?php _e('Anzahl und Art der zu verfassenden Texte', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="text_count" id="text_count" rows="3" placeholder="<?php _e('z.B. 5 Kategorietexte, 4 Produkttexte, 3 Blogtexte, 2 Newsletter', 'contify-pdf-Fragebogen-2025'); ?>" required></textarea>
                </div>
                <div class="form-group">
                    <label for="word_count" class="required"><?php _e('Gesamtzahl der zu schreibenden Wörter', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <input type="text" name="word_count" id="word_count" placeholder="<?php _e('ca. 5000 Wörter', 'contify-pdf-Fragebogen-2025'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="target_group" class="required"><?php _e('Definition der Zielgruppe', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="target_group" id="target_group" rows="3" placeholder="<?php _e('Geschlecht, Alter, Einkommen, etc.', 'contify-pdf-Fragebogen-2025'); ?>" required></textarea>
                </div>
                <div class="form-group">
                    <label for="user_intention"><?php _e('Userintention', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="user_intention" id="user_intention" rows="3" placeholder="<?php _e('Informationsgewinn, Kaufabsicht, Vergleich, günstig vs. gut...', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                </div>
                <div class="form-group">
                    <label for="text_goals"><?php _e('Welches Ziel verfolgen die Texte?', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="text_goals" id="text_goals" rows="3" placeholder="<?php _e('Conversion, Traffic-Generierung, Service-Themen...', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                </div>
                <div class="form-group">
                    <label for="values_norms"><?php _e('Werte / Normen, die vermittelt werden sollen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="values_norms" id="values_norms" rows="3" placeholder="<?php _e('hochwertig, Schnäppchen, Kundenzufriedenheit, Ehrlichkeit, Transparenz...', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                </div>
                <div class="form-group">
                    <label for="usps"><?php _e('USPs der Produkte / Services', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="usps" id="usps" rows="3" placeholder="<?php _e('Auswahl / Preis / schneller Versand / etc.', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                </div>
                <div class="form-group">
                    <label for="text_orientation"><?php _e('Ausrichtung der Texte', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="text_orientation" id="text_orientation" rows="3" placeholder="<?php _e('eher werblich / ratgebend / informativ / emotional oder faktenbezogen / allgemein gehalten', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                </div>
                <div class="form-group">
                    <label for="cooperations"><?php _e('Kooperationen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="cooperations" id="cooperations" rows="3" placeholder="<?php _e('Firmen / „Prominente" / Institute / Zeitschriften / Newsportale / Fernsehsender etc.', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                </div>
            </div>
            <div class="form-navigation">
                <button type="button" class="button-next"><?php _e('Weiter', 'contify-pdf-Fragebogen-2025'); ?></button>
            </div>
        </div>

        <!-- Step 2: Textspezifische Informationen -->
        <div class="form-section" data-section="2">
            <div class="section-header">
                <h3><span class="section-number">2.</span> <?php _e('Textspezifische Informationen', 'contify-pdf-Fragebogen-2025'); ?></h3>
            </div>
            <div class="section-content">
                <div class="form-group checkbox-group">
                    <label><?php _e('W-Fragen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="w_questions" value="1"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <span class="recommendation"><?php _e('Empfehlung: ja', 'contify-pdf-Fragebogen-2025'); ?></span>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Google-Suggest-Anfragen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="google_suggest" value="1"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <span class="recommendation"><?php _e('Empfehlung: ja', 'contify-pdf-Fragebogen-2025'); ?></span>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('WDF*IDF-Analyse', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="wdf_idf_analysis" value="1"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <p class="field-description"><?php _e('Eine WDF*IDF-Analyse wird für jeden Text erstellt, diese gilt jedoch als Inspiration, Recherchegrundlage sowie zur Strukturierung der Texte.', 'contify-pdf-Fragebogen-2025'); ?></p>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Sprachliche / rechtliche No-Gos', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="language_nogo" value="1" onchange="toggleTextarea('language_nogo_details', this.checked)"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <div class="conditional-field" id="language_nogo_details_wrapper" style="display:none;">
                        <label for="language_nogo_details"><?php _e('Welche:', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <textarea name="language_nogo_details" id="language_nogo_details" rows="2"></textarea>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Wording (Whitelist / Blacklist)', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="wording_restrictions" value="1" onchange="toggleTextarea('wording_details', this.checked)"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <p class="field-description"><?php _e('Begriffe, die Sie per se ausschließen oder die durch Patente etc. eine besondere Schreibweise erfordern.', 'contify-pdf-Fragebogen-2025'); ?></p>
                    <div class="conditional-field" id="wording_details_wrapper" style="display:none;">
                        <label for="wording_details"><?php _e('Welche:', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <textarea name="wording_details" id="wording_details" rows="2"></textarea>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Call-To-Action-Aussagen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="cta_in_text" value="1"> <?php _e('im Text', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="checkbox" name="cta_separated" value="1"> <?php _e('abgesetzt vom Text', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="checkbox" name="cta_button" value="1"> <?php _e('als Buttontext', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group radio-group">
                    <label class="required"><?php _e('Ansprache', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="radio-options">
                        <label><input type="radio" name="address_type" value="du" required> <?php _e('Du', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="address_type" value="sie"> <?php _e('Sie', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="address_type" value="neutral"> <?php _e('neutral', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group radio-group">
                    <label class="required"><?php _e('Intonation', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="radio-options">
                        <label><input type="radio" name="intonation_type" value="wir" required> <?php _e('"Wir bieten…"', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="intonation_type" value="ich"> <?php _e('"Ich biete..."', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="intonation_type" value="firma_de"> <?php _e('"Unsere-Firma.de bietet"', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="intonation_type" value="firma"> <?php _e('"Unsere Firma bietet"', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="intonation_type" value="neutral"> <?php _e('neutral', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language_style"><?php _e('Sprachstil', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="language_style" id="language_style" rows="3" placeholder="<?php _e('Hochsprachlich, jung, auf Augenhöhe, wissenschaftlich, Soziolekte...', 'contify-pdf-Fragebogen-2025'); ?>"></textarea>
                    <p class="field-description"><?php _e('In Abhängigkeit zu Zielgruppe und Userintention', 'contify-pdf-Fragebogen-2025'); ?></p>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Abkürzungen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="abbreviations_expand" value="1"> <?php _e('Sollen ausgeschrieben werden', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <p class="field-description"><?php _e('z.B. vs. zum Beispiel; i. d. R. vs. in der Regel…', 'contify-pdf-Fragebogen-2025'); ?></p>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Maßeinheiten', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="units_expand" value="1"> <?php _e('Sollen ausgeschrieben werden', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <p class="field-description"><?php _e('Zeitangaben, Währung, Entfernungen', 'contify-pdf-Fragebogen-2025'); ?></p>
                </div>
            </div>
            <div class="form-navigation">
                <button type="button" class="button-prev"><?php _e('Zurück', 'contify-pdf-Fragebogen-2025'); ?></button>
                <button type="button" class="button-next"><?php _e('Weiter', 'contify-pdf-Fragebogen-2025'); ?></button>
            </div>
        </div>

        <!-- Step 3: Zusätzliche Informationen -->
        <div class="form-section" data-section="3">
            <div class="section-header">
                <h3><span class="section-number">3.</span> <?php _e('Zusätzliche Informationen', 'contify-pdf-Fragebogen-2025'); ?></h3>
            </div>
            <div class="section-content">
                <div class="form-group radio-group">
                    <label class="required"><?php _e('Keywords', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="radio-options">
                        <label><input type="radio" name="keywords_type" value="provided" required> <?php _e('Bereits vorhanden', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="radio" name="keywords_type" value="research"> <?php _e('KW-Research erwünscht', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Bildmotivvorschläge', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="image_suggestions" value="1"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Meta-Infos', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="meta_info" value="1"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <p class="field-description"><?php _e('Dürfen wir Meta-Infos (Title und Description) mit anliefern?', 'contify-pdf-Fragebogen-2025'); ?></p>
                </div>
            </div>
            <div class="form-navigation">
                <button type="button" class="button-prev"><?php _e('Zurück', 'contify-pdf-Fragebogen-2025'); ?></button>
                <button type="button" class="button-next"><?php _e('Weiter', 'contify-pdf-Fragebogen-2025'); ?></button>
            </div>
        </div>

        <!-- Step 4: Informationen bzgl. CMS -->
        <div class="form-section" data-section="4">
            <div class="section-header">
                <h3><span class="section-number">4.</span> <?php _e('Informationen bzgl. CMS', 'contify-pdf-Fragebogen-2025'); ?></h3>
            </div>
            <div class="section-content">
                <div class="form-group checkbox-group">
                    <label><?php _e('Tabellen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="tables_support" value="1"> <?php _e('Können abgebildet werden', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Zitate / Blockquotes', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="quotes_support" value="1"> <?php _e('Können abgebildet werden', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Aufzählungen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="lists_support" value="1"> <?php _e('Können abgebildet werden (auch numerisch oder als Haken)', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Maximale Zeichenanzahl der Überschrift', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="heading_limit" value="1" onchange="toggleInput('heading_max_chars', this.checked)"> <?php _e('Ja', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <div class="conditional-field" id="heading_max_chars_wrapper" style="display:none;">
                        <input type="number" name="heading_max_chars" id="heading_max_chars" placeholder="<?php _e('Anzahl Zeichen', 'contify-pdf-Fragebogen-2025'); ?>">
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Absatzlänge', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="paragraph_limit" value="1" onchange="toggleTextarea('paragraph_requirements', this.checked)"> <?php _e('Gibt es Vorgaben', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                    <p class="field-description"><?php _e('z.B. Absatz je zwischen 5 und 7 Zeilen → so schaffen wir ein einheitliches Bild', 'contify-pdf-Fragebogen-2025'); ?></p>
                    <div class="conditional-field" id="paragraph_requirements_wrapper" style="display:none;">
                        <label for="paragraph_requirements"><?php _e('Welche:', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <textarea name="paragraph_requirements" id="paragraph_requirements" rows="2"></textarea>
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label><?php _e('Textanlieferung', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <div class="checkbox-options">
                        <label><input type="checkbox" name="delivery_word" value="1"> <?php _e('Als Word-Dokument, mit entsprechend formatierten Überschriften', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="checkbox" name="delivery_html" value="1"> <?php _e('Als HTML-codiertes UTF8-Dokument', 'contify-pdf-Fragebogen-2025'); ?></label>
                        <label><input type="checkbox" name="delivery_special" value="1"> <?php _e('Existiert ein Sonderformat an Auszeichnungen?', 'contify-pdf-Fragebogen-2025'); ?></label>
                    </div>
                </div>
            </div>
            <div class="form-navigation">
                <button type="button" class="button-prev"><?php _e('Zurück', 'contify-pdf-Fragebogen-2025'); ?></button>
                <button type="button" class="button-next"><?php _e('Weiter', 'contify-pdf-Fragebogen-2025'); ?></button>
            </div>
        </div>

        <!-- Step 5: Anmerkungen -->
        <div class="form-section" data-section="5">
            <div class="section-header">
                <h3><span class="section-number">5.</span> <?php _e('Anmerkungen', 'contify-pdf-Fragebogen-2025'); ?></h3>
            </div>
            <div class="section-content">
                <div class="form-group">
                    <label for="additional_notes"><?php _e('Weitere Informationen, Wünsche oder besondere Anforderungen', 'contify-pdf-Fragebogen-2025'); ?></label>
                    <textarea name="additional_notes" id="additional_notes" rows="5"></textarea>
                </div>
            </div>
            <div class="form-navigation">
                <button type="button" class="button-prev"><?php _e('Zurück', 'contify-pdf-Fragebogen-2025'); ?></button>
                <button type="submit" class="submit-button" id="submitButton">
                    <span class="button-text"><?php _e('Fragebogen senden', 'contify-pdf-Fragebogen-2025'); ?></span>
                    <span class="button-spinner" style="display:none;"></span>
                </button>
            </div>
        </div>

        <div class="form-messages" id="formMessages" style="display:none;"></div>
    </form>

    <div class="form-footer">
        <p>&copy; 2025 contify - Die Textagentur</p>
    </div>
</div>