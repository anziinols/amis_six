/**
 * AMIS PDF Generator
 * JavaScript-based PDF generation using html2pdf.js
 * 
 * This module provides functions to generate PDFs from web pages
 * including reports, activities, and other content.
 */

class AMISPdfGenerator {
    constructor() {
        this.defaultOptions = {
            margin: [10, 10, 10, 10],
            filename: 'AMIS_Report.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2,
                useCORS: true,
                allowTaint: true,
                letterRendering: true
            },
            jsPDF: { 
                unit: 'mm', 
                format: 'a4', 
                orientation: 'portrait' 
            }
        };
    }

    /**
     * Show loading indicator
     */
    showLoading(buttonElement) {
        if (buttonElement) {
            buttonElement.disabled = true;
            const originalText = buttonElement.innerHTML;
            buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating PDF...';
            buttonElement.setAttribute('data-original-text', originalText);
        }
    }

    /**
     * Hide loading indicator
     */
    hideLoading(buttonElement) {
        if (buttonElement) {
            buttonElement.disabled = false;
            const originalText = buttonElement.getAttribute('data-original-text');
            if (originalText) {
                buttonElement.innerHTML = originalText;
                buttonElement.removeAttribute('data-original-text');
            }
        }
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'success') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

    /**
     * Prepare element for PDF generation
     */
    prepareElement(element) {
        // Add PDF-specific styling
        element.classList.add('pdf-generating');
        
        // Hide elements that shouldn't appear in PDF
        const elementsToHide = element.querySelectorAll('.no-print, .btn, .dropdown, .navbar, .sidebar');
        elementsToHide.forEach(el => {
            el.style.display = 'none';
        });

        // Ensure charts are visible
        const charts = element.querySelectorAll('canvas, .chart-container');
        charts.forEach(chart => {
            chart.style.display = 'block';
            chart.style.visibility = 'visible';
        });

        return elementsToHide;
    }

    /**
     * Restore element after PDF generation
     */
    restoreElement(element, hiddenElements) {
        element.classList.remove('pdf-generating');
        
        // Restore hidden elements
        hiddenElements.forEach(el => {
            el.style.display = '';
        });
    }

    /**
     * Generate PDF from HTML element
     */
    async generatePDF(element, options = {}) {
        const mergedOptions = { ...this.defaultOptions, ...options };
        
        try {
            // Prepare element
            const hiddenElements = this.prepareElement(element);
            
            // Wait a bit for any dynamic content to render
            await new Promise(resolve => setTimeout(resolve, 500));
            
            // Generate PDF
            await html2pdf()
                .set(mergedOptions)
                .from(element)
                .save();
            
            // Restore element
            this.restoreElement(element, hiddenElements);
            
            this.showToast('PDF generated successfully!', 'success');
            
        } catch (error) {
            console.error('PDF generation error:', error);
            this.showToast('Failed to generate PDF. Please try again.', 'error');
            throw error;
        }
    }

    /**
     * Generate PDF for activity page
     */
    async generateActivityPDF(activityId) {
        const button = event.target.closest('button') || event.target.closest('a');
        this.showLoading(button);

        try {
            // Get the main content area
            const contentElement = document.querySelector('.main-content') || 
                                 document.querySelector('.container-fluid') || 
                                 document.querySelector('main') ||
                                 document.body;

            const options = {
                filename: `Activity_${activityId}_Report.pdf`,
                margin: [15, 10, 15, 10],
                html2canvas: {
                    scale: 1.5,
                    useCORS: true,
                    allowTaint: true
                }
            };

            await this.generatePDF(contentElement, options);

        } catch (error) {
            console.error('Activity PDF generation failed:', error);
        } finally {
            this.hideLoading(button);
        }
    }

    /**
     * Generate PDF for report page
     */
    async generateReportPDF(reportType) {
        const button = event.target.closest('button') || event.target.closest('a');
        this.showLoading(button);

        try {
            // Get the main content area, excluding navigation
            const contentElement = document.querySelector('.main-content') || 
                                 document.querySelector('.container-fluid') || 
                                 document.querySelector('main');

            if (!contentElement) {
                throw new Error('Content element not found');
            }

            const reportTitle = document.querySelector('h4, h5, .card-title')?.textContent || reportType;
            const filename = `${reportTitle.replace(/[^a-zA-Z0-9]/g, '_')}_Report.pdf`;

            const options = {
                filename: filename,
                margin: [15, 10, 15, 10],
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'landscape' // Better for reports with charts
                },
                html2canvas: {
                    scale: 1.2,
                    useCORS: true,
                    allowTaint: true,
                    scrollX: 0,
                    scrollY: 0
                }
            };

            await this.generatePDF(contentElement, options);

        } catch (error) {
            console.error('Report PDF generation failed:', error);
        } finally {
            this.hideLoading(button);
        }
    }

    /**
     * Generate PDF for workplan report
     */
    async generateWorkplanReportPDF() {
        return this.generateReportPDF('Workplan');
    }

    /**
     * Generate PDF for NASP report
     */
    async generateNASPReportPDF() {
        return this.generateReportPDF('NASP');
    }

    /**
     * Generate PDF for MTDP report
     */
    async generateMTDPReportPDF() {
        return this.generateReportPDF('MTDP');
    }

    /**
     * Generate PDF for commodity report
     */
    async generateCommodityReportPDF() {
        return this.generateReportPDF('Commodity');
    }

    /**
     * Generate PDF for activity maps report
     */
    async generateActivityMapsReportPDF() {
        return this.generateReportPDF('Activity_Maps');
    }

    /**
     * Generate custom PDF with specific options
     */
    async generateCustomPDF(selector, customOptions = {}) {
        const button = event.target.closest('button') || event.target.closest('a');
        this.showLoading(button);

        try {
            const element = document.querySelector(selector);
            if (!element) {
                throw new Error(`Element with selector "${selector}" not found`);
            }

            await this.generatePDF(element, customOptions);

        } catch (error) {
            console.error('Custom PDF generation failed:', error);
        } finally {
            this.hideLoading(button);
        }
    }
}

