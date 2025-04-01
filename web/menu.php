<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 20px;
    padding: 0;
    text-align: center;
}

/* Page Title */
h2 {
    color: #333;
    padding-bottom: 5px;
    margin-top: 20px;
}

/* Tables */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #91675B;
    color: white;
}

td img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
}

/* Buttons */
button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 5px;
    transition: 0.3s;
}

button:hover {
    opacity: 0.8;
}

button[onclick*='editItem'] {
    background: #28a745;
    color: white;
}

button[onclick*='deleteItem'] {
    background: #dc3545;
    color: white;
}

button[onclick*='toggle'] {
    background: #91675B;
    color: white;
}

/* Forms */
form {
    display: none;
    margin: 20px auto;
    padding: 20px;
    background: white;
    width: 50%;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: left;
}

form input {
    width: 95%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    background: #28a745;
    color: white;
    width: 100%;
}

/* Modals */
#editFoodModal, #editBevModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    width: 40%;
}

#editFoodModal h3, #editBevModal h3 {
    margin-bottom: 15px;
    color: #333;
}

#editFoodModal button, #editBevModal button {
    width: 100%;
    margin-top: 10px;
}

#editFoodModal img, #editBevModal img {
    display: block;
    margin: 10px auto;
    border-radius: 5px;
}





    </style>
</head>
<body>

    <h2>Food</h2>
    <button onclick="$('#foodForm').toggle()">+ Add Food</button>
    <form id="foodForm" style="display:none;" enctype="multipart/form-data">
        <input type="text" id="food_name" placeholder="Name" required>
        <input type="number" id="food_price" placeholder="Price" required>
        <input type="file" id="food_image" accept="image/*" required>
        <input type="text" id="food_description" placeholder="Description">
        <button type="button" onclick="addItem('Food')">Add Food</button>
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="foodTable"></tbody>
    </table>

    <h2>Beverages</h2>
    <button onclick="$('#bevForm').toggle()">+ Add Beverage</button>
    <form id="bevForm" style="display:none;" enctype="multipart/form-data">
        <input type="text" id="bev_name" placeholder="Name" required>
        <input type="file" id="bev_image" accept="image/*" required>
        <input type="number" id="small_price" placeholder="Small Price">
        <input type="number" id="medium_price" placeholder="Medium Price">
        <input type="number" id="large_price" placeholder="Large Price">
        <input type="text" id="bev_description" placeholder="Description">
        <button type="button" onclick="addItem('Beverages')">Add Beverage</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Small Price</th>
                <th>Medium Price</th>
                <th>Large Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="beverageTable"></tbody>
    </table>


    <!-- Edit Food Modal -->
<div id="editFoodModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc;">
    <h3>Edit Food</h3>
    <input type="hidden" id="edit_food_id">
    <input type="text" id="edit_food_name" placeholder="Name">
    <input type="file" id="edit_food_image" accept="image/*">
    <img id="edit_food_preview" src="" width="50"><br>
    <input type="number" id="edit_food_price" placeholder="Price">
    <input type="text" id="edit_food_description" placeholder="Description">
    <button onclick="updateFood()">Update</button>
    <button onclick="$('#editFoodModal').hide()">Cancel</button>
</div>

<!-- Edit Beverage Modal -->
<div id="editBevModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc;">
    <h3>Edit Beverage</h3>
    <input type="hidden" id="edit_bev_id">
    <input type="text" id="edit_bev_name" placeholder="Name">
    <input type="file" id="edit_bev_image" accept="image/*">
    <img id="edit_bev_preview" src="" width="50"><br>
    <input type="number" id="edit_small_price" placeholder="Small Price">
    <input type="number" id="edit_medium_price" placeholder="Medium Price">
    <input type="number" id="edit_large_price" placeholder="Large Price">
    <input type="text" id="edit_bev_description" placeholder="Description">
    <button onclick="updateBeverage()">Update</button>
    <button onclick="$('#editBevModal').hide()">Cancel</button>
