<div id="pdfViewerModal" class="pdf-viewer-modal" onclick="closePDFViewer(event)">
    <div class="pdf-viewer-content">
        <div class="pdf-viewer-header">
            <h3 class="pdf-viewer-title" id="pdfViewerTitle">Document Viewer</h3>
            <div class="pdf-viewer-controls">
                <button class="pdf-control-btn" onclick="downloadPDF()" title="Download PDF">
                    <i class="fa fa-download"></i> Download
                </button>
                <button class="pdf-control-btn" onclick="openPDFNewTab()" title="Open in New Tab">
                    <i class="fa fa-arrow-up-right-from-square"></i> Open in Tab
                </button>
                <button class="close-pdf-viewer" onclick="closePDFViewer()" title="Close">&times;</button>
            </div>
        </div>
        <div id="pdfViewerContent" style="flex:1;display:flex;flex-direction:column;min-height:0;">
            <div class="pdf-loading" id="pdfLoading">
                <i class="fa fa-spinner fa-spin fa-2x"></i> Loading PDF document...
            </div>
            <div class="pdf-error" id="pdfError" style="display:none;">
                <i class="fa fa-triangle-exclamation fa-3x" style="color:var(--primary-orange);margin-bottom:20px;"></i>
                <h3>Unable to display PDF</h3>
                <p>Your browser may not support embedded PDF viewing.</p>
                <button class="cta-button" onclick="openPDFNewTab()">Open in New Tab</button>
                <button class="cta-button" onclick="downloadPDF()" style="margin-left:10px;">Download PDF</button>
            </div>
            <iframe id="pdfViewerFrame" class="pdf-viewer-frame" style="display:none;"></iframe>
        </div>
    </div>
</div>
