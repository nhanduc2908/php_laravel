<template>
    <div class="score-display">
        <div class="score-main">
            <div class="score-circle" :class="scoreClass">
                <svg viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="54" fill="none" stroke="var(--bg-secondary)" stroke-width="8" />
                    <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="339.292" :stroke-dashoffset="circumference - (circumference * score / 100)" transform="rotate(-90 60 60)" />
                </svg>
                <div class="score-value">{{ score }}<span>%</span></div>
            </div>
            <div class="score-info"><div class="score-grade" :class="scoreClass">{{ grade }}</div><div class="score-label">Điểm tổng thể</div></div>
        </div>
        <div class="score-details"><div class="detail-item"><span class="label">Điểm đạt được:</span><span class="value">{{ earnedScore }} / {{ maxScore }}</span></div>
            <div class="detail-item"><span class="label">Số tiêu chí đạt:</span><span class="value">{{ compliantCount }} / {{ totalCriteria }}</span></div>
            <div class="detail-item"><span class="label">Tỷ lệ tuân thủ:</span><span class="value">{{ complianceRate }}%</span></div>
            <div class="detail-item"><span class="label">Xếp hạng:</span><span class="value grade-text" :class="scoreClass">{{ gradeText }}</span></div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ score: { type: Number, required: true }, earnedScore: { type: Number, default: 0 }, maxScore: { type: Number, default: 0 }, compliantCount: { type: Number, default: 0 }, totalCriteria: { type: Number, default: 0 }, complianceRate: { type: Number, default: 0 } })

const circumference = 2 * Math.PI * 54
const grade = computed(() => { const s = props.score; if (s >= 90) return 'A'; if (s >= 80) return 'B'; if (s >= 70) return 'C'; if (s >= 60) return 'D'; return 'F' })
const gradeText = computed(() => { const s = props.score; if (s >= 90) return 'Xuất sắc'; if (s >= 80) return 'Tốt'; if (s >= 70) return 'Khá'; if (s >= 60) return 'Trung bình'; return 'Yếu' })
const scoreClass = computed(() => { const s = props.score; if (s >= 80) return 'high'; if (s >= 60) return 'medium'; return 'low' })
</script>

<style scoped>
.score-display { background: var(--card-bg); border-radius: 16px; padding: 24px; display: flex; gap: 32px; flex-wrap: wrap; justify-content: center; }
.score-main { display: flex; align-items: center; gap: 24px; }
.score-circle { position: relative; width: 120px; height: 120px; color: var(--primary-600); }
.score-circle svg { width: 100%; height: 100%; transform: rotate(-90deg); }
.score-circle circle { transition: stroke-dashoffset 0.5s; }
.score-value { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 28px; font-weight: 700; text-align: center; }
.score-value span { font-size: 16px; font-weight: 400; }
.score-circle.high { color: #10b981; }
.score-circle.medium { color: #f59e0b; }
.score-circle.low { color: #ef4444; }
.score-grade { font-size: 48px; font-weight: 700; line-height: 1; }
.score-grade.high { color: #10b981; }
.score-grade.medium { color: #f59e0b; }
.score-grade.low { color: #ef4444; }
.score-label { font-size: 14px; color: var(--text-secondary); margin-top: 8px; }
.score-details { flex: 1; display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.detail-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color); }
.detail-item .label { font-size: 14px; color: var(--text-secondary); }
.detail-item .value { font-weight: 600; }
.grade-text.high { color: #10b981; }
.grade-text.medium { color: #f59e0b; }
.grade-text.low { color: #ef4444; }
@media (max-width: 640px) { .score-main { flex-direction: column; text-align: center; } .score-details { grid-template-columns: 1fr; } }
</style>