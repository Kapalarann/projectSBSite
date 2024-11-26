<?php // PHP START

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// AJAX this
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    //ADDING NEW USER
    if ($action === 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("ss", $name, $email, $pass);
        if ($stmt->execute()) {
            echo "User added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

    // EDITING USER - UPDATE SQL
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();
        $stmt->close();

    //DELETE USER
    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    exit;
}

// PHP END
?> 

<!-- HTML START -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            margin-top: 20px;
        }
        th, td {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Welcome to the Dashboard!</h1>

        <!-- User Form Section -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-2">
                <input type="text" class="form-control" id="name" placeholder="Name">
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <input type="email" class="form-control" id="email" placeholder="Email">
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <input type="password" class="form-control" id="password" placeholder="Password">
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <button class="btn btn-primary w-100" onclick="addUser()">Add User</button>
            </div>
        </div>

        <!-- Search Section -->
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <input type="text" class="form-control" id="search" placeholder="Search..." onkeyup="search()">
            </div>
        </div>

        <!-- User Table -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <!-- Data will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // SEARCH USER
        function search() {
            const search = document.getElementById('search').value;
            fetch(`dashboard.php?search=${search}`)
                .then(response => response.json())
                .then(data => {
                    const userTable = document.getElementById('userTable');
                    userTable.innerHTML = '';
                    data.forEach(user => {
                        userTable.innerHTML += `
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editUser(${user.id}, '${user.name}', '${user.email}')">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        // ADD USER
        function addUser() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            fetch('dashboard.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=add&name=${name}&email=${email}&password=${password}`
            }).then(() => {
                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('password').value = '';
                search();
            });
        }

        // EDIT USER
        function editUser(id, name, email) {
            const newName = prompt('Edit Name:', name);
            const newEmail = prompt('Edit Email:', email);
            const newPass = prompt('Edit Password:', '');

            if (newName && newEmail && newPass) {
                fetch('dashboard.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=edit&id=${id}&name=${newName}&email=${newEmail}&password=${newPass}`
                }).then(search);
            }
        }

        // DELETE USER
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('dashboard.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete&id=${id}`
                }).then(search);
            }
        }

        // INITIAL LOAD
        search();
    </script>
</body>
</html>

</body>
</html>
