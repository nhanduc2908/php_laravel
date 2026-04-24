<template>
    <div class="compliance"><h2>Trạng thái tuân thủ</h2>
        <div class="compliance-summary"><div class="gauge"><canvas ref="gaugeChart"></canvas><h3>{{ overall }}%</h3><p>Tuân thủ tổng thể</p></div></div>
        <table class="data-table"><thead><tr><th>Danh mục</th><th>Điểm</th><th>Trạng thái</th></tr></thead>
        <tbody><tr v-for="cat in categories" :key="cat.id"><td>{{ cat.name }}</td><td>{{ cat.score }}%</td><td><span :class="cat.score >= 70 ? 'badge-success' : 'badge-danger'">{{ cat.score >= 70 ? 'Đạt' : 'Chưa đạt' }}</span></td></tr></tbody></table>
    </div>
</template>