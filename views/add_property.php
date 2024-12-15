<form action="../php/add_property.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Property Title" required>
    <input type="text" name="location" placeholder="Location" required>
    <input type="number" name="price" placeholder="Price" required>
    <input type="number" name="bedrooms" placeholder="Number of Bedrooms" required>
    <input type="number" name="bathrooms" placeholder="Number of Bathrooms" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="file" name="image" required>
    <button type="submit">Add Property</button>
</form>
