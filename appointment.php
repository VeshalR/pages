<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: userlogin.php");
    exit;
}
$user = $_SESSION['user'];

// Handle appointment booking
if ($_POST && isset($_POST['book_appointment'])) {
    // Here you would save to database
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    $service = htmlspecialchars($_POST['service']);
    $description = htmlspecialchars($_POST['description']);
    
    // Generate booking ID
    $booking_id = 'APT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    
    // For demo purposes, store in session
    $_SESSION['last_booking'] = [
        'id' => $booking_id,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'date' => $date,
        'time' => $time,
        'service' => $service,
        'description' => $description,
        'status' => 'Confirmed'
    ];
    
    $booking_success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - KCC Secure Stock</title>
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
            text-decoration: none;
        }

        .logo::before {
            content: "🏢";
            font-size: 28px;
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

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-btn.active {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
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

        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 350px;
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

        /* Enhanced Calendar Styles */
        .calendar-container {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 20px;
    margin-bottom: 25px;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.calendar-header {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 15px;
}

.calendar-title {
    color: #4a5568;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.calendar-icon {
    font-size: 1.5rem;
}

.month-navigation {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.month-year-display {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.month-nav {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #4a5568;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.month-nav:hover {
    background: #edf2f7;
    border-color: #cbd5e0;
}

.nav-icon {
    font-size: 1.1rem;
    line-height: 1;
}

.calendar-body {
    width: 100%;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 10px;
    text-align: center;
}

.weekday {
    font-size: 0.85rem;
    font-weight: 600;
    color: #718096;
    padding: 8px 0;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    background: #fff;
    border: 1px solid #edf2f7;
}

.calendar-day:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

.calendar-day.today {
    background: #ebf8ff;
    border-color: #bee3f8;
    color: #3182ce;
    font-weight: 600;
}

.calendar-day.selected {
    background: #4299e1;
    color: white;
    border-color: #3182ce;
    font-weight: 600;
}

.calendar-day.disabled {
    color: #cbd5e0;
    background: #f8fafc;
    cursor: not-allowed;
}

.calendar-day.other-month {
    color: #cbd5e0;
    background: #f8fafc;
}

@media (max-width: 768px) {}
        .calendar-container {
        padding: 15px;
    }
    
    .calendar-title {
        font-size: 1.1rem;
    }
    
    .month-year-display {
        font-size: 1rem;
    }
    
    .month-nav {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .weekday {
        font-size: 0.8rem;
        padding: 6px 0;
    }

        /* Enhanced Time Slots */
        .time-slots-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .time-slots-title {
            font-size: 22px;
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .time-slots-title::before {
            content: '';
            display: block;
            width: 30px;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .time-slot {
            background: white;
            border: 2px solid #f0f2ff;
            border-radius: 12px;
            padding: 18px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .time-slot:hover {
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .time-slot.selected {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .time-slot.selected::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 12px;
            background: white;
            color: #667eea;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .time-slot.booked {
            background: #fff5f5;
            color: #ff5252;
            border-color: #ffebee;
            cursor: not-allowed;
            position: relative;
            overflow: hidden;
        }

        .time-slot.booked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #ff5252;
            transform: translateY(-50%) rotate(-15deg);
            opacity: 0.7;
        }

        .time-slot.booked:hover {
            transform: none;
        }

        /* Booking Form */
        .booking-form {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        .book-btn {
            background: linear-gradient(45deg, #43e97b, #38f9d7);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .book-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.3);
        }

        .book-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message h3 {
            margin-bottom: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .calendar-grid {
                gap: 4px;
            }
            
            .calendar-day {
                padding: 10px 5px;
                min-height: 40px;
                font-size: 14px;
            }
            
            .time-slots-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Navigation -->
        <header class="header">
            <a href="userindex.php" class="logo">KCC SECURE STOCK</a>
            
            <nav class="nav-buttons">
                <a href="userindex.php" class="nav-btn">🏠 Home</a>
                <a href="appointment.php" class="nav-btn active">📅 Book Appointment</a>
                <a href="repairs.php" class="nav-btn">🔧 Track My Repairs</a>
                <a href="parts.php" class="nav-btn">🔩 Computer Parts</a>
                <a href="contact.php" class="nav-btn">📞 Contact Us</a>
            </nav>

            <div class="user-info">
                Welcome, <?= htmlspecialchars($user['username']) ?>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-panel">
                <?php if (isset($booking_success)): ?>
                    <div class="success-message">
                        <h3>🎉 Appointment Booked Successfully!</h3>
                        <p>Your booking ID is: <strong><?= $_SESSION['last_booking']['id'] ?></strong></p>
                        <p>We'll contact you at <?= $_SESSION['last_booking']['email'] ?> with confirmation details.</p>
                    </div>
                <?php endif; ?>

                <h1 class="panel-title">
                    <span>📅</span>
                    Book Your Appointment
                </h1>
                <p class="welcome-message">
                    Schedule a convenient time for your computer repair, consultation, or service. 
                    Select your preferred date and time from our available slots.
                </p>

                <form method="POST" id="appointmentForm">
                    <!-- Calendar Section -->
                    <div class="calendar-container">
    <div class="calendar-header">
        <h3 class="calendar-title">
            <span class="calendar-icon">📆</span>
            Select Date and Time
        </h3>
        
        <div class="month-navigation">
            <button type="button" class="month-nav prev-month" onclick="changeMonth(-1)">
                <span class="nav-icon">‹</span> Previous
            </button>
            
            <div class="month-year-display" id="monthYear">
                June 2024
            </div>
            
            <button type="button" class="month-nav next-month" onclick="changeMonth(1)">
                Next <span class="nav-icon">›</span>
            </button>
        </div>
    </div>

    <div class="calendar-body">
        <div class="calendar-weekdays">
            <div class="weekday">Sun</div>
            <div class="weekday">Mon</div>
            <div class="weekday">Tue</div>
            <div class="weekday">Wed</div>
            <div class="weekday">Thu</div>
            <div class="weekday">Fri</div>
            <div class="weekday">Sat</div>
        </div>
        
        <div class="calendar-days" id="calendarDays">
            <!-- Days will be populated by JavaScript -->
        </div>
    </div>
</div>

                    <!-- Time Slots Section -->
                    <div class="time-slots-container">
                        <h3 class="time-slots-title">
                            🕒 Available Time Slots
                        </h3>
                        <div class="time-slots-grid" id="timeSlots">
                            <!-- Time slots will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <div class="booking-form">
                        <h3 style="color: #667eea; margin-bottom: 20px;">📝 Booking Details</h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-input" required 
                                       value="<?= htmlspecialchars($user['username']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-input" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Service Type *</label>
                                <select name="service" class="form-select" required>
                                    <option value="">Select Service</option>
                                    <option value="Computer Repair">🔧 Computer Repair</option>
                                    <option value="Hardware Installation">⚙️ Hardware Installation</option>
                                    <option value="Software Troubleshooting">💻 Software Troubleshooting</option>
                                    <option value="Data Recovery">💾 Data Recovery</option>
                                    <option value="Virus Removal">🛡️ Virus Removal</option>
                                    <option value="Network Setup">🌐 Network Setup</option>
                                    <option value="Consultation">💡 Technical Consultation</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Describe Your Issue</label>
                            <textarea name="description" class="form-textarea" 
                                      placeholder="Please describe your computer issue or service requirements in detail..."></textarea>
                        </div>

                        <input type="hidden" name="date" id="selectedDate">
                        <input type="hidden" name="time" id="selectedTime">

                        <button type="submit" name="book_appointment" class="book-btn" id="bookBtn" disabled>
                            📅 Book Appointment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-panel">
                    <h3 class="sidebar-title">📋 Booking Information</h3>
                    <div style="background: #f0f4ff; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                        <strong>📅 Selected Date:</strong><br>
                        <span id="displayDate">Please select a date</span>
                    </div>
                    <div style="background: #f0f4ff; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                        <strong>🕒 Selected Time:</strong><br>
                        <span id="displayTime">Please select a time</span>
                    </div>
                    
                    <div style="background: #e8f5e8; padding: 15px; border-radius: 10px;">
                        <h4 style="color: #2e7d32; margin-bottom: 10px;">💡 Booking Tips</h4>
                        <ul style="margin-left: 20px; color: #2e7d32;">
                            <li>Book at least 24 hours in advance</li>
                            <li>Bring all necessary cables and accessories</li>
                            <li>Have your device information ready</li>
                            <li>Describe your issue in detail</li>
                        </ul>
                    </div>
                </div>

                <div class="sidebar-panel">
                    <h3 class="sidebar-title">🕐 Business Hours</h3>
                    <div style="line-height: 1.8;">
                        <strong>Monday - Friday:</strong><br>
                        9:00 AM - 6:00 PM<br><br>
                        <strong>Saturday:</strong><br>
                        9:00 AM - 2:00 PM<br><br>
                        <strong>Sunday:</strong><br>
                        Closed
                    </div>
                </div>

                <div class="sidebar-panel">
                    <h3 class="sidebar-title">📞 Contact Info</h3>
                    <div style="line-height: 1.8;">
                        <strong>Phone:</strong><br>
                        +60 12-345-6789<br><br>
                        <strong>Email:</strong><br>
                        support@kccsecurestock.com<br><br>
                        <strong>Emergency:</strong><br>
                        +60 12-999-0000
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <script>
        let currentDate = new Date();
        let selectedDate = null;
        let selectedTime = null;

        // Available time slots
        const timeSlots = [
            '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
            '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM'
        ];

        // Booked time slots (for demo)
        const bookedSlots = {
            '2024-06-25': ['10:00 AM', '2:30 PM'],
            '2024-06-26': ['9:00 AM', '3:00 PM', '4:00 PM']
        };

        function generateCalendar() {
            const monthYear = document.getElementById('monthYear');
            const calendarDays = document.getElementById('calendarDays');
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const today = new Date();
            
            monthYear.textContent = currentDate.toLocaleDateString('en-US', { 
                month: 'long', 
                year: 'numeric' 
            });
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();
            
            calendarDays.innerHTML = '';
            
            // Add empty cells for previous month days
            for (let i = 0; i < startingDayOfWeek; i++) {
                const day = document.createElement('div');
                day.className = 'calendar-day other-month';
                const prevMonth = new Date(year, month, 0);
                day.textContent = prevMonth.getDate() - startingDayOfWeek + i + 1;
                calendarDays.appendChild(day);
            }
            
            // Add current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                
                const dayDate = new Date(year, month, day);
                
                // Highlight today's date
                if (dayDate.toDateString() === today.toDateString()) {
                    dayElement.classList.add('today');
                }
                
                // Disable past dates and Sundays
                if (dayDate < today || dayDate.getDay() === 0) {
                    dayElement.classList.add('disabled');
                } else {
                    dayElement.onclick = () => selectDate(year, month, day, dayElement);
                }
                
                calendarDays.appendChild(dayElement);
            }
        }

        function selectDate(year, month, day, element) {
            // Remove previous selection
            document.querySelectorAll('.calendar-day.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selection to clicked element
            element.classList.add('selected');
            
            selectedDate = new Date(year, month, day);
            const dateString = selectedDate.toISOString().split('T')[0];
            
            document.getElementById('selectedDate').value = dateString;
            document.getElementById('displayDate').textContent = selectedDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            generateTimeSlots(dateString);
            checkFormCompletion();
        }

        function generateTimeSlots(dateString) {
            const timeSlotsContainer = document.getElementById('timeSlots');
            timeSlotsContainer.innerHTML = '';
            
            timeSlots.forEach(time => {
                const slot = document.createElement('div');
                slot.className = 'time-slot';
                slot.innerHTML = `<span style="font-size: 18px;">${time.split(' ')[0]}</span><br><small>${time.split(' ')[1]}</small>`;
                
                // Check if slot is booked
                if (bookedSlots[dateString] && bookedSlots[dateString].includes(time)) {
                    slot.classList.add('booked');
                    slot.innerHTML = `
                        <span style="font-size: 18px;">${time.split(' ')[0]}</span><br>
                        <small style="color: #ff5252;">Unavailable</small>
                    `;
                } else {
                    slot.onclick = () => selectTime(time, slot);
                }
                
                timeSlotsContainer.appendChild(slot);
            });
        }

        function selectTime(time, element) {
            // Remove previous selection
            document.querySelectorAll('.time-slot.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selection to clicked element
            element.classList.add('selected');
            
            selectedTime = time;
            document.getElementById('selectedTime').value = time;
            document.getElementById('displayTime').textContent = time;
            
            checkFormCompletion();
        }

        function checkFormCompletion() {
            const bookBtn = document.getElementById('bookBtn');
            if (selectedDate && selectedTime) {
                bookBtn.disabled = false;
                bookBtn.textContent = '📅 Book Appointment';
            } else {
                bookBtn.disabled = true;
                bookBtn.textContent = 'Please select date and time';
            }
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar();
            
            // Clear selections when changing month
            selectedDate = null;
            selectedTime = null;
            document.getElementById('selectedDate').value = '';
            document.getElementById('selectedTime').value = '';
            document.getElementById('displayDate').textContent = 'Please select a date';
            document.getElementById('displayTime').textContent = 'Please select a time';
            document.getElementById('timeSlots').innerHTML = '';
            checkFormCompletion();
        }

        // Form validation
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            if (!selectedDate || !selectedTime) {
                e.preventDefault();
                alert('Please select both date and time for your appointment.');
                return false;
            }
            
            const requiredFields = ['name', 'email', 'phone', 'service'];
            for (let field of requiredFields) {
                if (!document.querySelector(`[name="${field}"]`).value.trim()) {
                    e.preventDefault();
                    alert(`Please fill in the ${field.replace('_', ' ')} field.`);
                    return false;
                }
            }
        });

        // Initialize calendar
        generateCalendar();
    </script>
</body>
</html>