</div>







    <script>
        $(document).ready(function() {
            loadItems();
        });

        function loadItems() {
            $.ajax({
                url: "fetch_items.php",
                type: "GET",
                success: function(response) {
                    let data = JSON.parse(response);
                    let foodHtml = "";
                    let beverageHtml = "";

                    data.forEach(item => {
                        if (item.category === "Food") {
                            foodHtml += `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.name}</td>
                                    <td><img src="${item.image_path}" width="50"></td>
                                    <td>${item.price}</td>
                                    <td>${item.description}</td>
                                    <td>
                                        <button onclick="editItem(${item.id})">Edit</button>
                                        <button onclick="deleteItem(${item.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                        } else if (item.category === "Beverages") {
                            beverageHtml += `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.name}</td>
                                    <td><img src="${item.image_path}" width="50"></td>
                                    <td>${item.small_price}</td>
                                    <td>${item.medium_price}</td>
                                    <td>${item.large_price}</td>
                                    <td>${item.description}</td>
                                    <td>
                                        <button onclick="editItem(${item.id})">Edit</button>
                                        <button onclick="deleteItem(${item.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                        }
                    });

                    $("#foodTable").html(foodHtml);
                    $("#beverageTable").html(beverageHtml);
                }
            });
        }

        function addItem(category) {
            let formData = new FormData();
            let imageFile = category === "Food" ? $("#food_image")[0].files[0] : $("#bev_image")[0].files[0];

            formData.append("category", category);
            formData.append("name", category === "Food" ? $("#food_name").val() : $("#bev_name").val());
            formData.append("price", category === "Food" ? $("#food_price").val() : 0);
            formData.append("description", category === "Food" ? $("#food_description").val() : $("#bev_description").val());
            formData.append("image", imageFile);

            if (category === "Beverages") {
                formData.append("small_price", $("#small_price").val());
                formData.append("medium_price", $("#medium_price").val());
                formData.append("large_price", $("#large_price").val());
            }

            $.ajax({
                url: "insert_item.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    alert("Item added successfully!");
                    loadItems();
                }
            });
        }

        function deleteItem(id) {
            if (confirm("Are you sure you want to delete this item?")) {
                $.ajax({
                    url: "delete_item.php",
                    type: "POST",
                    data: { id: id },
                    success: function() {
                        alert("Item deleted successfully!");
                        loadItems();
                    }
                });
            }
        }






       function editItem(id) {
        $.ajax({
            url: "fetch_single_item.php",
            type: "POST",
            data: { id: id },
            success: function(response) {
                let item = JSON.parse(response);

                if (item.category === "Food") {
                    $("#edit_food_id").val(item.id);
                    $("#edit_food_name").val(item.name);
                    $("#edit_food_preview").attr("src", item.image_path);
                    $("#edit_food_price").val(item.price);
                    $("#edit_food_description").val(item.description);
                    $("#editFoodModal").show();
                } else {
                    $("#edit_bev_id").val(item.id);
                    $("#edit_bev_name").val(item.name);
                    $("#edit_bev_preview").attr("src", item.image_path);
                    $("#edit_small_price").val(item.small_price);
                    $("#edit_medium_price").val(item.medium_price);
                    $("#edit_large_price").val(item.large_price);
                    $("#edit_bev_description").val(item.description);
                    $("#editBevModal").show();
                }
            }
        });
    }
    function updateFood() {
        let formData = new FormData();
        formData.append("id", $("#edit_food_id").val());
        formData.append("name", $("#edit_food_name").val());
        formData.append("price", $("#edit_food_price").val());
        formData.append("description", $("#edit_food_description").val());
        
        let file = $("#edit_food_image")[0].files[0];
        if (file) {
            formData.append("image", file);
        } else {
            formData.append("image", ""); // Avoid breaking query
        }

        $.ajax({
            url: "update_item.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                $("#editFoodModal").hide();
                loadItems();  // Refresh the table
            }
        });
    }

    function updateBeverage() {
        let formData = new FormData();
        formData.append("id", $("#edit_bev_id").val());
        formData.append("name", $("#edit_bev_name").val());
        formData.append("small_price", $("#edit_small_price").val());
        formData.append("medium_price", $("#edit_medium_price").val());
        formData.append("large_price", $("#edit_large_price").val());
        formData.append("description", $("#edit_bev_description").val());

        let file = $("#edit_bev_image")[0].files[0];
        if (file) {
            formData.append("image", file);
        } else {
            formData.append("image", "");
        }

        $.ajax({
            url: "update_item.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                $("#editBevModal").hide();
                loadItems();  // Refresh the table
            }
        });
    }





    </script>

</body>
</html>
