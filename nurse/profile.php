<?php
session_start();
include("../database.php");

$user_id = $_SESSION['user_id'] ?? null;
$profile = [];
$msg = "";

// Redirect to login if not logged in
if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Fetch user profile data
$sql = "SELECT full_name, username, email_id, contact_number, user_type, created_at FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $profile = $result->fetch_assoc();
} else {
    $msg = "<p style='color: red;'>User not found.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #f5f9ff 100%);
            min-height: 100vh;
            padding: 20px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 700px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            animation: slideDown 0.6s ease-out;
        }

        .profile-header h1 {
            color: #0D47A1;
            font-size: 2.5rem;
            margin: 0;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .profile-header .subtitle {
            color: #2196F3;
            font-size: 1.1rem;
            margin-top: 8px;
            opacity: 0.8;
        }

        .profile-box {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(13, 71, 161, 0.1);
            padding: 40px;
            color: #0D47A1;
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .profile-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0D47A1, #2196F3, #64B5F6);
        }

        .profile-box h2 {
            color: #0D47A1;
            font-size: 1.8rem;
            font-weight: 500;
            margin: 0 0 30px 0;
            text-align: center;
            position: relative;
        }

        .profile-grid {
            display: grid;
            gap: 20px;
        }

        .profile-item {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            background: #f8fbff;
            border-radius: 12px;
            border-left: 4px solid #2196F3;
            transition: all 0.3s ease;
            position: relative;
        }

        .profile-item:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(33, 150, 243, 0.15);
            background: #f0f8ff;
        }

        .profile-item .icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #2196F3, #64B5F6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .profile-item .content {
            flex: 1;
        }

        .profile-item .label {
            font-weight: 600;
            color: #0D47A1;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .profile-item .value {
            color: #1976D2;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0D47A1, #2196F3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            box-shadow: 0 8px 20px rgba(13, 71, 161, 0.3);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #f44336;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-item:nth-child(1) { animation-delay: 0.1s; }
        .profile-item:nth-child(2) { animation-delay: 0.2s; }
        .profile-item:nth-child(3) { animation-delay: 0.3s; }
        .profile-item:nth-child(4) { animation-delay: 0.4s; }
        .profile-item:nth-child(5) { animation-delay: 0.5s; }
        .profile-item:nth-child(6) { animation-delay: 0.6s; }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .profile-box {
                padding: 25px 20px;
            }
            
            .profile-header h1 {
                font-size: 2rem;
            }
            
            .profile-item {
                padding: 15px;
            }
            
            .profile-item .icon {
                width: 40px;
                height: 40px;
                margin-right: 15px;
            }
        }
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <h1><i class="fas fa-user-circle"></i> User Profile</h1>
            <div class="subtitle">Your account information</div>
        </div>

        <div class="profile-box">
            <?php if ($msg): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($profile)): ?>
                <div class="user-avatar">
                    <?= strtoupper(substr($profile['full_name'], 0, 1)) ?>
                </div>
                
                <h2>My Profile</h2>
                
                <div class="profile-grid">
                    <div class="profile-item">
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="content">
                            <div class="label">Full Name</div>
                            <div class="value"><?= htmlspecialchars($profile['full_name']) ?></div>
                        </div>
                    </div>

                    <div class="profile-item">
                        <div class="icon">
                            <i class="fas fa-at"></i>
                        </div>
                        <div class="content">
                            <div class="label">Username</div>
                            <div class="value"><?= htmlspecialchars($profile['username']) ?></div>
                        </div>
                    </div>

                    <div class="profile-item">
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="content">
                            <div class="label">Email Address</div>
                            <div class="value"><?= htmlspecialchars($profile['email_id']) ?></div>
                        </div>
                    </div>

                    <div class="profile-item">
                        <div class="icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="content">
                            <div class="label">Contact Number</div>
                            <div class="value"><?= htmlspecialchars($profile['contact_number']) ?></div>
                        </div>
                    </div>

                    <div class="profile-item">
                        <div class="icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div class="content">
                            <div class="label">User Type</div>
                            <div class="value"><?= htmlspecialchars($profile['user_type']) ?></div>
                        </div>
                    </div>

                    <div class="profile-item">
                        <div class="icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="content">
                            <div class="label">Member Since</div>
                            <div class="value"><?= htmlspecialchars($profile['created_at']) ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>