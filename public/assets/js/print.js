function printReceipt(elem) {
    // Get the content from the specified element (receipt div)
    var new_str = document.getElementById(elem).innerHTML;

    // Open a new window
    var printWindow = window.open("", "", "width=600,height=800");

    // Add the receipt content to the new window
    printWindow.document.write(`
            <html>
            <head>
                <title>Receipt</title>
                <style>
                 @page {
                size: 8.3in 11.7in;
            }

            @page {
                size: A4;
            }

            .page {
                page-break-after: always;
                padding: 20px;
            }

            .table-bg {
                border-collapse: collapse;
                width: 100%;
                font-size: 15px;
                text-align: center;
            }

            .th {
                border: 1px solid #000;
                padding: 10px;
            }

            .td {
                border: 1px solid #000;
                padding: 3px;
            }

            .ass td {
                border: 1px solid #000;
                margin: 0px;
            }


            /* Container styling */
            .assessment-guide {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }

            .assessment-guide h3 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            /* Grid styling for two rows and three columns */
            .grades-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                justify-items: center;
                padding: 0 10px;
            }

            /* Styling for each grade card */
            .grade-card {
                border: 1px solid #000;
                padding: 8px;
                font-size: 14px;
                width: 100%;
                box-sizing: border-box;
                text-align: center;
            }

            /* Range and details styling */
            .grade-range {
                font-weight: bold;
            }

            .grade-details {
                font-style: italic;
            }


            @media print {
                @page {
                    margin: 0px;
                    margin-left: 20px;
                    margin-right: 20px;
                }
            }
                    /* Thermal Printer Optimized Receipt Styles */
            #thermal-receipt {
                font-family: 'Courier New', Courier, monospace;
              
            }

            /* Header Section */
            .receipt-header {
                text-align: center;
                margin-bottom: 10px;
            }

            .receipt-header img {
                max-height: 60px;
                max-width: 60px;
                margin-bottom: 5px;
            }

            .receipt-header h1 {
                font-size: 14px;
                margin: 0;
            }

            .receipt-header p {
                margin: 0;
                font-size:12px;
                line-height: 1.2em;
            }


            .customer-info {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .customer-info td {
                padding: 2px 0;
                font-size: 12px;
                vertical-align: top;
            }
            .customer-info tr {
                border: none;
            }

            .customer-info td {
                border: none;
            }


            .receipt-info {
                margin-bottom: 10px;
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
                padding: 5px 0;
            }


            /* Table Section */
            .receipt-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .receipt-table th,
            .receipt-table td {
                text-align: left;
                padding: 2px 0;
            }

            .receipt-table th {
                font-size: 12px;
                border-bottom: 1px dashed #000;
            }

            .receipt-table td {
                font-size: 12px;
            }

            /* Footer Section */
            .receipt-footer {
                text-align: center;
                margin-top: 10px;
                border-top: 1px dashed #000;
                padding-top: 5px;
            }

            .receipt-footer p {
                margin: 0;
                line-height: 1.2em;
                font-size: 12px;
            }

            /* Print-Specific Styles */
            @media print {
              
            }

      
            </style>
        </head>
        <body>
            <div id="receipt">
                ${new_str}
            </div>
        </body>
        </html>
    `);

    // Wait for the content to load, then print
    printWindow.document.close(); // Necessary to trigger print after content is loaded
    printWindow.print(); // Trigger the print dialog
    printWindow.close(); // Optionally close the window after printing

    // Close the window after 5 seconds (5000 milliseconds)
    setTimeout(() => {
        printWindow.close();
    }, 5000);

    return false; // Prevent any default action (e.g., redirect)
}

