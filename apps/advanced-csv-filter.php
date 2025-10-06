<?php
/* 
=====================================================================================
  HIDDEN INSTRUCTIONS / USAGE NOTES (Server-side comment, not shown to end user):
  
  1) We gather unique values from the chosen column of the CSV file and present them
     as suggestions via an HTML <datalist>. The user can pick one or type any other value.
  2) The rest of the code handles CSV parsing (Papa Parse), filter creation, applying filters,
     reset functionality, and downloading the filtered CSV.
  3) Added sticky headers, sortable columns, and "Expand" modals for both Original and Filtered data.
  4) The "Expand" modals are now full-screen to show the table at full width (Bootstrap 5 `modal-fullscreen`).
=====================================================================================
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Advanced CSV Filtering Tool – Multi-Condition Analyzer</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Upload spreadsheets, build complex filters, and export curated datasets with the BREN7 Advanced CSV Filtering Tool.">
  <meta name="keywords" content="csv filter tool, spreadsheet analyzer, data filtering web app, csv export, data cleanup">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Advanced CSV Filtering Tool – Multi-Condition Analyzer">
  <meta property="og:description" content="Create layered filters, highlight matches, and download refined CSV outputs with this advanced data tool from BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/advanced-csv-filter.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Advanced CSV Filtering Tool – Multi-Condition Analyzer">
  <meta name="twitter:description" content="Filter and export CSV data with precision using the BREN7 Advanced CSV tool.">
  <meta name="twitter:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Favicon -->
  <link rel="icon" href="https://bren7.com/images/favicon.jpg" type="image/jpeg">

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>

  <!-- Bootstrap CSS for improved styling -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

  <style>
    body {
      background-color: #f8f9fa; /* Light gray background */
      margin-top: 20px;
    }
    .card {
      margin-bottom: 20px;
    }
    /* Make tables scrollable if large */
    .table-container {
      position: relative;   /* for sticky positioning */
      max-height: 400px;    /* Adjust or remove if you want a different height */
      overflow-y: auto;
    }
    /* Sticky header for both tables (including modals) */
    .table-container table thead th {
      position: sticky;
      top: 0;
      background: #ffffff;
      z-index: 10;
    }
    
    .filter-criteria {
      margin-bottom: 15px;
    }
    
    /* Button color overrides */
    .btn-primary { background:#2EB7A0; border:#2EB7A0; color:#fff; }
    .btn-secondary { background:#2EB7A0; border:#2EB7A0; color:#fff; }
    .btn-warning { background:#2EB7A0; border:#2EB7A0; color:#fff; }
    .btn-info { background:#2EB7A0; border:#2EB7A0; color:#fff; }

    /* Optional: show a pointer cursor on sortable columns */
    thead th.sortable {
      cursor: pointer;
    }
  </style>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/app-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">

</head>
<body class="app-page">
  <div class="grid-background"></div>
  <div class="main-wrapper app-wrapper">
    <header class="app-header">
      <div class="header-content">
        <div class="logo-wrapper">
          <div class="logo">BREN<span class="accent">7</span></div>
          <div class="logo-underline"></div>
        </div>
        <p class="tagline">Web Tools & Experiments</p>
      </div>
    </header>
    <main class="app-main">
      <div class="app-content">

  <div class="container">
    <!-- Page Header with About Button -->
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="mb-4">Advanced CSV Filtering Tool</h1>
      <!-- About Button triggers the modal below -->
      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#aboutModal">
        About & Instructions
      </button>
    </div>

    <!-- 1) Upload CSV -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">1) Upload CSV</h5>
        <input type="file" id="csvFileInput" accept=".csv" class="form-control" />
        <small class="text-muted">Select a CSV file from your computer to parse and filter.</small>
      </div>
    </div>

    <!-- 2) Display Original CSV Data -->
    <div class="card">
      <div class="card-body">
        <!-- "2) Original CSV Data" title + Expand button -->
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">2) Original CSV Data</h5>
          <button id="expandOriginalBtn" class="btn btn-outline-secondary btn-sm">
            Expand
          </button>
        </div>

        <div class="table-container mt-3">
          <table id="originalTable" class="table table-striped table-bordered"></table>
        </div>
      </div>
    </div>

    <!-- 3) Filter Options -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">3) Filter Options</h5>

        <!-- Container for multiple filter criteria -->
        <div id="filtersContainer">
          <!-- Filter criteria will be appended here -->
        </div>

        <!-- Button row: Add Filter, Apply Filters, Reset -->
        <div class="d-flex gap-2">
          <button id="addFilterBtn" class="btn btn-secondary mb-3">Add Filter</button>
          <button id="applyFiltersBtn" class="btn btn-primary mb-3">Apply Filters</button>
          <button id="resetBtn" class="btn btn-warning mb-3">Reset</button>
        </div>
      </div>
    </div>

    <!-- 4) Display Filtered Results + Download -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 d-flex align-items-center">
            4) Filtered Results
            <!-- Show the count of rows in the filtered results -->
            <span id="filterCount" class="badge bg-danger ms-3">0</span>
            <!-- Filter Summary Element -->
            <small id="filterSummary" class="text-muted ms-3"></small>
          </h5>
          <!-- "Expand" button for Filtered Results -->
          <button id="expandFilteredBtn" class="btn btn-outline-secondary btn-sm">
            Expand
          </button>
        </div>

        <div class="table-container mt-3">
          <table id="filteredTable" class="table table-striped table-bordered"></table>
        </div>
        <button id="downloadBtn" class="btn btn-success mt-3" style="display: none;">Download Filtered CSV</button>
      </div>
    </div>
  </div>

  <!-- About & Instructions Modal -->
  <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="aboutModalLabel">About This Tool</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- About Section -->
          <p>
            <strong>Advanced CSV Filtering Tool</strong> is a client-side web application
            that lets you upload a CSV file, apply multiple filter criteria, and then
            download the filtered results in CSV format.
          </p>
          <p>
            This tool uses <a href="https://www.papaparse.com/" target="_blank">Papa Parse</a> 
            for parsing CSV files entirely in the browser, meaning no data leaves your computer.
          </p>
          <p>
            <strong>Features:</strong>
          <ul>
            <li>Multiple filter criteria</li>
            <li>Smart input fields for dates/numbers/text</li>
            <li>Auto-suggest values from the CSV data using datalists</li>
            <li>Client-side CSV parsing and export</li>
            <li>Lightweight and privacy-friendly</li>
          </ul>
          </p>

          <!-- Instructions Section -->
          <hr>
          <h5>How to Use</h5>
          <ol>
            <li><strong>Upload Your CSV:</strong> Click on the <em>Upload CSV</em> area to select a CSV file.</li>
            <li><strong>Check Original Data:</strong> The raw CSV data appears in the <em>Original CSV Data</em> table.</li>
            <li><strong>Set Your Filters:</strong> 
              <ul>
                <li>Click <em>Add Filter</em> to create a new filter condition.</li>
                <li>Select which column to filter on. A list of unique values (from that column) will be suggested.</li>
                <li>Choose a filter type (equals, contains, greater, etc.)—the input field type may change to date/number/text.</li>
                <li>Either pick one of the suggested values or type your own.</li>
              </ul>
            </li>
            <li><strong>Apply Filters:</strong> Click <em>Apply Filters</em> to see the filtered results in the <em>Filtered Results</em> table.</li>
            <li><strong>Reset:</strong> Click <em>Reset</em> to clear all filters and restore the original data.</li>
            <li><strong>Download Results:</strong> If there are any filtered rows, click <em>Download Filtered CSV</em> to save them.</li>
          </ol>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Expanding Original CSV Data -->
  <!-- Replaced 'modal-xl' with 'modal-fullscreen' for full-width (and full-height) -->
  <div class="modal fade" id="originalExpandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Original CSV Data (Expanded)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Optionally remove max-height if you don't want internal scrolling -->
          <div class="table-container">
            <table id="modalOriginalTable" class="table table-striped table-bordered"></table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Expanding Filtered Results -->
  <!-- Replaced 'modal-xl' with 'modal-fullscreen' for full-width (and full-height) -->
  <div class="modal fade" id="filteredExpandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Filtered Results (Expanded)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Optionally remove max-height if you don't want internal scrolling -->
          <div class="table-container">
            <table id="modalFilteredTable" class="table table-striped table-bordered"></table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Papa Parse (for client-side CSV parsing) -->
  <script src="https://cdn.jsdelivr.net/npm/papaparse@5.3.2/papaparse.min.js"></script>
  <!-- Bootstrap JS (for dynamic components) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    let originalData = []; // Holds the full parsed CSV (array of objects)
    let filteredData = []; // Holds the filtered rows
    let headers = [];      // Holds CSV headers

    // Global variable to store the applied filters (for dynamic filename)
    let currentFilters = [];

    // For column sorting in the Filtered Results (both main and modal)
    let sortState = { column: null, direction: 'asc' }; // track which column is sorted and how

    // DOM elements
    const csvFileInput      = $('#csvFileInput');
    const originalTable     = $('#originalTable');
    const filteredTable     = $('#filteredTable');
    const filtersContainer  = $('#filtersContainer');
    const addFilterBtn      = $('#addFilterBtn');
    const applyFiltersBtn   = $('#applyFiltersBtn');
    const resetBtn          = $('#resetBtn');
    const downloadBtn       = $('#downloadBtn');
    const filterSummary     = $('#filterSummary');
    const filterCount       = $('#filterCount');

    // Expand button references
    const expandOriginalBtn = $('#expandOriginalBtn');
    const expandFilteredBtn = $('#expandFilteredBtn');

    // Modal table references
    const modalOriginalTable = $('#modalOriginalTable');
    const modalFilteredTable = $('#modalFilteredTable');

    // -----------------------------
    // 1) Handle CSV File Upload
    // -----------------------------
    csvFileInput.on('change', function(event) {
      const file = event.target.files[0];
      if (!file) return; // No file selected

      // Use Papa Parse to parse the file directly in the browser
      Papa.parse(file, {
        header: true,         // first row = headers
        skipEmptyLines: true, // ignore empty lines
        dynamicTyping: true,  // automatically typecast fields
        complete: function(results) {
          originalData = results.data;
          headers = results.meta.fields;
          // Display the original data in a table
          displayDataInTable(originalData, originalTable);
          // Initialize filter options
          initializeFilters();
          // Clear any previously filtered data
          filteredData = [];
          displayDataInTable([], filteredTable);
          downloadBtn.hide();
          // Clear filter summary
          filterSummary.text('');
          // Reset count to 0 (no filtered data displayed yet)
          filterCount.text(0);
          // Reset sort state
          sortState = { column: null, direction: 'asc' };
        },
        error: function(err) {
          console.error('Error parsing CSV:', err);
          alert('Error parsing CSV. Check console for details.');
        }
      });
    });

    // -----------------------------
    // 2) Display data in table
    // -----------------------------
    function displayDataInTable(dataArray, tableElement) {
      tableElement.empty();

      if (!dataArray || dataArray.length === 0) {
        tableElement.append('<tr><td>No data available</td></tr>');
        return;
      }

      // Build table headers from the keys of the first object
      const colHeaders = Object.keys(dataArray[0]);

      // Identify if table is "filtered" or "modalFiltered"
      const tableId = tableElement.attr('id');
      const isFilteredTable = (tableId === 'filteredTable' || tableId === 'modalFilteredTable');

      let thead = '<thead><tr>';
      colHeaders.forEach(header => {
        // Make Filtered Results table (and modal) headers "sortable"
        if (isFilteredTable) {
          thead += `<th class="sortable" data-column="${header}">${header}</th>`;
        } else {
          thead += `<th>${header}</th>`;
        }
      });
      thead += '</tr></thead>';

      // Build table body
      let tbody = '<tbody>';
      dataArray.forEach(row => {
        tbody += '<tr>';
        colHeaders.forEach(header => {
          const cellValue = row[header] ?? '';
          tbody += `<td>${cellValue}</td>`;
        });
        tbody += '</tr>';
      });
      tbody += '</tbody>';

      tableElement.append(thead + tbody);
    }

    // -----------------------------
    // 3) Initialize Filters
    // -----------------------------
    function initializeFilters() {
      // Clear existing filters
      filtersContainer.empty();
      // Add one default filter row
      addFilter();
    }

    // -----------------------------
    // 4) Add a new filter criteria
    // -----------------------------
    function addFilter() {
      const filterId = Date.now(); // Unique identifier

      const filterHTML = `
        <div class="filter-criteria card p-3 mb-3" data-id="${filterId}">
          <div class="row">
            <div class="col-md-3">
              <label class="form-label">Column</label>
              <select class="form-select columnSelect">
                <option value="">--Select Column--</option>
                ${headers.map(header => `<option value="${header}">${header}</option>`).join('')}
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Filter Type</label>
              <select class="form-select filterType">
                <option value="">--Select Type--</option>
                <option value="equals">Equals</option>
                <option value="contains">Contains</option>
                <option value="greater">Greater Than</option>
                <option value="less">Less Than</option>
                <option value="date_before">Date Before</option>
                <option value="date_after">Date After</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Value</label>
              <!-- We'll attach a datalist to this input dynamically -->
              <input type="text" class="form-control filterValue" placeholder="Enter value" />
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button class="btn btn-danger removeFilterBtn">Remove</button>
            </div>
          </div>
        </div>
      `;
      filtersContainer.append(filterHTML);
    }

    // -----------------------------
    // 4a) Datalist Helper
    // -----------------------------
    function getUniqueValues(columnName) {
      if (!columnName) return [];
      const uniqueSet = new Set();
      for (const row of originalData) {
        if (row[columnName] !== null && row[columnName] !== undefined) {
          uniqueSet.add(String(row[columnName]));
        }
      }
      return Array.from(uniqueSet).sort();
    }

    // When the user picks a column, build a <datalist> of unique values for that column
    filtersContainer.on('change', '.columnSelect', function() {
      const selectedColumn = $(this).val();
      const filterRow      = $(this).closest('.filter-criteria');
      const filterId       = filterRow.data('id');
      const datalistId     = 'datalist_' + filterId;
      const filterInput    = filterRow.find('.filterValue');

      // Remove any previous datalist in this filter row
      filterRow.find('datalist').remove();

      if (selectedColumn) {
        // Attach a unique ID to the input
        filterInput.attr('list', datalistId);

        // Build new <datalist> from the unique column values
        const uniqueVals = getUniqueValues(selectedColumn);
        let datalistHTML = `<datalist id="${datalistId}">`;
        uniqueVals.forEach(val => {
          datalistHTML += `<option value="${val}">`;
        });
        datalistHTML += '</datalist>';

        // Insert the datalist into the DOM (inside the filter card)
        filterRow.append(datalistHTML);
      } else {
        // If no column is selected, remove the list attribute
        filterInput.removeAttr('list');
      }
    });

    // -----------------------------
    // Make Value field "smart"
    // -----------------------------
    filtersContainer.on('change', '.filterType', function() {
      const selectedType = $(this).val();
      let inputType = 'text';
      let placeholder = 'Enter value';

      switch (selectedType) {
        case 'greater':
        case 'less':
          inputType = 'number';
          placeholder = 'Enter a number';
          break;
        case 'date_before':
        case 'date_after':
          inputType = 'date';
          placeholder = 'Select a date';
          break;
        case 'equals':
        case 'contains':
        default:
          inputType = 'text';
          placeholder = 'Enter text';
          break;
      }

      $(this).closest('.filter-criteria')
             .find('.filterValue')
             .attr('type', inputType)
             .attr('placeholder', placeholder);
    });

    // -----------------------------
    // Event listener to add a new filter
    // -----------------------------
    addFilterBtn.on('click', function() {
      addFilter();
    });

    // Event delegation to handle removal of filters
    filtersContainer.on('click', '.removeFilterBtn', function() {
      $(this).closest('.filter-criteria').remove();
    });

    // -----------------------------
    // 5) Apply Filters
    // -----------------------------
    applyFiltersBtn.on('click', function() {
      // Gather all filter criteria
      const filters = [];
      $('.filter-criteria').each(function() {
        const column = $(this).find('.columnSelect').val();
        const type   = $(this).find('.filterType').val();
        let value    = $(this).find('.filterValue').val().trim();

        if (column && type && value !== '') {
          filters.push({ column, type, value });
        }
      });

      if (filters.length === 0) {
        alert('Please add at least one valid filter criteria.');
        return;
      }

      // Save filters for filename usage
      currentFilters = filters;

      // Apply filters to the original data
      filteredData = originalData.filter(row => {
        return filters.every(filter => {
          const cellValue = row[filter.column];
          if (cellValue === undefined || cellValue === null) return false;

          switch (filter.type) {
            case 'equals':
              return String(cellValue).toLowerCase() === String(filter.value).toLowerCase();
            case 'contains':
              return String(cellValue).toLowerCase().includes(String(filter.value).toLowerCase());
            case 'greater':
              return parseFloat(cellValue) > parseFloat(filter.value);
            case 'less':
              return parseFloat(cellValue) < parseFloat(filter.value);
            case 'date_before':
              return new Date(cellValue) < new Date(filter.value);
            case 'date_after':
              return new Date(cellValue) > new Date(filter.value);
            default:
              return false;
          }
        });
      });

      // Reset any previous sort state 
      sortState = { column: null, direction: 'asc' };

      // Display the filtered results
      displayDataInTable(filteredData, filteredTable);

      // Show or hide the download button
      if (filteredData.length > 0) {
        downloadBtn.show();
      } else {
        downloadBtn.hide();
      }

      // Update the filtered row count
      filterCount.text(filteredData.length);

      // Update Filter Summary
      const filterDescriptions = filters.map(filter => {
        let filterTypeText;
        switch (filter.type) {
          case 'equals':
            filterTypeText = 'equals';
            break;
          case 'contains':
            filterTypeText = 'contains';
            break;
          case 'greater':
            filterTypeText = 'greater than';
            break;
          case 'less':
            filterTypeText = 'less than';
            break;
          case 'date_before':
            filterTypeText = 'date before';
            break;
          case 'date_after':
            filterTypeText = 'date after';
            break;
          default:
            filterTypeText = filter.type;
        }
        return `<strong>${filter.column}</strong> ${filterTypeText} <em>${filter.value}</em>`;
      });

      filterSummary.html("Filters Applied: " + filterDescriptions.join('; '));
    });

    // -----------------------------
    // 5a) Reset Filters
    // -----------------------------
    resetBtn.on('click', function() {
      // Re-initialize filters (clears them and adds one empty row)
      initializeFilters();
      
      // Show the original data again in the "Filtered Results" section
      displayDataInTable(originalData, filteredTable);

      // Hide the download button
      downloadBtn.hide();

      // Clear the filter summary
      filterSummary.text('');

      // Show the full row count of original data
      filterCount.text(originalData.length);

      currentFilters = [];
      sortState = { column: null, direction: 'asc' };
    });

    // -----------------------------
    // 6) Download Filtered CSV
    // -----------------------------
    // Utility to create a filename from the applied filters
    function createFilteredFileName(filters) {
      if (!filters || !filters.length) {
        return 'filtered_data.csv';
      }
      // Build a descriptor for each filter like "Column_Type_Value"
      const parts = filters.map(f => {
        let typeAbbr = f.type;
        if (typeAbbr === 'date_before') typeAbbr = 'before';
        if (typeAbbr === 'date_after')  typeAbbr = 'after';
        // Combine column, filter type, and value into one chunk
        return `${f.column}_${typeAbbr}_${f.value}`;
      });

      // Join them with double underscores
      let descriptor = parts.join('__');

      // Sanitize for filename (remove or replace invalid chars)
      descriptor = descriptor.replace(/[^\w\d_\-]+/g, '_');

      // Limit length if it's too big
      if (descriptor.length > 80) {
        descriptor = descriptor.substring(0, 80) + '_etc';
      }

      return `filtered_${descriptor}.csv`;
    }

    downloadBtn.on('click', function() {
      if (!filteredData || filteredData.length === 0) return;

      // Convert filtered data back to CSV
      const csv = Papa.unparse(filteredData);

      // Create a Blob and download
      const csvBlob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(csvBlob);
      const tempLink = document.createElement('a');
      tempLink.href = url;
      tempLink.setAttribute('download', createFilteredFileName(currentFilters));
      document.body.appendChild(tempLink);
      tempLink.click();
      document.body.removeChild(tempLink);
    });

    // ========================================================
    // 7) Clickable headers for sorting in the Filtered Tables
    // ========================================================
    // We use event delegation on the document, 
    // listening for clicks on #filteredTable and #modalFilteredTable TH
    $(document).on('click', '#filteredTable thead th.sortable, #modalFilteredTable thead th.sortable', function() {
      const columnName = $(this).data('column');

      // Determine next sort direction
      if (sortState.column === columnName) {
        // Toggle asc/desc
        sortState.direction = (sortState.direction === 'asc') ? 'desc' : 'asc';
      } else {
        // New column to sort, default to asc
        sortState.column = columnName;
        sortState.direction = 'asc';
      }

      // Sort the filteredData in place
      sortFilteredData(columnName, sortState.direction);

      // Re-render both filtered tables with sorted data
      // so main and modal remain in sync
      displayDataInTable(filteredData, filteredTable);
      displayDataInTable(filteredData, modalFilteredTable);
    });

    // Helper to detect type and sort
    function sortFilteredData(columnName, direction) {
      // We'll guess column type (string, number, date) by inspecting the first non-null value
      let sampleValue = null;
      for (const row of filteredData) {
        if (row[columnName] !== null && row[columnName] !== undefined) {
          sampleValue = row[columnName];
          break;
        }
      }

      let isNumber = false;
      let isDate = false;
      if (sampleValue !== null) {
        // Check if it's a valid number
        if (!isNaN(parseFloat(sampleValue)) && isFinite(sampleValue)) {
          isNumber = true;
        }
        // Check if it's a valid date
        const dateTest = new Date(sampleValue);
        if (!isNaN(dateTest.valueOf())) {
          isDate = true;
        }
      }

      // If it looks both numeric and date, we'll prefer numeric 
      if (isNumber && String(sampleValue).match(/^\d+(\.\d+)?$/)) {
        isDate = false; 
      }

      // Actually do the sort
      filteredData.sort((a, b) => {
        let aVal = a[columnName];
        let bVal = b[columnName];

        // Handle missing values
        if (aVal === null || aVal === undefined) aVal = '';
        if (bVal === null || bVal === undefined) bVal = '';

        if (isNumber) {
          aVal = parseFloat(aVal) || 0;
          bVal = parseFloat(bVal) || 0;
        } else if (isDate) {
          aVal = new Date(aVal).getTime() || 0;
          bVal = new Date(bVal).getTime() || 0;
        } else {
          // String comparison (case-insensitive)
          aVal = String(aVal).toLowerCase();
          bVal = String(bVal).toLowerCase();
        }

        if (aVal < bVal) return (direction === 'asc') ? -1 : 1;
        if (aVal > bVal) return (direction === 'asc') ? 1 : -1;
        return 0;
      });
    }

    // =======================
    // 8) Expand Modals
    // =======================
    // When user clicks "Expand" on Original
    expandOriginalBtn.on('click', function() {
      // Display the original data in modal (full screen)
      displayDataInTable(originalData, modalOriginalTable);
      $('#originalExpandModal').modal('show');
    });

    // When user clicks "Expand" on Filtered
    expandFilteredBtn.on('click', function() {
      // Display the filtered data in modal (full screen)
      displayDataInTable(filteredData, modalFilteredTable);
      $('#filteredExpandModal').modal('show');
    });
  </script>
      </div>
    </main>
    <footer class="app-footer">
      <div class="footer-content">
        <div class="social-links">
          <a href="https://discordapp.com/users/Bliss#6318" class="social-link" target="_blank" rel="noopener" aria-label="Discord">
            <i class="fab fa-discord"></i>
            <span>Discord</span>
          </a>
          <a href="https://github.com/brentjlaf" class="social-link" target="_blank" rel="noopener" aria-label="GitHub">
            <i class="fab fa-github"></i>
            <span>GitHub</span>
          </a>
        </div>
        <div class="footer-info">
          <p>&copy; <span id="current-year"></span> BREN7. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
  <script>document.getElementById('current-year').textContent = new Date().getFullYear();</script>

</body>
</html>
