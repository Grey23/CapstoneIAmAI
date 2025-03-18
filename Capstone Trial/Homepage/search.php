<?php
// Sample product data (same as in your main file)
$products = [
    ["id" => 1, "name" => "Brightening Facial Serum", "category" => "facial-sets", "price" => 4299, "image" => "Logo2.jpg", "description" => "Our lightweight serum brightens skin tone and reduces dark spots with vitamin C and niacinamide.", "link" => "product.php?id=1"],
    ["id" => 2, "name" => "Whitening Face Cream", "category" => "facial-sets", "price" => 3850, "image" => "Logo2.jpg", "description" => "This rich face cream evens skin tone and provides deep hydration with shea butter and hyaluronic acid.", "link" => "product.php?id=2"],
    ["id" => 3, "name" => "Lip Tint Collection", "category" => "lotions", "price" => 2499, "image" => "Logo2.jpg", "description" => "A set of 3 long-lasting lip tints in flattering shades that provide moisture and natural-looking color.", "link" => "product.php?id=3"],
    ["id" => 4, "name" => "Exfoliating Body Scrub", "category" => "scrubs", "price" => 2999, "image" => "Logo2.jpg", "description" => "Gentle exfoliating scrub reveals smoother skin with natural ingredients that polish without harsh abrasives.", "link" => "product.php?id=4"],
    ["id" => 5, "name" => "Brightening Cushion Foundation", "category" => "facial-sets", "price" => 30000.0, "image" => "Logo2.jpg", "description" => "Lightweight cushion foundation that brightens and evens skin tone while providing SPF 50 protection.", "link" => "product.php?id=5"],
    ["id" => 6, "name" => "Whitening Shower Gel", "category" => "lotions", "price" => 1850, "image" => "Logo2.jpg", "description" => "Moisturizing shower gel with brightening ingredients for a refreshing and rejuvenating shower experience.", "link" => "product.php?id=6"],
    ["id" => 7, "name" => "Ranniel", "category" => "Facial", "price" => 1850, "image" => "Logo2.jpg", "description" => "Moisturizing shower gel with brightening ingredients for a refreshing and rejuvenating shower experience.", "link" => "product.php?id=6"]

];

// Get search parameters
$query = isset($_POST['query']) ? strtolower(trim($_POST['query'])) : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

// Filter products based on search criteria
$filtered_products = [];
foreach ($products as $product) {
    // Check if product matches search criteria
    $name_match = empty($query) || strpos(strtolower($product['name']), $query) !== false;
    $desc_match = empty($query) || strpos(strtolower($product['description']), $query) !== false;
    $category_match = empty($category) || $product['category'] == $category;
    
    // Add product to results if it matches criteria
    if (($name_match || $desc_match) && $category_match) {
        $filtered_products[] = $product;
    }
}


echo json_encode($filtered_products);
?>