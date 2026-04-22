<template>
    <div class="vulnerabilities"><h2>Lỗ hổng bảo mật</h2>
        <div class="filters"><select v-model="severityFilter"><option value="">Tất cả</option><option value="critical">Critical</option><option value="high">High</option><option value="medium">Medium</option><option value="low">Low</option></select></div>
        <table class="data-table"><thead><tr><th>CVE</th><th>Tên</th><th>Mức độ</th><th>Trạng thái</th></tr></thead>
        <tbody><tr v-for="v in vulnerabilities" :key="v.id"><td>{{ v.cve }}</td><td>{{ v.name }}</td><td><span :class="`severity-${v.severity}`">{{ v.severity }}</span></td><td>{{ v.status }}</td></tr></tbody></table>
    </div>
</template>