<?php
include("../database.php");

$user = [];
$selected_user = null;
$msg = "";

// Fetch all users
$sql = "SELECT user_id, username, user_type FROM user";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $user[] = $row;
}

// Handle user selection
if (isset($_GET['user_id'])) {
    $id = intval($_GET['user_id']);
    $sql = "SELECT * FROM user WHERE user_id = $id";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) == 1) {
        $selected_user = mysqli_fetch_assoc($res);
    }
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $sql = "DELETE FROM user WHERE user_id = $id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<p class='success-message' style='color: green;'>User deleted successfully.</p>";
    } else {
        $msg = "<p class='error-message' style='color: red;'>Error deleting user.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Users</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .user-record {
            margin-top: 30px;
            animation: fadeIn 0.4s ease-in-out;
        }

        .user-box {
            border: 2px solid #2196F3;
            border-radius: 8px;
            padding: 15px 20px;
            text-align: center;
            font-weight: 500;
            font-size: 1rem;
            color: #0D47A1;
            background-color: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s;
            min-width: 200px;
            position: relative;
            margin-right: 10px;
            display: inline-block;
            margin-top: 10px;
        }

        .user-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
            cursor: pointer;
            background-color: #f0f8ff;
        }

        .user-box:active {
            transform: scale(0.96);
            background-color: #e3f2fd;
        }

        .user-box a {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
            height: 100%;
        }

        .user-record-box {
            background-color: white;
            border: 2px solid #0D47A1;
            border-radius: 12px;
            padding: 25px 30px;
            max-width: 600px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            font-size: 1.05rem;
            color: #0D47A1;
            animation: fadeIn 0.6s ease-in-out;
        }

        .user-record-box h3 {
            margin-bottom: 20px;
            font-size: 1.3rem;
            border-bottom: 1px solid #2196F3;
            padding-bottom: 10px;
        }

        .user-record-box p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .delete-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: crimson;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <h1>All Users</h1>
    <?= $msg ?>

    <?php foreach ($user as $u): ?>
        <a href="?user_id=<?= $u['user_id'] ?>" class="user-box">
            <?= htmlspecialchars($u['username']) ?> (<?= htmlspecialchars($u['user_type']) ?>)
        </a>
    <?php endforeach; ?>

    <?php if ($selected_user): ?>
        <div class="user-record">
            <div class="user-record-box">
                <h3>User Details</h3>
                <p><strong>Full Name:</strong> <?= htmlspecialchars($selected_user['full_name']) ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($selected_user['username']) ?></p>
                <p><strong>Contact Number:</strong> <?= htmlspecialchars($selected_user['contact_number']) ?></p>
                <p><strong>Email ID:</strong> <?= htmlspecialchars($selected_user['email_id']) ?></p>
                <p><strong>User Type:</strong> <?= htmlspecialchars($selected_user['user_type']) ?></p>
                <p><strong>Created At:</strong> <?= htmlspecialchars($selected_user['created_at']) ?></p>

                <form method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <input type="hidden" name="delete_id" value="<?= $selected_user['user_id'] ?>">
                    <button type="submit" class="delete-btn">Delete User</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>