// Create global instance
window.AMISPdf = new AMISPdfGenerator();

// Add PDF-specific CSS
const pdfStyles = `
<style>
.pdf-generating {
    background: white !important;
    color: black !important;
}

.pdf-generating .no-print,
.pdf-generating .btn,
.pdf-generating .dropdown,
.pdf-generating .navbar,
.pdf-generating .sidebar,
.pdf-generating .breadcrumb,
.pdf-generating .pagination {
    display: none !important;
}

.pdf-generating .card {
    border: 1px solid #dee2e6 !important;
    box-shadow: none !important;
    margin-bottom: 20px !important;
}

.pdf-generating .card-header {
    background-color: #f8f9fa !important;
    color: black !important;
    border-bottom: 1px solid #dee2e6 !important;
}

.pdf-generating .table {
    font-size: 11px !important;
    border-collapse: collapse !important;
}

.pdf-generating .table th,
.pdf-generating .table td {
    border: 1px solid #dee2e6 !important;
    padding: 8px !important;
}

.pdf-generating .chart-container,
.pdf-generating canvas {
    page-break-inside: avoid;
    max-width: 100% !important;
    height: auto !important;
}

.pdf-generating .bg-primary,
.pdf-generating .bg-success,
.pdf-generating .bg-info,
.pdf-generating .bg-warning,
.pdf-generating .bg-danger {
    color: white !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
}

.pdf-generating .text-white {
    color: white !important;
}

.pdf-generating .container-fluid {
    padding: 15px !important;
}

.pdf-generating h1, .pdf-generating h2, .pdf-generating h3,
.pdf-generating h4, .pdf-generating h5, .pdf-generating h6 {
    page-break-after: avoid;
    margin-top: 20px !important;
    margin-bottom: 10px !important;
}

@media print {
    .no-print {
        display: none !important;
    }

    .pdf-content {
        margin: 0 !important;
        padding: 20px !important;
    }

    body {
        background: white !important;
    }
}
</style>`;

// Inject PDF styles
document.head.insertAdjacentHTML('beforeend', pdfStyles);

// Global helper functions for backward compatibility
window.generateActivityPDF = function(activityId) {
    return window.AMISPdf.generateActivityPDF(activityId);
};

window.generateReportPDF = function(reportType) {
    return window.AMISPdf.generateReportPDF(reportType);
};

console.log('AMIS PDF Generator loaded successfully');
