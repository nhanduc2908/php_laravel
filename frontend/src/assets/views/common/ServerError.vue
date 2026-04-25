<template>
    <div class="error-page">
        <div class="error-container">
            <div class="error-code">500</div>
            <div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <h2>Lỗi máy chủ nội bộ</h2>
            <p>Đã xảy ra lỗi từ phía máy chủ. Vui lòng thử lại sau.</p>
            <div class="alert alert-danger" v-if="debug"><strong>Debug Info:</strong><br>{{ errorMessage }}<pre class="mt-2"><code>{{ errorTrace }}</code></pre></div>
            <div class="alert alert-info" v-else><i class="fas fa-info-circle"></i> Đội ngũ kỹ thuật đã được thông báo và đang xử lý sự cố.</div>
            <div class="error-actions"><router-link to="/" class="btn btn-primary"><i class="fas fa-home"></i> Về trang chủ</router-link><button class="btn btn-secondary" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Thử lại</button></div>
            <div class="error-reference"><small>Mã tham chiếu: <span id="errorId"></span></small></div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const debug = import.meta.env.DEV
const errorMessage = ref(route.query.message || 'Unknown error')
const errorTrace = ref(route.query.trace || '')
onMounted(() => { document.getElementById('errorId').innerText = 'ERR_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6) })
</script>
<style scoped>
.error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.error-container { background: white; border-radius: 20px; padding: 50px; max-width: 550px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.error-code { font-size: 100px; font-weight: bold; color: #ef4444; line-height: 1; }
.error-icon { font-size: 60px; color: #ef4444; margin: 20px 0; }
.alert-danger { background-color: #fee2e2; border-color: #fecaca; color: #dc2626; text-align: left; margin: 20px 0; }
.alert-danger pre { background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 11px; overflow-x: auto; }
.error-reference { margin-top: 30px; color: #999; }
</style>