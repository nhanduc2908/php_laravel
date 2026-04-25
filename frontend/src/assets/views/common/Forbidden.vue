<template>
    <div class="error-page">
        <div class="error-container">
            <div class="error-code">403</div>
            <div class="error-icon"><i class="fas fa-lock"></i></div>
            <h2>Không có quyền truy cập</h2>
            <p>Bạn không có quyền để truy cập vào trang này.</p>
            <div class="alert alert-warning" v-if="message"><i class="fas fa-info-circle"></i> {{ message }}</div>
            <div class="error-actions">
                <router-link to="/" class="btn btn-primary"><i class="fas fa-home"></i> Về trang chủ</router-link>
                <button class="btn btn-secondary" @click="$router.back()"><i class="fas fa-arrow-left"></i> Quay lại</button>
            </div>
            <div class="contact-support"><p>Bạn cần hỗ trợ? <a href="mailto:support@security.com">Liên hệ Admin</a></p></div>
        </div>
    </div>
</template>
<script setup>
import { useRoute } from 'vue-router'
const route = useRoute()
const message = route.query.message || ''
</script>
<style scoped>
.error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.error-container { background: white; border-radius: 20px; padding: 50px; max-width: 500px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.error-code { font-size: 100px; font-weight: bold; color: #f59e0b; line-height: 1; }
.error-icon { font-size: 60px; color: #f59e0b; margin: 20px 0; }
.alert-warning { background-color: #fff3cd; border-color: #ffecb5; color: #856404; padding: 10px; border-radius: 8px; margin: 20px 0; }
.contact-support { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; }
.contact-support a { color: #667eea; text-decoration: none; }
</style>