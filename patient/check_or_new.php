<?php
session_start();
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
if (!$patient_id) {
    echo "No patient ID found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an appointment or check appointment status</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            line-height: 1.6;
            color: #334155;
        }
        .hero-section {
            color: white;
            text-align: center;
            padding: 80px 20px 60px;
        }

        .hero-content h1 {
            font-size: 56px;
            margin-bottom: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .hero-content p {
            font-size: 24px;
            margin-bottom: 40px;
            opacity: 0.9;
            font-weight: 400;
            color: #003366;
        }
        .patient-selection {
            padding: 80px 20px;
            text-align: center;
        }

        .selection-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .selection-title {
            font-size: 42px;
            color: #1e293b;
            margin-bottom: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .selection-subtitle {
            font-size: 20px;
            color: #64748b;
            margin-bottom: 60px;
            line-height: 1.7;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 60px;
        }

        .selection-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            max-width: 800px;
            margin: 0 auto;
        }

        .selection-button {
            background: #ffffff;
            color: #334155;
            border: 2px solid #e2e8f0;
            padding: 48px 32px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 320px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .selection-button:hover {
            border-color: #2563eb;
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.25), 0 0 0 1px rgba(37, 99, 235, 0.05);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .selection-button:active {
            transform: translateY(-4px) scale(1.01);
        }

        .selection-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.08), transparent);
            transition: left 0.6s ease;
        }

        .selection-button:hover::before {
            left: 100%;
        }

        .selection-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(37, 99, 235, 0.1) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .selection-button:hover::after {
            opacity: 1;
        }

        .button-icon {
            font-size: 56px;
            margin-bottom: 20px;
            color: #2563eb;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 4px 8px rgba(37, 99, 235, 0.1));
        }

        .selection-button:hover .button-icon {
            transform: scale(1.15) rotate(5deg);
            filter: drop-shadow(0 8px 16px rgba(37, 99, 235, 0.2));
        }

        .button-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1e293b;
            transition: color 0.3s ease;
            text-align: center;
        }

        .selection-button:hover .button-title {
            color: #2563eb;
        }

        .button-description {
            font-size: 14px;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
            transition: color 0.3s ease;
        }

        .selection-button:hover .button-description {
            color: #475569;
        }
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 40px;
            }

            .hero-content p {
                font-size: 20px;
            }
            .selection-title {
                font-size: 32px;
            }

            .selection-subtitle {
                font-size: 18px;
            }

            .selection-buttons {
                grid-template-columns: 1fr;
                gap: 20px;
                max-width: 360px;
            }

            .selection-button {
                height: 280px;
                padding: 36px 28px;
            }
        }
        @keyframes slideInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <section class="hero-section">
        <div class="hero-content">
            <h1>Welcome to Sinbadh Hospitals</h1>
            <p>Please select an option</p>
        </div>
    </section>
    <section class="patient-selection">
        <div class="selection-container">
            <div class="selection-buttons">
                <a href="check_requests.php?patient_id=<?= $patient_id ?>" class="selection-button">
                    <div class="button-icon">🏥</div>
                    <div class="button-title">Check appointment status</div>
                    <div class="button-description">Check status of your requested appointments</div>
                </a>
                
                <a href="new_request.php?patient_id=<?= $patient_id ?>" class="selection-button">
                    <div class="button-icon">👋</div>
                    <div class="button-title">Book new appointment</div>
                    <div class="button-description">Make a request for a new appointment</div>
                </a>
            </div>
        </div>
    </section>
</body>
</html>
</html>
