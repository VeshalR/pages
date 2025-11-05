<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: userlogin.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KCC Secure Stock - Welcome <?= htmlspecialchars($user['username']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Navigation */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo::before {
            content: "🏢";
            font-size: 28px;
        }

        .user-info {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info::before {
            content: "👤";
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .nav-btn:hover::before {
            left: 100%;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-btn.active {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .logout-btn {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
        }

        .search-container {
            position: relative;
        }

        .search-input {
            padding: 12px 40px 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            width: 250px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: #667eea;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content Area */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 30px;
            margin-bottom: 30px;
        }

        .content-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .content-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .panel-title {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 16px;
            font-weight: 500;
        }

        .action-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .action-card .icon {
            font-size: 30px;
            margin-bottom: 10px;
            display: block;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .sidebar-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .stat-item {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }

        /* Recent Activity */
        .activity-item {
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 0 10px 10px 0;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
        }

        .activity-time {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .activity-text {
            font-size: 14px;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .nav-buttons {
                justify-content: center;
            }
            
            .search-input {
                width: 200px;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .header {
                padding: 15px;
                flex-direction: column;
                text-align: center;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Navigation -->
        <header class="header">
            <div class="logo">KCC SECURE STOCK</div>
            
            <nav class="nav-buttons">
            <a href="userindex.php" class="nav-btn active">🏠 Home</a>
            <a href="appointment.php" class="nav-btn">📅 Book Appointment</a>
            <a href="repairs.php" class="nav-btn">🔧 Track My Repairs</a>
            <a href="parts.php" class="nav-btn">🔩 Computer Parts</a>
            <a href="contact.php" class="nav-btn">📞 Contact Us</a>
            </nav>

            <div style="display: flex; gap: 15px; align-items: center;">
                <div class="user-info">
                    Welcome, <?= htmlspecialchars($user['username']) ?>
                </div>
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search products, services...">
                    <button class="search-btn">🔍</button>
                </div>
                <a href="userlogout.php" class="nav-btn logout-btn">🚪 Logout</a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-panel">
                <h1 class="panel-title">
                    <span>🏠</span>
                    Welcome to Your Dashboard, <?= htmlspecialchars($user['username']) ?>!
                </h1>
                <p class="welcome-message">
                    Your trusted partner for computer repairs, parts, and IT solutions. 
                    We provide professional services with secure stock management and reliable customer support.
                    You are successfully logged in to your personal dashboard.
                </p>

                <div class="quick-actions">
                    <button class="action-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <span class="icon">🛒</span>
                        Buy Now
                    </button>
                    <button class="action-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <span class="icon">🔧</span>
                        Book Repair
                    </button>
                    <button class="action-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <span class="icon">⚙️</span>
                        Browse Parts
                    </button>
                    <button class="action-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                        <span class="icon">🎧</span>
                        Get Support
                    </button>
                </div>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-top: 20px;">
                    <h3 style="color: #667eea; margin-bottom: 15px;">📋 Your Account Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <strong>Username:</strong><br>
                            <?= htmlspecialchars($user['username']) ?>
                        </div>
                        <div>
                            <strong>Account Status:</strong><br>
                            <span style="color: #28a745;">✅ Active</span>
                        </div>
                        <div>
                            <strong>Member Since:</strong><br>
                            <?= date('M Y') ?>
                        </div>
                        <div>
                            <strong>Total Orders:</strong><br>
                            <span style="color: #667eea;">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-panel">
                    <h3 class="sidebar-title">📊 Quick Stats</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">156</div>
                            <div class="stat-label">Products</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">23</div>
                            <div class="stat-label">Active Repairs</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Satisfaction</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Support</div>
                        </div>
                    </div>
                </div>

                <div class="sidebar-panel">
                    <h3 class="sidebar-title">🔔 Recent Activity</h3>
                    <div class="activity-item">
                        <div class="activity-time">Just now</div>
                        <div class="activity-text">Welcome to KCC Secure Stock!</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">2 hours ago</div>
                        <div class="activity-text">New repair request submitted</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">5 hours ago</div>
                        <div class="activity-text">Graphics card shipped</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">1 day ago</div>
                        <div class="activity-text">Repair REP-001 completed</div>
                    </div>
                </div>

                <div class="sidebar-panel">
                    <h3 class="sidebar-title">⭐ Featured Products</h3>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                            <strong>Gaming Graphics Card</strong><br>
                            <span style="color: #667eea;">RM 1,299</span>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                            <strong>High-Speed SSD</strong><br>
                            <span style="color: #667eea;">RM 399</span>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                            <strong>Gaming Memory Kit</strong><br>
                            <span style="color: #667eea;">RM 299</span>
                        </div>
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <script>
        // Add some basic interactivity
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function() {
                const icon = this.querySelector('.icon').textContent;
                const text = this.textContent.trim().replace(icon, '').trim();
                alert(`You clicked: ${text}\n\nThis feature will be implemented soon!`);
            });
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            if (searchTerm) {
                alert(`Searching for: ${searchTerm}\n\nSearch functionality will be implemented soon!`);
            }
        });

        // Enter key for search
        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.search-btn').click();
            }
        });
    </script>
</body>
</html>