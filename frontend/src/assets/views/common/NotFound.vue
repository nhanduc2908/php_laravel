<template>
    <div class="error-page">
        <div class="error-container">
            <div class="error-code">404</div>
            <div class="error-icon"><i class="fas fa-map-signs"></i></div>
            <h2>Không tìm thấy trang</h2>
            <p>Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
            <div class="error-actions">
                <router-link to="/" class="btn btn-primary"><i class="fas fa-home"></i> Về trang chủ</router-link>
                <button class="btn btn-secondary" @click="$router.back()"><i class="fas fa-arrow-left"></i> Quay lại</button>
            </div>
            <div class="error-suggestion"><p>Hoặc bạn có thể:</p><ul><li><router-link to="/dashboard">📊 Đến Dashboard</router-link></li><li><router-link to="/servers">🖥️ Quản lý máy chủ</router-link></li><li><router-link to="/reports">📄 Xem báo cáo</router-link></li></ul></div>
        </div>
    </div>
</template>
<style scoped>
.error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.error-container { background: white; border-radius: 20px; padding: 50px; max-width: 500px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.error-code { font-size: 100px; font-weight: bold; color: #667eea; line-height: 1; }
.error-icon { font-size: 60px; color: #764ba2; margin: 20px 0; }
.error-container h2 { font-size: 28px; margin-bottom: 10px; }
.error-container p { color: #666; margin-bottom: 30px; }
.error-actions .btn { margin: 0 5px; padding: 10px 20px; }
.error-suggestion { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
.error-suggestion ul { list-style: none; padding: 0; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
.error-suggestion ul li a { color: #667eea; text-decoration: none; }
.error-suggestion ul li a:hover { text-decoration: underline; }
</style>