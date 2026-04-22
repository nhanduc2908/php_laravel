<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảo trì hệ thống - Security Assessment Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .maintenance-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 550px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 20px;
        }
        .maintenance-icon {
            font-size: 80px;
            color: #f59e0b;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 15px;
            color: #333;
        }
        .maintenance-message {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .progress-bar {
            background: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            margin: 30px 0;
            overflow: hidden;
        }
        .progress-fill {
            background: linear-gradient(90deg, #667eea, #764ba2);
            width: 60%;
            height: 100%;
            border-radius: 10px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 0.6; width: 30%; }
            50% { opacity: 1; width: 70%; }
            100% { opacity: 0.6; width: 30%; }
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .info-box h4 {
            margin-bottom: 10px;
            color: #667eea;
        }
        .info-box ul {
            list-style: none;
            padding-left: 0;
        }
        .info-box ul li {
            padding: 5px 0;
            color: #666;
        }
        .info-box ul li i {
            width: 25px;
            color: #667eea;
        }
        .eta {
            font-size: 14px;
            color: #999;
            margin-top: 20px;
        }
        .social-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .social-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }
        .social-links a:hover {
            text-decoration: underline;
        }
        .refresh-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: transform 0.2s;
        }
        .refresh-btn:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1>Đang bảo trì hệ thống</h1>
        
        <div class="maintenance-message">
            <p>Chúng tôi đang nâng cấp và bảo trì hệ thống để mang đến trải nghiệm tốt nhất.</p>
            <p>Vui lòng quay lại sau.</p>
        </div>
        
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        
        <div class="info-box">
            <h4><i class="fas fa-info-circle"></i> Thông tin bảo trì</h4>
            <ul>
                <li><i class="fas fa-clock"></i> Thời gian dự kiến: 30 phút</li>
                <li><i class="fas fa-calendar"></i> Bắt đầu lúc: {{ now()->format('H:i d/m/Y') }}</li>
                <li><i class="fas fa-wrench"></i> Nội dung: Nâng cấp hệ thống bảo mật</li>
            </ul>
        </div>
        
        <div class="eta">
            <i class="fas fa-hourglass-half"></i> Dự kiến hoàn thành sau <span id="countdown">00:00</span>
        </div>
        
        <button class="refresh-btn" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Kiểm tra lại
        </button>
        
        <div class="social-links">
            <a href="mailto:support@security.com"><i class="fas fa-envelope"></i> Email hỗ trợ</a>
            <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
            <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        // Countdown timer (30 minutes from now)
        function startCountdown() {
            const endTime = new Date();
            endTime.setMinutes(endTime.getMinutes() + 30);
            
            function updateCountdown() {
                const now = new Date();
                const diff = endTime - now;
                
                if (diff <= 0) {
                    document.getElementById('countdown').innerText = '00:00';
                    return;
                }
                
                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                document.getElementById('countdown').innerText = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
        
        startCountdown();
        
        // Auto refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>