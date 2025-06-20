<?php
include("../database.php");
$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["medicine_name"]);
    $sql = "SELECT * FROM stock WHERE LOWER(medicine_name) LIKE LOWER('%$name%')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
    $searchPerformed = true;
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Stock Search</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        /* Enhanced autocomplete styling */
        .search-container {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 400px;
        }

        #medicine_name {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #medicine_name:focus {
            border-color: #4285f4;
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.15);
        }

        .autocomplete-items {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            margin-top: 4px;
            opacity: 0;
            transform: translateY(-10px);
            animation: slideDown 0.2s ease forwards;
        }

        @keyframes slideDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .autocomplete-items::-webkit-scrollbar {
            width: 6px;
        }

        .autocomplete-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .autocomplete-items::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .autocomplete-items::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            transition: background-color 0.15s ease;
            color: #333;
            font-size: 14px;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover,
        .autocomplete-item.selected {
            background: #f8f9fa;
            color: #1a73e8;
        }

        .autocomplete-item::before {
            content: "💊";
            margin-right: 8px;
            font-size: 14px;
            opacity: 0.7;
        }

        .search-btn {
            padding: 12px 24px;
            background: #4285f4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(66, 133, 244, 0.3);
            margin-left: 8px;
        }

        .search-btn:hover {
            background: #3367d6;
            box-shadow: 0 4px 8px rgba(66, 133, 244, 0.4);
            transform: translateY(-1px);
        }

        .search-btn:active {
            transform: translateY(0);
        }

        /* Form styling */
        form {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
            width: 100%;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-btn {
                margin-left: 0;
                margin-top: 8px;
            }
        }

        /* Loading state */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            color: #666;
            font-size: 14px;
        }

        .loading::before {
            content: "";
            width: 16px;
            height: 16px;
            border: 2px solid #e0e0e0;
            border-top: 2px solid #4285f4;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-results {
            padding: 16px;
            text-align: center;
            color: #666;
            font-size: 14px;
            font-style: italic;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("medicine_name");
            let selectedIndex = -1;
            let currentItems = [];

            input.addEventListener("input", function() {
                const query = this.value.trim();
                selectedIndex = -1;
                
                if (query.length === 0) return closeSuggestions();

                // Show loading state
                showLoading();

                // Debounce the API call
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => {
                    fetch(`medicine_suggest.php?term=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        closeSuggestions();
                        currentItems = data;
                        
                        if (data.length === 0) {
                            showNoResults();
                            return;
                        }

                        const list = document.createElement("div");
                        list.setAttribute("id", "autocomplete-list");
                        list.setAttribute("class", "autocomplete-items");
                        input.parentNode.appendChild(list);

                        data.forEach((item, index) => {
                            const option = document.createElement("div");
                            option.setAttribute("class", "autocomplete-item");
                            option.textContent = item;
                            option.addEventListener("click", function() {
                                input.value = item;
                                closeSuggestions();
                                selectedIndex = -1;
                            });
                            
                            option.addEventListener("mouseenter", function() {
                                clearSelection();
                                this.classList.add("selected");
                                selectedIndex = index;
                            });
                            
                            list.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        closeSuggestions();
                    });
                }, 150); // 150ms debounce
            });

            // Keyboard navigation
            input.addEventListener("keydown", function(e) {
                const items = document.querySelectorAll(".autocomplete-item");
                
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    updateSelection(items);
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection(items);
                } else if (e.key === "Enter" && selectedIndex >= 0) {
                    e.preventDefault();
                    if (items[selectedIndex]) {
                        input.value = items[selectedIndex].textContent;
                        closeSuggestions();
                        selectedIndex = -1;
                    }
                } else if (e.key === "Escape") {
                    closeSuggestions();
                    selectedIndex = -1;
                }
            });

            function updateSelection(items) {
                clearSelection();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    items[selectedIndex].classList.add("selected");
                }
            }

            function clearSelection() {
                const items = document.querySelectorAll(".autocomplete-item");
                items.forEach(item => item.classList.remove("selected"));
            }

            function showLoading() {
                closeSuggestions();
                const list = document.createElement("div");
                list.setAttribute("id", "autocomplete-list");
                list.setAttribute("class", "autocomplete-items");
                
                const loading = document.createElement("div");
                loading.setAttribute("class", "loading");
                loading.textContent = "Searching...";
                
                list.appendChild(loading);
                input.parentNode.appendChild(list);
            }

            function showNoResults() {
                closeSuggestions();
                const list = document.createElement("div");
                list.setAttribute("id", "autocomplete-list");
                list.setAttribute("class", "autocomplete-items");
                
                const noResults = document.createElement("div");
                noResults.setAttribute("class", "no-results");
                noResults.textContent = "No medicines found";
                
                list.appendChild(noResults);
                input.parentNode.appendChild(list);
            }

            function closeSuggestions() {
                const items = document.querySelectorAll(".autocomplete-items");
                items.forEach(item => item.remove());
                clearTimeout(window.searchTimeout);
            }

            // Close suggestions when clicking outside
            document.addEventListener("click", function(e) {
                if (!input.contains(e.target) && !e.target.closest('.autocomplete-items')) {
                    closeSuggestions();
                    selectedIndex = -1;
                }
            });

            // Close suggestions when input loses focus (with delay for clicks)
            input.addEventListener("blur", function() {
                setTimeout(() => {
                    closeSuggestions();
                    selectedIndex = -1;
                }, 150);
            });
        });
    </script>
</head>
<body>
    <h1>Check Medicine Stock</h1>
    <form method="post">
        <div class="search-container">
            <label for="medicine_name" >Enter the name of the medicine:</label>
            <input type="text" id="medicine_name" name="medicine_name" required autocomplete="off" placeholder="Type medicine name...">
        </div>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $stock): ?>
                <div class="medicine-record">
                    <strong>Stock ID:</strong> <?= htmlspecialchars($stock['stock_id']) ?><br>
                    <strong>Medicine name:</strong> <?= htmlspecialchars($stock['medicine_name']) ?><br>
                    <strong>Manufacturing company:</strong> <?= htmlspecialchars($stock['company']) ?><br>
                    <strong>Batch:</strong> <?= htmlspecialchars($stock['batch']) ?><br>
                    <strong>Quantity:</strong> <?= htmlspecialchars($stock['quantity']) ?><br>
                    <strong>Purchase date:</strong> <?= htmlspecialchars($stock['purchase_date']) ?><br>
                    <strong>Expiry date:</strong> <?= htmlspecialchars($stock['expiry_date']) ?><br>
                    <strong>Unit type:</strong> <?= htmlspecialchars($stock['unit_type']) ?><br>
                    <strong>Cost per unit:</strong> <?= htmlspecialchars($stock['cost_per_unit']) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($stock['status']) ?><br>
                    <strong>Last updated:</strong> <?= htmlspecialchars($stock['last_updated']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No medicine found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_stock.php" class="new-medication-btn">Add new Medicine:</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>