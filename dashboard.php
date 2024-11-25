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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
       .form-control input {
            padding: 8px;
            margin-right: 10px;
        }
        .form-control button {
            padding: 8px 20px;
        }
        .search {
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Dashboard!</h1>

    <div class="form-control">
        <input type="text" id="name" placeholder="Name">
        <input type="email" id="email" placeholder="Email">
        <input type="password" id="password" placeholder="Password">
        <button onclick="addUser()">Add User</button>
    </div>

    <div class="search">
        <input type="text" id="search" placeholder="Search..." onkeyup="search()">
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTable">
            
        </tbody>
    </table>

    <script> // SCRIPT START
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
                                <button onclick="editUser(${user.id}, '${user.name}', '${user.email}')">Edit</button>
                                <button onclick="deleteUser(${user.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            });
        }

        //ADD USER
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

        //EDITING
        function editUser(id, name, email) {
            const newName = prompt('Edit Name:', name);
            const newEmail = prompt('Edit Email:', email);
            const newPass = prompt('Edit Password:', password);

            if (newName && newEmail && newPass) {
                fetch('dashboard.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=edit&id=${id}&name=${newName}&email=${newEmail}&password=${newPass}`
                }).then(search);
            }
        }

        // DELETING
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('dashboard.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete&id=${id}`
                }).then(search);
            }
        }

        search();

        // SCRIPT END
    </script>

</body>
</html>
