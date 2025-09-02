# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview
This is a WordPress plugin development project for "Contify PDF Fragebogen 2025" - a professional questionnaire form plugin for text projects. The plugin implements a comprehensive questionnaire system with email functionality and Elementor integration.

## Architecture
- **Main Plugin File**: `contify-pdf-Fragebogen-2025/contify-pdf-Fragebogen-2025.php` - WordPress plugin entry point using singleton pattern
- **Includes Directory**: `contify-pdf-Fragebogen-2025/includes/` - Core plugin classes (form handler, email handler, shortcode)
- **Templates Directory**: `contify-pdf-Fragebogen-2025/templates/` - Frontend form templates
- **Assets Directory**: `contify-pdf-Fragebogen-2025/assets/` - CSS, JS, and other static files

## Development Environment
- **Python Path**: `C:\Users\Hill\miniconda3_latest\python.exe` (system Python installation)
- **WordPress Plugin Structure**: Follows WordPress Plugin Boilerplate standards
- **Plugin Constants**: Defined in main plugin file with CON_FRA_2025_ prefix

## Form Structure
The questionnaire is organized into 5 main sections based on `contify_fragebogen_bereinigt.md`:
1. **Allgemeine Informationen** (General Information) - Text count, target group, user intention
2. **Textspezifische Informationen** (Text-specific Information) - W-questions, WDF*IDF analysis, language style  
3. **Zusätzliche Informationen** (Additional Information) - Keywords, image suggestions, meta info
4. **CMS Informationen** (CMS Information) - Tables, quotes, formatting requirements
5. **Anmerkungen** (Comments) - Additional requirements and notes

## Key Features (from wordpress_plugin_prompt.md)
- Responsive HTML5 form with collapsible sections
- Email functionality using WordPress wp_mail()
- Elementor integration via shortcode `[contify_fragebogen_2025]`
- Admin settings interface
- GDPR compliance and security features
- Database storage for form submissions

## Plugin Structure
```
contify-pdf-Fragebogen-2025/
├── contify-pdf-Fragebogen-2025.php (main plugin file)
├── includes/
│   ├── class-form-handler.php
│   ├── class-email-handler.php  
│   └── class-shortcode.php
├── assets/ (CSS, JS files)
└── templates/ (form templates)
```

## Important Notes
- Plugin uses singleton pattern for main class instantiation
- Constants are prefixed with `CON_FRA_2025_`
- Plugin text domain: `contify-pdf-Fragebogen-2025`
- Follows WordPress coding standards and OOP approach
- Includes proper activation/deactivation hooks