function printReceipt2(elem) {
    // Get the content from the specified element (receipt div)
    var new_str = document.getElementById(elem).innerHTML;

    // Open a new window
    var printWindow = window.open("", "", "width=600,height=800");

    // Add the receipt content to the new window
    printWindow.document.write(`
            <html>
            <head>
                <title>Receipt</title>
                <style>
                *,
            ::before,
            ::after {
                box-sizing: border-box;
                border-width: 0;
                border-style: solid;
                border-color: #e5e7eb;
            }

        ::before,
        ::after {
            --tw-content: "";
        }

        html {
            line-height: 1.5;
            -webkit-text-size-adjust: 100%;
            -moz-tab-size: 4;
            tab-size: 4;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif,
                "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
                "Noto Color Emoji";
            font-feature-settings: normal;
            font-variation-settings: normal;
        }


        body {
            margin: 0;
            line-height: inherit;
        }

        hr {
            height: 0;
            color: inherit;
            border-top-width: 1px;

        }


abbr:where([title]) {
    -webkit-text-decoration: underline dotted;
    text-decoration: underline dotted;
}


h1,
h2,
h3,
h4,
h5,
h6 {
    font-size: inherit;
    font-weight: inherit;
}

a {
    color: inherit;
    text-decoration: inherit;
}

b,
strong {
    font-weight: bolder;
}


code,
kbd,
samp,
pre {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,
        "Liberation Mono", "Courier New", monospace;
    font-size: 1em;
}

small {
    font-size: 80%;
}


sub,
sup {
    font-size: 75%;
    line-height: 0;
    position: relative;
    vertical-align: baseline;
}

sub {
    bottom: -0.25em;
}

sup {
    top: -0.5em;
}

table {
    text-indent: 0;
    border-color: inherit;
    border-collapse: collapse;
}

button,
input,
optgroup,
select,
textarea {
    font-family: inherit;
    font-feature-settings: inherit;
    font-variation-settings: inherit;
    font-size: 100%;
    font-weight: inherit;
    line-height: inherit;
    color: inherit;
    margin: 0;
    padding: 0;
}



button,
select {
    text-transform: none;
}


button,
[type="button"],
[type="reset"],
[type="submit"] {
    -webkit-appearance: button;
    background-color: transparent;
    background-image: none;

}


:-moz-focusring {
    outline: auto;
}

:-moz-ui-invalid {
    box-shadow: none;
}


progress {
    vertical-align: baseline;
}


::-webkit-inner-spin-button,
::-webkit-outer-spin-button {
    height: auto;
}

[type="search"] {
    -webkit-appearance: textfield;
    outline-offset: -2px;

}


::-webkit-search-decoration {
    -webkit-appearance: none;
}

::-webkit-file-upload-button {
    -webkit-appearance: button;
    font: inherit;

}


summary {
    display: list-item;
}



blockquote,
dl,
dd,
h1,
h2,
h3,
h4,
h5,
h6,
hr,
figure,
p,
pre {
    margin: 0;
}

fieldset {
    margin: 0;
    padding: 0;
}

legend {
    padding: 0;
}

ol,
ul,
menu {
    list-style: none;
    margin: 0;
    padding: 0;
}

dialog {
    padding: 0;
}


textarea {
    resize: vertical;
}


input::placeholder,
textarea::placeholder {
    opacity: 1;
    color: #9ca3af;

}


button,
[role="button"] {
    cursor: pointer;
}



:disabled {
    cursor: default;
}



img,
svg,
video,
canvas,
audio,
iframe,
embed,
object {
    display: block;

    vertical-align: middle;

}



img,
video {
    max-width: 100%;
    height: auto;
}


[hidden] {
    display: none;
}

*,
::before,
::after {
    --tw-border-spacing-x: 0;
    --tw-border-spacing-y: 0;
    --tw-translate-x: 0;
    --tw-translate-y: 0;
    --tw-rotate: 0;
    --tw-skew-x: 0;
    --tw-skew-y: 0;
    --tw-scale-x: 1;
    --tw-scale-y: 1;
    --tw-pan-x: ;
    --tw-pan-y: ;
    --tw-pinch-zoom: ;
    --tw-scroll-snap-strictness: proximity;
    --tw-gradient-from-position: ;
    --tw-gradient-via-position: ;
    --tw-gradient-to-position: ;
    --tw-ordinal: ;
    --tw-slashed-zero: ;
    --tw-numeric-figure: ;
    --tw-numeric-spacing: ;
    --tw-numeric-fraction: ;
    --tw-ring-inset: ;
    --tw-ring-offset-width: 0px;
    --tw-ring-offset-color: #fff;
    --tw-ring-color: rgb(59 130 246 / 0.5);
    --tw-ring-offset-shadow: 0 0 #0000;
    --tw-ring-shadow: 0 0 #0000;
    --tw-shadow: 0 0 #0000;
    --tw-shadow-colored: 0 0 #0000;
    --tw-blur: ;
    --tw-brightness: ;
    --tw-contrast: ;
    --tw-grayscale: ;
    --tw-hue-rotate: ;
    --tw-invert: ;
    --tw-saturate: ;
    --tw-sepia: ;
    --tw-drop-shadow: ;
    --tw-backdrop-blur: ;
    --tw-backdrop-brightness: ;
    --tw-backdrop-contrast: ;
    --tw-backdrop-grayscale: ;
    --tw-backdrop-hue-rotate: ;
    --tw-backdrop-invert: ;
    --tw-backdrop-opacity: ;
    --tw-backdrop-saturate: ;
    --tw-backdrop-sepia: ;
}

::backdrop {
    --tw-border-spacing-x: 0;
    --tw-border-spacing-y: 0;
    --tw-translate-x: 0;
    --tw-translate-y: 0;
    --tw-rotate: 0;
    --tw-skew-x: 0;
    --tw-skew-y: 0;
    --tw-scale-x: 1;
    --tw-scale-y: 1;
    --tw-pan-x: ;
    --tw-pan-y: ;
    --tw-pinch-zoom: ;
    --tw-scroll-snap-strictness: proximity;
    --tw-gradient-from-position: ;
    --tw-gradient-via-position: ;
    --tw-gradient-to-position: ;
    --tw-ordinal: ;
    --tw-slashed-zero: ;
    --tw-numeric-figure: ;
    --tw-numeric-spacing: ;
    --tw-numeric-fraction: ;
    --tw-ring-inset: ;
    --tw-ring-offset-width: 0px;
    --tw-ring-offset-color: #fff;
    --tw-ring-color: rgb(59 130 246 / 0.5);
    --tw-ring-offset-shadow: 0 0 #0000;
    --tw-ring-shadow: 0 0 #0000;
    --tw-shadow: 0 0 #0000;
    --tw-shadow-colored: 0 0 #0000;
    --tw-blur: ;
    --tw-brightness: ;
    --tw-contrast: ;
    --tw-grayscale: ;
    --tw-hue-rotate: ;
    --tw-invert: ;
    --tw-saturate: ;
    --tw-sepia: ;
    --tw-drop-shadow: ;
    --tw-backdrop-blur: ;
    --tw-backdrop-brightness: ;
    --tw-backdrop-contrast: ;
    --tw-backdrop-grayscale: ;
    --tw-backdrop-hue-rotate: ;
    --tw-backdrop-invert: ;
    --tw-backdrop-opacity: ;
    --tw-backdrop-saturate: ;
    --tw-backdrop-sepia: ;
}

.fixed {
    position: fixed;
}

.bottom-0 {
    bottom: 0px;
}

.left-0 {
    left: 0px;
}

.table {
    display: table;
}

.h-12 {
    height: 3rem;
}

.w-1\/2 {
    width: 50%;
}

.w-full {
    width: 100%;
}

.border-collapse {
    border-collapse: collapse;
}

.border-spacing-0 {
    --tw-border-spacing-x: 0px;
    --tw-border-spacing-y: 0px;
    border-spacing: var(--tw-border-spacing-x) var(--tw-border-spacing-y);
}

.whitespace-nowrap {
    white-space: nowrap;
}

.border-b {
    border-bottom-width: 1px;
}

.border-b-2 {
    border-bottom-width: 2px;
}

.border-r {
    border-right-width: 1px;
}

.border-main {
    border-color: #5c6ac4;
}

.bg-main {
    background-color: #5c6ac4;
}

.bg-slate-100 {
    background-color: #f1f5f9;
}

.p-3 {
    padding: 0.75rem;
}

.px-14 {
    padding-left: 3.5rem;
    padding-right: 3.5rem;
}

.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.py-10 {
    padding-top: 2.5rem;
    padding-bottom: 2.5rem;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.py-4 {
    padding-top: 1rem;
    padding-bottom: 1rem;
}

.py-6 {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
}

.pb-3 {
    padding-bottom: 0.75rem;
}

.pl-2 {
    padding-left: 0.5rem;
}

.pl-3 {
    padding-left: 0.75rem;
}

.pl-4 {
    padding-left: 1rem;
}

.pr-3 {
    padding-right: 0.75rem;
}

.pr-4 {
    padding-right: 1rem;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.align-top {
    vertical-align: top;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.font-bold {
    font-weight: 700;
}

.italic {
    font-style: italic;
}

.text-main {
    color: #5c6ac4;
}

.text-neutral-600 {
    color: #525252;
}

.text-neutral-700 {
    color: #404040;
}

.text-slate-300 {
    color: #cbd5e1;
}

.text-slate-400 {
    color: #94a3b8;
}

.text-white {
    color: #fff;
}

@page {
    margin: 0;
}

@media print {
    body {
        -webkit-print-color-adjust: exact;
    }
}

                </style>
            </head>
            <body>
                <div id="receipt">
                    ${new_str}
                </div>
            </body>
            </html> 
    `);

    // Wait for the content to load, then print
    printWindow.document.close(); // Necessary to trigger print after content is loaded
    printWindow.print(); // Trigger the print dialog
    printWindow.close(); // Optionally close the window after printing

    // Close the window after 5 seconds (5000 milliseconds)
    setTimeout(() => {
        printWindow.close();
    }, 5000);

    return false; // Prevent any default action (e.g., redirect)
}
