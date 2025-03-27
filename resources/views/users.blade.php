<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center">User Management</h1>
        
        <!-- Add User Form -->
        <form id="addUserForm" class="space-y-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Name" required class="w-full p-2 border rounded">
                <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded">
                <input type="text" name="phone" placeholder="Phone" required class="w-full p-2 border rounded">
                <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Add User</button>
        </form>
        
        <!-- User Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-2">ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Phone</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="text-center"></tbody>
            </table>
        </div>
        
        <!-- Edit User Form -->
        <div id="editUserDiv" class="hidden mt-6 bg-gray-50 p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-2">Edit User</h2>
            <form id="editUserForm" class="space-y-4">
                <input type="hidden" name="id" id="editUserId">
                <input type="text" name="name" id="editUserName" required class="w-full p-2 border rounded">
                <input type="email" name="email" id="editUserEmail" required class="w-full p-2 border rounded">
                <input type="text" name="phone" id="editUserPhone" required class="w-full p-2 border rounded">
                <input type="password" name="password" id="editUserPassword" class="w-full p-2 border rounded">
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Update User</button>
            </form>
        </div>
    </div>

    <script>
        // Fetch and display users
        function fetchUsers() {
            $.get("/api/get-users", function(response) {
                let users = response.data;
                let tableBody = "";
                users.forEach(user => {
                    tableBody += `<tr class='border-b'>
                        <td class='p-2'>${user.id}</td>
                        <td class='p-2'>${user.name}</td>
                        <td class='p-2'>${user.email}</td>
                        <td class='p-2'>${user.phone}</td>
                        <td class='p-2'>
                            <button onclick="editUser(${user.id}, '${user.name}', '${user.email}', '${user.phone}')" class='bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600'>Edit</button>
                            <button onclick="deleteUser(${user.id})" class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-2'>Delete</button>
                        </td>
                    </tr>`;
                });
                $("#userTableBody").html(tableBody);
            });
        }
        fetchUsers();

        // Add User
        $("#addUserForm").submit(function(e) {
            e.preventDefault();
            $.post("/api/create-user", $(this).serialize(), function(response) {
                alert(response.message);
                fetchUsers();
            });
        });

        // Delete User
        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: `/api/delete-user/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        alert(response.message);
                        fetchUsers();
                    }
                });
            }
        }

        // Edit User
        function editUser(id, name, email, phone) {
            $("#editUserId").val(id);
            $("#editUserName").val(name);
            $("#editUserEmail").val(email);
            $("#editUserPhone").val(phone);
            $("#editUserDiv").removeClass("hidden");
        }

        // Update User
        $("#editUserForm").submit(function(e) {
            e.preventDefault();
            let id = $("#editUserId").val();
            $.ajax({
                url: `/api/update-user/${id}`,
                type: "PUT",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    $("#editUserDiv").addClass("hidden");
                    fetchUsers();
                }
            });
        });
    </script>
</body>
</html>