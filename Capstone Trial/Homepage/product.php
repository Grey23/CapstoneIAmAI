<?php
// Sample product data (can be stored in a database later)
$products = [
    ["id" => 1, "name" => "Brightening Facial Serum", "category" => "facial-sets", "price" => 4299, "image" => "Logo2.jpg", "description" => "Our lightweight serum brightens skin tone and reduces dark spots with vitamin C and niacinamide.", "link" => "product.php?id=1"],
    ["id" => 2, "name" => "Whitening Face Cream", "category" => "facial-sets", "price" => 3850, "image" => "Logo2.jpg", "description" => "This rich face cream evens skin tone and provides deep hydration with shea butter and hyaluronic acid.", "link" => "product.php?id=2"],
    ["id" => 3, "name" => "Lip Tint Collection", "category" => "lotions", "price" => 2499, "image" => "Logo2.jpg", "description" => "A set of 3 long-lasting lip tints in flattering shades that provide moisture and natural-looking color.", "link" => "product.php?id=3"],
    ["id" => 4, "name" => "Exfoliating Body Scrub", "category" => "scrubs", "price" => 2999, "image" => "Logo2.jpg", "description" => "Gentle exfoliating scrub reveals smoother skin with natural ingredients that polish without harsh abrasives.", "link" => "product.php?id=4"],
    ["id" => 5, "name" => "Brightening Cushion Foundation", "category" => "facial-sets", "price" => 400000, "image" => "Logo2.jpg", "description" => "Lightweight cushion foundation that brightens and evens skin tone while providing SPF 50 protection.", "link" => "product.php?id=5"],
    ["id" => 6, "name" => "Whitening Shower Gel", "category" => "lotions", "price" => 1850, "image" => "Logo2.jpg", "description" => "Moisturizing shower gel with brightening ingredients for a refreshing and rejuvenating shower experience.", "link" => "product.php?id=6"],
    ["id" => 7, "name" => "Ranniel", "category" => "Facial", "price" => 1850, "image" => "Logo2.jpg", "description" => "Moisturizing shower gel with brightening ingredients for a refreshing and rejuvenating shower experience.", "link" => "product.php?id=6"]

    
];


$products_json = json_encode($products);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I AM AI - Premium Cosmetics & Whitening Products</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="Logo.png" alt="IAM.AI Logo">
            </div>
            
            <div class="nav-links">
                <a href="homepage.php">Home</a>
                <a href="About.php">About US</a>
                <a href="product.php">Merchandise</a>
                <a href="#">About Us</a>
            </div>
            
            <div class="auth-buttons">
                <a href="../login.php" class="login-btn">Log In</a>
                <a href="Appointment.php">
                <button class="get-started-btn">Book Appointment</button></a>
            </div>
        </nav>

    <section class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="search-input" placeholder="Search for products...">
                </div>
                <select id="category-select">
                    <option value="">All Categories</option>
                    <option value="personal-care">Personal Care</option>
                    <option value="soap">Soap</option>
                    <option value="color-cosmetic">Color Cosmetic</option>
                    <option value="haircare">Haircare</option>
                    <option value="perfumee">Perfume</option>
                </select>
                <button class="search-button" onclick="performSearch()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="search-results" id="search-results">
            <div class="results-header">
                <h3 class="results-title">Search Results</h3>
                <span class="results-count" id="results-count">0 products</span>
            </div>
            <div class="products-grid" id="results-grid"></div>
            <div id="no-results" style="display: none;">
                <p>No products found. Try adjusting your search criteria.</p>
            </div>
        </div>
    </div>

    <div id="product-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-product-content">

            </div>
        </div>
    </div>

  
    <script>
        let products = <?php echo $products_json; ?>;
        const modal = document.getElementById("product-modal");
        const modalContent = document.querySelector(".modal-product-content");
        const closeModal = document.querySelector(".close-modal");

        function formatPrice(price) {

            const decimal = parseFloat(price) / 100;
            return '₱' + decimal.toFixed(2);
        }


        function createProductCard(product) {
            const categoryDisplay = product.category
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');

            const formattedPrice = formatPrice(product.price);

            return `
                <div class="product-card" data-product-id="${product.id}">
                    <div class="product-image-container">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                    </div>
                    <div class="product-details">
                        <p class="product-category">${categoryDisplay}</p>
                        <h3 class="product-name">${product.name}</h3>
                        <p class="product-price">${formattedPrice}</p>
                    </div>
                </div>`;
        }


        function performSearch() {
            let query = document.getElementById("search-input").value.toLowerCase().trim();
            let category = document.getElementById("category-select").value;

            $.ajax({
                url: "search.php",
                type: "POST",
                data: {
                    query: query,
                    category: category
                },
                success: function(response) {
                    try {

                        let results = JSON.parse(response);
                        let resultsGrid = document.getElementById("results-grid");
                        let resultsCount = document.getElementById("results-count");
                        let noResults = document.getElementById("no-results");

                        resultsGrid.innerHTML = "";

                        if (results.length === 0) {
                            noResults.style.display = "block";
                            resultsCount.textContent = "0 products";
                        } else {
                            noResults.style.display = "none";
                            resultsCount.textContent = results.length + (results.length === 1 ? " product" : " products");


                            results.forEach(product => {
                                resultsGrid.innerHTML += createProductCard(product);
                            });


                            attachProductCardListeners();
                        }
                    } catch (error) {
                        console.error("Error parsing search results:", error);
                        alert("There was an error processing your search. Please try again.");
                    }
                },
                error: function() {
                    alert("There was an error connecting to the server. Please try again.");
                }
            });
        }


        function showProductModal(product) {
            const categoryDisplay = product.category
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');


            const formattedPrice = formatPrice(product.price);

            modalContent.innerHTML = `
                <div class="modal-product">
                    <div class="modal-product-image">
                        <img src="${product.image}" alt="${product.name}">
                    </div>
                    <div class="modal-product-details">
                        <span class="modal-product-category">${categoryDisplay}</span>
                        <h2 class="modal-product-name">${product.name}</h2>
                        <p class="modal-product-price">${formattedPrice}</p>
                        <p class="modal-product-description">${product.description}</p>
                        <div class="modal-product-actions">
                            <a href="${product.link}" target="_blank" class="view-details-btn">
                                <i class="fas fa-external-link-alt"></i> View Details
                            </a>
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i> Add to Wishlist
                            </button>
                        </div>
                    </div>
                </div>
            `;

            modal.style.display = "flex";
            document.body.style.overflow = "hidden";
        }


        function attachProductCardListeners() {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('click', function() {
                    const productId = parseInt(this.getAttribute('data-product-id'));
                    const product = products.find(p => p.id === productId);
                    if (product) {
                        showProductModal(product);
                    }
                });
            });
        }


        closeModal.addEventListener('click', function() {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        });


        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
                document.body.style.overflow = "auto";
            }
        });


        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = "none";
                document.body.style.overflow = "auto";
            }
        });


        document.getElementById("search-input").addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        });


        window.onload = function() {
            let resultsGrid = document.getElementById("results-grid");
            let resultsCount = document.getElementById("results-count");

            resultsCount.textContent = products.length + " products";
            products.forEach(product => {
                resultsGrid.innerHTML += createProductCard(product);
            });


            attachProductCardListeners();
        }
    </script>

</body>

</html>