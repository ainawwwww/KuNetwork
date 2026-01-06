<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Product Form</h2>
    <form id="productForm" class="mt-4">
        <!-- Category Selection Group -->
        <div class="category-group">
            <div class="row g-3 mb-3">
                <!-- Category Level 1 -->
                <div class="col-md-4">
                    <label for="category1" class="form-label">Category Level 1</label>
                    <select class="form-select" id="category1" name="category1[]" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="1">Electronics</option>
                        <option value="2">Fashion</option>
                        <option value="3">Home Appliances</option>
                    </select>
                </div>

                <!-- Category Level 2 -->
                <div class="col-md-4">
                    <label for="category2" class="form-label">Category Level 2</label>
                    <select class="form-select" id="category2" name="category2[]" required>
                        <option value="" disabled selected>Select Subcategory</option>
                        <option value="1">Mobiles</option>
                        <option value="2">Laptops</option>
                        <option value="3">Clothing</option>
                    </select>
                </div>

                <!-- Category Level 3 -->
                <div class="col-md-4">
                    <label for="category3" class="form-label">Category Level 3</label>
                    <select class="form-select" id="category3" name="category3[]" required>
                        <option value="" disabled selected>Select Sub-Subcategory</option>
                        <option value="1">Smartphones</option>
                        <option value="2">Gaming Laptops</option>
                        <option value="3">Men's Wear</option>
                    </select>
                </div>
            </div>
        </div>
                <!-- Add More Categories Button -->
                <button type="button" id="addMore" class="btn btn-secondary mb-3">More</button>


        <!-- Product Name -->
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" placeholder="Enter product name" required>
        </div>

        <!-- Product Price -->
        <div class="mb-3">
            <label for="productPrice" class="form-label">Product Price</label>
            <input type="number" class="form-control" id="productPrice" name="product_price" placeholder="Enter price" min="0" step="0.01" required>
        </div>


        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // jQuery to add more category levels dynamically
    $(document).ready(function () {
        $('#addMore').on('click', function () {
            const newCategoryGroup = `
                <div class="row g-3 mb-3 category-group">
                    <div class="col-md-4">
                        <label for="category1" class="form-label">Category Level 1</label>
                        <select class="form-select" name="category1[]" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="1">Electronics</option>
                            <option value="2">Fashion</option>
                            <option value="3">Home Appliances</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="category2" class="form-label">Category Level 2</label>
                        <select class="form-select" name="category2[]" required>
                            <option value="" disabled selected>Select Subcategory</option>
                            <option value="1">Mobiles</option>
                            <option value="2">Laptops</option>
                            <option value="3">Clothing</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="category3" class="form-label">Category Level 3</label>
                        <select class="form-select" name="category3[]" required>
                            <option value="" disabled selected>Select Sub-Subcategory</option>
                            <option value="1">Smartphones</option>
                            <option value="2">Gaming Laptops</option>
                            <option value="3">Men's Wear</option>
                        </select>
                    </div>
                </div>
            `;
            // Append the new category group to the form
            $('#addMore').before(newCategoryGroup);
        });
    });
</script>
</body>
</html>
