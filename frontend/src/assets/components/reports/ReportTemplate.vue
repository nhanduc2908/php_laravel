<template>
    <div class="report-template">
        <div class="template-header">
            <div class="logo"><img src="/assets/images/logo.svg" alt="Logo"><span>{{ appName }}</span></div>
            <div class="report-info"><h1>{{ reportTitle }}</h1><p>Ngày tạo: {{ formatDate(createdAt) }}</p></div>
        </div>
        <div class="template-content">
            <div class="summary-section"><h2>Tóm tắt</h2><div class="summary-stats"><div class="stat"><span class="label">Tổng số máy chủ:</span><span class="value">{{ summary.totalServers }}</span></div>
            <div class="stat"><span class="label">Điểm trung bình:</span><span class="value">{{ summary.avgScore }}%</span></div>
            <div class="stat"><span class="label">Tỷ lệ tuân thủ:</span><span class="value">{{ summary.complianceRate }}%</span></div>
            <div class="stat"><span class="label">Lỗ hổng nghiêm trọng:</span><span class="value">{{ summary.criticalVulns }}</span></div></div></div>
            <div class="details-section" v-if="includeDetails"><h2>Chi tiết</h2><table class="detail-table"><thead><tr><th>Máy chủ</th><th>Điểm</th><th>Tuân thủ</th><th>Lỗ hổng</th></tr></thead><tbody><tr v-for="server in details" :key="server.id"><td>{{ server.name }}</td><td>{{ server.score }}%</td><td>{{ server.compliance }}%</td><td>{{ server.vulnerabilities }}</td></tr></tbody></table></div>
            <div class="charts-section" v-if="includeCharts"><h2>Biểu đồ</h2><div class="charts-placeholder">[Biểu đồ sẽ được hiển thị tại đây]</div></div>
        </div>
        <div class="template-footer"><p>Báo cáo được tạo tự động bởi Security Assessment Platform</p></div>
    </div>
</template>

<script setup>
const props = defineProps({ title: { type: String, default: 'Báo cáo đánh giá an ninh' }, createdAt: { type: String, default: () => new Date().toISOString() }, summary: { type: Object, required: true }, details: { type: Array, default: () => [] }, includeDetails: { type: Boolean, default: true }, includeCharts: { type: Boolean, default: true } })

const appName = import.meta.env.VITE_APP_NAME || 'Security Assessment Platform'
const reportTitle = props.title
const formatDate = (date) => new Date(date).toLocaleString('vi-VN')
</script>

<style scoped>
.report-template { max-width: 900px; margin: 0 auto; background: white; color: #333; font-family: 'Times New Roman', serif; }
.template-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; }
.logo { display: flex; align-items: center; gap: 10px; }
.logo img { width: 40px; height: 40px; }
.logo span { font-size: 18px; font-weight: bold; }
.report-info h1 { margin: 0 0 5px; font-size: 24px; }
.report-info p { margin: 0; color: #666; }
.template-content h2 { font-size: 18px; margin: 20px 0 15px; color: #333; border-left: 4px solid #667eea; padding-left: 12px; }
.summary-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; background: #f5f5f5; padding: 20px; border-radius: 8px; }
.stat { display: flex; justify-content: space-between; }
.stat .label { font-weight: 500; }
.stat .value { font-weight: 600; color: #667eea; }
.detail-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
.detail-table th, .detail-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
.detail-table th { background: #f5f5f5; font-weight: 600; }
.charts-placeholder { background: #f5f5f5; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #999; }
.template-footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 12px; color: #999; }
</style>