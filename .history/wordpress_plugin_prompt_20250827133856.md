# WordPress Plugin Development Prompt: Contify Fragebogen 2025

## Overview
Create a modern, user-friendly WordPress plugin that implements the "Contify Fragebogen 2025" questionnaire as an interactive form. The plugin should be lightweight, follow WordPress coding standards, and integrate seamlessly with Elementor via shortcode.

## Core Requirements

### 1. Plugin Structure
- Create a WordPress plugin named "contify-pdf-Fragebogen-2025"
- Use OOP approach with proper namespacing
- Follow WordPress Plugin Boilerplate structure
- Include uninstall.php for clean removal
- Add plugin activation/deactivation hooks

### 2. Form Implementation
- Convert the questionnaire from markdown to a responsive HTML5 form
- Organize form into collapsible sections for better UX
- Implement client-side validation
- Use modern CSS (Flexbox/Grid) for responsive design
- Include progress indicator
- Make all form fields accessible (WCAG 2.1 compliant)

### 3. Email Functionality
- Use WordPress wp_mail() function for sending
- Implement SMTP integration using WordPress core functions
- Send form data as a well-formatted HTML email
- Include form responses in the email body
- Add CC/BCC support in plugin settings
- Implement email template system
- Add success/error messages after submission

### 4. Elementor Integration
- Create a custom Elementor widget
- Register shortcode: [contify_fragebogen_2025]
- Add widget to Elementor's widget panel
- Include basic styling controls in Elementor

### 5. Admin Interface
- Add settings page under WordPress admin menu
- Configure email recipients
- Set up email templates
- Enable/disable form sections
- Export form submissions as CSV

### 6. Security
- Implement nonce verification
- Sanitize and validate all inputs
- Use prepared statements for database operations
- Rate limiting to prevent spam
- GDPR compliance (data retention settings)

## Technical Specifications

### File Structure
```
contify-fragebogen/
├── admin/
│   ├── css/
│   ├── js/
│   └── partials/
├── includes/
│   ├── class-contify-fragebogen.php
│   ├── class-contify-fragebogen-form-handler.php
│   └── class-contify-fragebogen-email.php
├── public/
│   ├── css/
│   ├── js/
│   └── partials/
├── languages/
├── templates/
├── uninstall.php
└── contify-fragebogen.php
```

### Database
- Store form submissions in custom table
- Include timestamp and IP address
- Implement data retention policy
- Add database versioning for updates

### Dependencies
- jQuery 3.6.0+ (included with WordPress)
- Font Awesome 5+ (CDN)
- Optional: Contact Form 7 integration

## Development Notes
- Use WordPress coding standards
- Document all functions with PHPDoc
- Include inline comments for complex logic
- Implement proper error handling
- Add debug mode for development

## Testing
- Test with latest WordPress version
- Verify compatibility with major themes
- Test with different SMTP configurations
- Mobile responsiveness testing
- Cross-browser testing

## Delivery
- Package as .zip file
- Include documentation (README.md)
- Add screenshots
- Include uninstall instructions

## Future Enhancements
1. Multi-step form wizard
2. Save and resume functionality
3. File upload support
4. Conditional logic for questions
5. Integration with CRM systems
6. Analytics dashboard
7. Multi-language support
8. Form analytics and tracking

## Implementation Timeline
1. Basic form structure - 2 days
2. Form styling and validation - 2 days
3. Email functionality - 2 days
4. Admin interface - 2 days
5. Testing and bug fixes - 2 days
6. Documentation - 1 day

## Acceptance Criteria
- Plugin passes WordPress.org plugin review guidelines
- No PHP notices or warnings in debug mode
- Responsive on all device sizes
- Accessible according to WCAG 2.1
- Secure against common vulnerabilities (OWASP Top 10)
- Well-documented code and user documentation
